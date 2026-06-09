<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Role;
use App\Models\SrsFunctionalRequirement;
use App\Models\SrsNonFunctionalRequirement;
use App\Models\Task;
use App\Services\AI\TaskGenerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AITaskController extends Controller
{
    /**
     * Show the AI task generation preview page.
     * Loads project context, SRS requirements, and team members for the AI.
     */
    public function preview(Project $project): \Illuminate\View\View|\Illuminate\Http\JsonResponse
    {
        $this->authorize('view', $project);

        // Get the SRS document with all requirements
        $srsDocument = $project->srsDocuments()->with([
            'functionalRequirements',
            'nonFunctionalRequirements',
        ])->first();

        // Get team members with their active task counts
        $teamMembers = $this->getTeamMembersWithWorkload($project);

        // Get available roles (system roles + any team-specific roles)
        $systemRoles = $this->getAvailableRoles($project);

        return view('projects.ai-tasks.preview', compact(
            'project',
            'srsDocument',
            'teamMembers',
            'systemRoles'
        ));
    }

    /**
     * Generate tasks via server-side OpenRouter API call.
     * This endpoint is a fallback — the blade view calls OpenRouter directly from JS.
     */
    public function generate(Request $request, Project $project): \Illuminate\Http\JsonResponse
    {
        $this->authorize('view', $project);

        $srsDocument = $project->srsDocuments()->with([
            'functionalRequirements',
            'nonFunctionalRequirements',
        ])->first();

        $teamMembers = $this->getTeamMembersWithWorkload($project);
        $systemRoles = $this->getAvailableRoles($project);

        return response()->json([
            'success' => true,
            'project' => [
                'name' => $project->name,
                'description' => $project->description,
                'type' => $project->type,
                'status' => $project->status,
            ],
            'team' => $teamMembers->map(fn($m) => [
                'user_id' => $m->id,
                'name' => $m->name,
                'role' => $m->role_name ?? $m->role,
                'active_tasks' => $m->active_tasks,
            ]),
            'functional_requirements' => $srsDocument?->functionalRequirements->map(fn($req) => [
                'id' => $req->id,
                'title' => $req->title,
                'description' => $req->description,
                'priority' => $req->priority ?? 'Medium',
            ]) ?? [],
            'non_functional_requirements' => $srsDocument?->nonFunctionalRequirements->map(fn($req) => [
                'id' => $req->id,
                'title' => $req->title,
                'description' => $req->description,
                'priority' => $req->priority ?? 'Medium',
            ]) ?? [],
            'available_roles' => $systemRoles->pluck('name'),
        ]);
    }

    public function startLayeredGeneration(Request $request, Project $project, TaskGenerationService $service)
    {
        $this->authorize('view', $project);

        $validated = $request->validate([
            'srs_document_id' => 'nullable|integer|exists:srs_documents,id',
            'mock_ai' => 'nullable|boolean',
        ]);

        $srsDocument = !empty($validated['srs_document_id'])
            ? $project->srsDocuments()->findOrFail($validated['srs_document_id'])
            : $project->srsDocuments()->with(['functionalRequirements', 'nonFunctionalRequirements'])->first();

        $runId = $service->startLayeredGeneration(
            $project,
            $srsDocument,
            Auth::id(),
            (bool) ($validated['mock_ai'] ?? false)
        );

        return response()->json([
            'success' => true,
            'run_id' => $runId,
            'status' => 'queued',
            'poll_url' => route('projects.ai-tasks.layered.status', [$project, $runId]),
        ]);
    }

    public function layeredStatus(Project $project, string $runId, TaskGenerationService $service)
    {
        $this->authorize('view', $project);

        $state = $service->getLayeredStatus($runId);

        abort_unless((int) ($state['project_id'] ?? 0) === $project->id, 404);

        return response()->json([
            'success' => true,
            'run_id' => $runId,
            'status' => $state['status'],
            'layer' => $state['layer'] ?? 0,
            'preview' => $state['preview'] ?? null,
            'error' => $state['error'] ?? null,
        ]);
    }

    public function commitLayeredGeneration(Project $project, string $runId, TaskGenerationService $service)
    {
        $this->authorize('view', $project);

        $state = $service->getLayeredStatus($runId);

        abort_unless((int) ($state['project_id'] ?? 0) === $project->id, 404);

        $tasks = $service->commitLayeredGeneration($project, $runId, Auth::id());

        return response()->json([
            'success' => true,
            'created' => $tasks->count(),
            'task_ids' => $tasks->pluck('id')->values(),
            'redirect' => route('projects.show', $project),
        ]);
    }

    /**
     * Save the AI-generated tasks to the database.
     */
    public function store(Request $request, Project $project): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.priority' => 'required|in:Low,Medium,High,Critical',
            'tasks.*.estimated_hours' => 'nullable|integer|min:1|max:200',
            'tasks.*.assigned_to' => 'nullable|exists:users,id',
            'tasks.*.required_role' => 'nullable|string|max:100',
            'tasks.*.requirement_type' => 'nullable|in:functional,non_functional',
            'tasks.*.requirement_id' => 'nullable|integer',
        ]);

        $created = 0;
        $errors = [];

        foreach ($request->tasks as $index => $taskData) {
            try {
                // Resolve the requirement morph type
                $requirementType = null;
                $requirementId = null;

                if (!empty($taskData['requirement_id']) && !empty($taskData['requirement_type'])) {
                    if ($taskData['requirement_type'] === 'functional') {
                        $req = SrsFunctionalRequirement::findOrFail($taskData['requirement_id']);
                        $requirementType = SrsFunctionalRequirement::class;
                        $requirementId = $req->id;
                    } elseif ($taskData['requirement_type'] === 'non_functional') {
                        $req = SrsNonFunctionalRequirement::findOrFail($taskData['requirement_id']);
                        $requirementType = SrsNonFunctionalRequirement::class;
                        $requirementId = $req->id;
                    }
                }

                $project->tasks()->create([
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'priority' => $taskData['priority'],
                    'status' => 'To Do',
                    'assigned_to' => $taskData['assigned_to'] ?? null,
                    'created_by' => Auth::id(),
                    'estimated_hours' => $taskData['estimated_hours'] ?? null,
                    'requirement_type' => $requirementType,
                    'requirement_id' => $requirementId,
                ]);

                $created++;
            } catch (\Exception $e) {
                $errors[] = "Task #{$index} failed: " . $e->getMessage();
            }
        }

        if ($created === 0) {
            return response()->json([
                'success' => false,
                'error' => 'No tasks were saved. ' . implode(', ', $errors),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'created' => $created,
            'errors' => $errors,
            'redirect' => route('projects.show', $project),
        ]);
    }

    /**
     * Get team members for the project with their active task workload.
     */
    private function getTeamMembersWithWorkload(Project $project)
    {
        if ($project->team) {
            $members = $project->team->members()->get();
        } else {
            // Solo project — just the owner
            $members = collect([Auth::user()]);
        }

        // Get active task counts per user for this project
        $activeCounts = Task::where('project_id', $project->id)
            ->whereIn('assigned_to', $members->pluck('id'))
            ->whereIn('status', ['To Do', 'In Progress', 'Review'])
            ->selectRaw('assigned_to, COUNT(*) as cnt')
            ->groupBy('assigned_to')
            ->pluck('cnt', 'assigned_to');

        // Attach workload and role info to each member
        return $members->map(function ($member) use ($activeCounts, $project) {
            $member->active_tasks = $activeCounts[$member->id] ?? 0;

            // Try to get their role from the team pivot
            if ($project->team && isset($member->pivot)) {
                $roleId = $member->pivot->role_id ?? null;
                if ($roleId) {
                    $role = Role::find($roleId);
                    $member->role_name = $role?->name;
                } else {
                    $member->role_name = $member->pivot->role ?? null;
                }
            } else {
                $member->role_name = 'Project Owner';
            }

            return $member;
        });
    }

    /**
     * Get available roles for task assignment suggestions.
     */
    private function getAvailableRoles(Project $project): \Illuminate\Support\Collection
    {
        // Get system roles
        $roles = Role::where('is_system_role', true)->get();

        // If team has custom roles, include those too
        if ($project->team_id) {
            $teamRoles = Role::where('team_id', $project->team_id)->get();
            $roles = $roles->merge($teamRoles)->unique('name');
        }

        // Fallback if no roles defined
        if ($roles->isEmpty()) {
            return collect([
                (object)['name' => 'Full Stack Developer'],
                (object)['name' => 'Frontend Developer'],
                (object)['name' => 'Backend Developer'],
                (object)['name' => 'QA Engineer'],
                (object)['name' => 'DevOps Engineer'],
                (object)['name' => 'UI/UX Designer'],
                (object)['name' => 'Project Manager'],
            ]);
        }

        return $roles;
    }
}
