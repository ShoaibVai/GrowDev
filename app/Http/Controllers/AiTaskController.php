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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AITaskController extends Controller
{
    protected TaskGenerationService $taskService;

    public function __construct(TaskGenerationService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Show the AI task generation preview page.
     */
    public function preview(Project $project)
    {
        $this->authorize('update', $project);

        $srsDocument = $project->srsDocuments()->with([
            'functionalRequirements',
            'nonFunctionalRequirements',
        ])->first();

        $teamMembers = $this->getTeamWithRoles($project);
        $systemRoles = Role::where('is_system_role', true)->get();

        return view('projects.ai-tasks.preview', compact(
            'project',
            'srsDocument',
            'teamMembers',
            'systemRoles'
        ));
    }

    /**
     * Generate tasks using AI.
     */
    public function generate(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $srsDocument = $project->srsDocuments()->with([
            'functionalRequirements',
            'nonFunctionalRequirements',
        ])->first();

        // Generate tasks
        $result = $this->taskService->generateTasks($project, $srsDocument);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'] ?? 'Failed to generate tasks',
            ], 422);
        }

        // Assign tasks to team members
        $tasks = $this->taskService->assignTasksToTeam($project, $result['tasks']);

        return response()->json([
            'success' => true,
            'tasks' => $tasks,
            'message' => 'Tasks generated successfully. Review and confirm to save.',
        ]);
    }

    /**
     * Store the generated tasks.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.priority' => 'required|in:Low,Medium,High,Critical',
            'tasks.*.estimated_hours' => 'nullable|integer|min:1|max:200',
            'tasks.*.required_role' => 'nullable|string|max:100',
            'tasks.*.assigned_to' => 'nullable|exists:users,id',
            'tasks.*.requirement_type' => 'nullable|in:functional,non_functional',
            'tasks.*.requirement_id' => 'nullable|integer',
            'tasks.*.dependencies' => 'nullable|array',
        ]);

        $srsDocument = $project->srsDocuments()->first();
        $createdTasks = [];
        $taskIndexMap = []; // Map original index to created task ID

        DB::beginTransaction();

        try {
            foreach ($request->tasks as $index => $taskData) {
                // Determine requirement linkage
                $requirementType = null;
                $requirementId = null;

                if (!empty($taskData['requirement_type']) && !empty($taskData['requirement_id'])) {
                    if ($taskData['requirement_type'] === 'functional') {
                        $requirement = SrsFunctionalRequirement::find($taskData['requirement_id']);
                        if ($requirement && $srsDocument && $requirement->srs_document_id === $srsDocument->id) {
                            $requirementType = SrsFunctionalRequirement::class;
                            $requirementId = $requirement->id;
                        }
                    } elseif ($taskData['requirement_type'] === 'non_functional') {
                        $requirement = SrsNonFunctionalRequirement::find($taskData['requirement_id']);
                        if ($requirement && $srsDocument && $requirement->srs_document_id === $srsDocument->id) {
                            $requirementType = SrsNonFunctionalRequirement::class;
                            $requirementId = $requirement->id;
                        }
                    }
                }

                // Find or create role
                $roleId = null;
                if (!empty($taskData['required_role'])) {
                    $role = Role::where('name', $taskData['required_role'])
                        ->where('is_system_role', true)
                        ->first();
                    $roleId = $role?->id;
                }

                $task = Task::create([
                    'project_id' => $project->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'ai_generated_description' => $taskData['description'] ?? null,
                    'priority' => $taskData['priority'],
                    'status' => 'To Do',
                    'assigned_to' => $taskData['assigned_to'] ?? null,
                    'created_by' => Auth::id(),
                    'estimated_hours' => $taskData['estimated_hours'] ?? null,
                    'required_role_id' => $roleId,
                    'requirement_type' => $requirementType,
                    'requirement_id' => $requirementId,
                    'is_ai_generated' => true,
                ]);

                $createdTasks[] = $task;
                $taskIndexMap[$index] = $task->id;
            }

            // Create task dependencies
            foreach ($request->tasks as $index => $taskData) {
                if (!empty($taskData['dependencies']) && is_array($taskData['dependencies'])) {
                    $task = Task::find($taskIndexMap[$index]);
                    foreach ($taskData['dependencies'] as $depIndex) {
                        if (isset($taskIndexMap[$depIndex])) {
                            DB::table('task_dependencies')->insert([
                                'task_id' => $task->id,
                                'depends_on_task_id' => $taskIndexMap[$depIndex],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            // Notify assigned users
            foreach ($createdTasks as $task) {
                if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
                    $task->assignee?->notify(new \App\Notifications\TaskAssigned($task));
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($createdTasks) . ' tasks created successfully.',
                'redirect' => route('projects.show', $project),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store AI-generated tasks', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to save tasks: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get team members with their roles.
     */
    protected function getTeamWithRoles(Project $project)
    {
        if (!$project->team) {
            return collect([
                (object)[
                    'id' => $project->user_id,
                    'name' => $project->user->name ?? 'Owner',
                    'email' => $project->user->email ?? '',
                    'role' => 'Project Owner',
                    'role_name' => 'Project Owner',
                    'active_tasks' => Task::where('project_id', $project->id)
                        ->where('assigned_to', $project->user_id)
                        ->whereIn('status', ['To Do', 'In Progress', 'Review'])
                        ->count(),
                ]
            ]);
        }

        return $project->team->members()
            ->withPivot(['role', 'role_id'])
            ->get()
            ->map(function ($member) use ($project) {
                $roleName = $member->pivot->role ?? 'Team Member';
                if ($member->pivot->role_id) {
                    $role = Role::find($member->pivot->role_id);
                    $roleName = $role?->name ?? $roleName;
                }

                return (object)[
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'role' => $roleName,
                    'role_name' => $roleName,
                    'role_id' => $member->pivot->role_id,
                    'active_tasks' => Task::where('project_id', $project->id)
                        ->where('assigned_to', $member->id)
                        ->whereIn('status', ['To Do', 'In Progress', 'Review'])
                        ->count(),
                ];
            });
    }
}
