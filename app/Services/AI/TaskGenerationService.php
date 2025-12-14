<?php

namespace App\Services\AI;

use App\Models\Project;
use App\Models\Role;
use App\Models\SrsDocument;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Database\Seeders\SystemRolesSeeder;

class TaskGenerationService
{
    protected string $apiEndpoint;
    protected ?string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiEndpoint = config('services.openai.endpoint', 'https://api.openai.com/v1/chat/completions');
        $this->apiKey = config('services.openai.api_key') ?: '';
        $this->model = config('services.openai.model', 'gpt-4o-mini');
    }

    /**
     * Generate tasks for a project based on its requirements.
     *
     * @param Project $project
     * @param SrsDocument|null $srsDocument
     * @return array{success: bool, tasks: array, error?: string}
     */
    public function generateTasks(Project $project, ?SrsDocument $srsDocument = null): array
    {
        try {
            // Build the context payload
            $payload = $this->buildPayload($project, $srsDocument);

            // Call AI API
            $response = $this->callAI($payload);

            if (!$response['success']) {
                return $response;
            }

            // Parse and validate tasks
            $tasks = $this->parseTasks($response['content']);

            return [
                'success' => true,
                'tasks' => $tasks,
                'raw_response' => $response['content'],
            ];
        } catch (\Exception $e) {
            Log::error('Task generation failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'tasks' => [],
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build the payload for AI task generation.
     */
    protected function buildPayload(Project $project, ?SrsDocument $srsDocument): array
    {
        $teamComposition = $this->getTeamComposition($project);
        $functionalRequirements = [];
        $nonFunctionalRequirements = [];

        if ($srsDocument) {
            $srsDocument->load(['functionalRequirements', 'nonFunctionalRequirements']);
            
            $functionalRequirements = $srsDocument->functionalRequirements->map(fn($req) => [
                'id' => $req->id,
                'section' => $req->section_number,
                'title' => $req->title,
                'description' => $req->description,
                'priority' => $req->priority,
                'acceptance_criteria' => $req->acceptance_criteria,
            ])->toArray();

            $nonFunctionalRequirements = $srsDocument->nonFunctionalRequirements->map(fn($req) => [
                'id' => $req->id,
                'section' => $req->section_number,
                'title' => $req->title,
                'description' => $req->description,
                'category' => $req->category,
                'priority' => $req->priority,
                'target_value' => $req->target_value,
            ])->toArray();
        }

        return [
            'project' => [
                'name' => $project->name,
                'description' => $project->description,
                'type' => $project->type,
                'status' => $project->status,
            ],
            'team' => $teamComposition,
            'functional_requirements' => $functionalRequirements,
            'non_functional_requirements' => $nonFunctionalRequirements,
            'available_roles' => SystemRolesSeeder::getSystemRoleNames(),
        ];
    }

    /**
     * Get team composition with role assignments.
     */
    protected function getTeamComposition(Project $project): array
    {
        if (!$project->team) {
            $activeTasksCount = Task::where('project_id', $project->id)
                ->where('assigned_to', $project->user_id)
                ->whereIn('status', ['To Do', 'In Progress', 'Review'])
                ->count();
                
            return [
                [
                    'user_id' => $project->user_id,
                    'name' => $project->user->name ?? 'Owner',
                    'role' => 'Full Stack Developer',
                    'active_tasks' => $activeTasksCount,
                ]
            ];
        }

        // Get team members with pivot data
        $members = $project->team->members()
            ->withPivot(['role', 'role_id'])
            ->get();
            
        // Fetch all roles in one query
        $roleIds = $members->pluck('pivot.role_id')->filter()->unique();
        $roles = Role::whereIn('id', $roleIds)->pluck('name', 'id');
        
        // Get all active task counts in one query
        $memberIds = $members->pluck('id');
        $taskCounts = Task::where('project_id', $project->id)
            ->whereIn('assigned_to', $memberIds)
            ->whereIn('status', ['To Do', 'In Progress', 'Review'])
            ->selectRaw('assigned_to, count(*) as count')
            ->groupBy('assigned_to')
            ->pluck('count', 'assigned_to');

        return $members->map(function ($member) use ($roles, $taskCounts) {
            $role = null;
            if ($member->pivot->role_id && isset($roles[$member->pivot->role_id])) {
                $role = $roles[$member->pivot->role_id];
            }
            $role = $role ?? $member->pivot->role ?? 'Team Member';

            return [
                'user_id' => $member->id,
                'name' => $member->name,
                'role' => $role,
                'active_tasks' => $taskCounts[$member->id] ?? 0,
            ];
        })->toArray();
    }

    /**
     * Call the AI API to generate tasks.
     */
    protected function callAI(array $payload): array
    {
        $systemPrompt = $this->getSystemPrompt();
        $userPrompt = $this->getUserPrompt($payload);

        // If no API key or dummy key, use mock response
        if (empty($this->apiKey) || str_starts_with($this->apiKey, 'dummy') || str_starts_with($this->apiKey, 'sk-dummy')) {
            return $this->getMockResponse($payload);
        }

        try {
            $response = Http::withoutVerifying()
                ->timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiEndpoint, [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4000,
                ]);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => 'AI API request failed: ' . $response->body(),
                ];
            }

            $content = $response->json('choices.0.message.content');
            
            return [
                'success' => true,
                'content' => $content,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'AI API error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get the system prompt for task generation.
     */
    protected function getSystemPrompt(): string
    {
        return <<<PROMPT
You are an expert project manager and software architect. Your task is to analyze project requirements and generate a structured list of development tasks.

For each task, you must specify:
1. title: Clear, actionable task title (max 100 chars)
2. description: Detailed description of what needs to be done
3. priority: Low, Medium, High, or Critical
4. estimated_hours: Estimated effort in hours (1-40)
5. required_role: One of the available roles that should handle this task
6. requirement_type: 'functional' or 'non_functional' if linked to a requirement
7. requirement_id: The ID of the linked requirement (if applicable)
8. dependencies: Array of task indices (0-based) this task depends on

Guidelines:
- Break down complex requirements into smaller, manageable tasks
- Consider both development and non-development tasks (testing, documentation, review)
- Balance workload across different roles
- Set realistic time estimates
- Create logical task dependencies
- Prioritize based on requirement priority and dependencies

Output Format:
Return ONLY a valid JSON array of task objects. No markdown, no explanation, just the JSON array.
PROMPT;
    }

    /**
     * Get the user prompt with project context.
     */
    protected function getUserPrompt(array $payload): string
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        
        return <<<PROMPT
Generate development tasks for the following project:

{$json}

Return a JSON array of task objects with the structure:
[
  {
    "title": "string",
    "description": "string",
    "priority": "Low|Medium|High|Critical",
    "estimated_hours": number,
    "required_role": "string (from available_roles)",
    "requirement_type": "functional|non_functional|null",
    "requirement_id": number|null,
    "dependencies": [number] (indices of dependent tasks)
  }
]
PROMPT;
    }

    /**
     * Parse tasks from AI response.
     */
    protected function parseTasks(string $content): array
    {
        // Clean up the response - remove markdown code blocks if present
        $content = preg_replace('/^```json?\s*/m', '', $content);
        $content = preg_replace('/```\s*$/m', '', $content);
        $content = trim($content);

        $tasks = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to parse AI response as JSON: ' . json_last_error_msg());
        }

        if (!is_array($tasks)) {
            throw new \RuntimeException('AI response is not a valid task array');
        }

        // Validate and normalize each task
        return array_map(function ($task, $index) {
            return [
                'index' => $index,
                'title' => $this->validateString($task['title'] ?? '', 'title', 255),
                'description' => $task['description'] ?? '',
                'priority' => $this->validatePriority($task['priority'] ?? 'Medium'),
                'estimated_hours' => $this->validateInt($task['estimated_hours'] ?? 4, 1, 200),
                'required_role' => $task['required_role'] ?? 'Full Stack Developer',
                'requirement_type' => $this->validateRequirementType($task['requirement_type'] ?? null),
                'requirement_id' => $task['requirement_id'] ?? null,
                'dependencies' => $task['dependencies'] ?? [],
            ];
        }, $tasks, array_keys($tasks));
    }

    /**
     * Validate string field.
     */
    protected function validateString(?string $value, string $field, int $maxLength): string
    {
        $value = $value ?? '';
        return substr(trim($value), 0, $maxLength);
    }

    /**
     * Validate priority field.
     */
    protected function validatePriority(string $priority): string
    {
        $valid = ['Low', 'Medium', 'High', 'Critical'];
        return in_array($priority, $valid) ? $priority : 'Medium';
    }

    /**
     * Validate integer field.
     */
    protected function validateInt($value, int $min, int $max): int
    {
        $value = (int) $value;
        return max($min, min($max, $value));
    }

    /**
     * Validate requirement type.
     */
    protected function validateRequirementType(?string $type): ?string
    {
        if ($type === 'functional' || $type === 'non_functional') {
            return $type;
        }
        return null;
    }

    /**
     * Generate mock response for development without API key.
     */
    protected function getMockResponse(array $payload): array
    {
        $tasks = [];
        $taskIndex = 0;

        // Generate tasks from functional requirements
        foreach ($payload['functional_requirements'] as $req) {
            // Main development task
            $tasks[] = [
                'title' => 'Implement: ' . substr($req['title'], 0, 80),
                'description' => "Implement the functionality as described:\n\n" . $req['description'],
                'priority' => ucfirst($req['priority'] ?? 'Medium'),
                'estimated_hours' => rand(4, 16),
                'required_role' => $this->suggestRole($req['title'], $req['description']),
                'requirement_type' => 'functional',
                'requirement_id' => $req['id'],
                'dependencies' => [],
            ];
            $devTaskIndex = $taskIndex++;

            // Testing task
            $tasks[] = [
                'title' => 'Test: ' . substr($req['title'], 0, 85),
                'description' => "Write and execute tests for:\n\n" . ($req['acceptance_criteria'] ?? $req['description']),
                'priority' => ucfirst($req['priority'] ?? 'Medium'),
                'estimated_hours' => rand(2, 6),
                'required_role' => 'QA Engineer',
                'requirement_type' => 'functional',
                'requirement_id' => $req['id'],
                'dependencies' => [$devTaskIndex],
            ];
            $taskIndex++;
        }

        // Generate tasks from non-functional requirements
        foreach ($payload['non_functional_requirements'] as $req) {
            $role = $this->suggestRoleForNFR($req['category'] ?? 'other');
            
            $tasks[] = [
                'title' => 'NFR: ' . substr($req['title'], 0, 85),
                'description' => $req['description'] . ($req['target_value'] ? "\n\nTarget: " . $req['target_value'] : ''),
                'priority' => ucfirst($req['priority'] ?? 'Medium'),
                'estimated_hours' => rand(4, 12),
                'required_role' => $role,
                'requirement_type' => 'non_functional',
                'requirement_id' => $req['id'],
                'dependencies' => [],
            ];
            $taskIndex++;
        }

        // Add some general project tasks
        $tasks[] = [
            'title' => 'Project setup and environment configuration',
            'description' => 'Set up development environment, CI/CD pipeline, and project infrastructure.',
            'priority' => 'High',
            'estimated_hours' => 8,
            'required_role' => 'DevOps Engineer',
            'requirement_type' => null,
            'requirement_id' => null,
            'dependencies' => [],
        ];

        $tasks[] = [
            'title' => 'Create project documentation',
            'description' => 'Document project architecture, API endpoints, and developer guides.',
            'priority' => 'Medium',
            'estimated_hours' => 6,
            'required_role' => 'Technical Writer',
            'requirement_type' => null,
            'requirement_id' => null,
            'dependencies' => [],
        ];

        return [
            'success' => true,
            'content' => json_encode($tasks),
        ];
    }

    /**
     * Suggest appropriate role based on task content.
     */
    protected function suggestRole(string $title, string $description): string
    {
        $content = strtolower($title . ' ' . $description);

        if (preg_match('/\b(ui|ux|design|interface|wireframe|mockup|prototype)\b/', $content)) {
            return 'UX/UI Designer';
        }
        if (preg_match('/\b(api|backend|database|server|endpoint|query)\b/', $content)) {
            return 'Backend Developer';
        }
        if (preg_match('/\b(frontend|react|vue|angular|css|html|component)\b/', $content)) {
            return 'Frontend Developer';
        }
        if (preg_match('/\b(test|qa|quality|automation|selenium)\b/', $content)) {
            return 'QA Engineer';
        }
        if (preg_match('/\b(security|auth|encrypt|vulnerab|penetration)\b/', $content)) {
            return 'Security Specialist';
        }
        if (preg_match('/\b(deploy|ci|cd|docker|kubernetes|infrastructure)\b/', $content)) {
            return 'DevOps Engineer';
        }
        if (preg_match('/\b(document|guide|manual|readme)\b/', $content)) {
            return 'Technical Writer';
        }

        return 'Full Stack Developer';
    }

    /**
     * Suggest role for non-functional requirement.
     */
    protected function suggestRoleForNFR(string $category): string
    {
        return match ($category) {
            'performance' => 'Backend Developer',
            'security' => 'Security Specialist',
            'reliability', 'availability' => 'DevOps Engineer',
            'maintainability' => 'Full Stack Developer',
            'scalability' => 'Backend Developer',
            'usability' => 'UX/UI Designer',
            'compatibility' => 'QA Engineer',
            'compliance' => 'Security Specialist',
            default => 'Full Stack Developer',
        };
    }

    /**
     * Assign tasks to team members based on roles and workload.
     *
     * @param Project $project
     * @param array $tasks Generated tasks
     * @return array Tasks with assigned users
     */
    public function assignTasksToTeam(Project $project, array $tasks): array
    {
        $teamMembers = $this->getTeamMembersWithRoles($project);
        
        return array_map(function ($task) use ($teamMembers, $project) {
            $assignee = $this->findBestAssignee($task['required_role'], $teamMembers, $project);
            $task['assigned_to'] = $assignee?->id;
            $task['assignee_name'] = $assignee?->name;
            return $task;
        }, $tasks);
    }

    /**
     * Get team members with their roles.
     */
    protected function getTeamMembersWithRoles(Project $project): Collection
    {
        if (!$project->team) {
            return collect([
                (object)[
                    'id' => $project->user_id,
                    'name' => $project->user->name ?? 'Owner',
                    'role' => 'Full Stack Developer',
                    'role_id' => null,
                    'active_tasks' => 0,
                ]
            ]);
        }

        return $project->team->members()
            ->withPivot(['role', 'role_id'])
            ->get()
            ->map(function ($member) use ($project) {
                $role = null;
                if ($member->pivot->role_id) {
                    $role = Role::find($member->pivot->role_id)?->name;
                }
                
                return (object)[
                    'id' => $member->id,
                    'name' => $member->name,
                    'role' => $role ?? $member->pivot->role ?? 'Team Member',
                    'role_id' => $member->pivot->role_id,
                    'active_tasks' => Task::where('project_id', $project->id)
                        ->where('assigned_to', $member->id)
                        ->whereIn('status', ['To Do', 'In Progress', 'Review'])
                        ->count(),
                ];
            });
    }

    /**
     * Find the best assignee for a task based on role match and workload.
     */
    protected function findBestAssignee(string $requiredRole, Collection $teamMembers, Project $project): ?object
    {
        // First, try exact role match with lowest workload
        $exactMatch = $teamMembers
            ->filter(fn($m) => strcasecmp($m->role, $requiredRole) === 0)
            ->sortBy('active_tasks')
            ->first();

        if ($exactMatch) {
            return $exactMatch;
        }

        // Second, try related roles
        $relatedRoles = $this->getRelatedRoles($requiredRole);
        $relatedMatch = $teamMembers
            ->filter(fn($m) => in_array($m->role, $relatedRoles, true))
            ->sortBy('active_tasks')
            ->first();

        if ($relatedMatch) {
            return $relatedMatch;
        }

        // Third, try Full Stack Developer as fallback
        $fullStack = $teamMembers
            ->filter(fn($m) => $m->role === 'Full Stack Developer')
            ->sortBy('active_tasks')
            ->first();

        if ($fullStack) {
            return $fullStack;
        }

        // Finally, assign to team member with lowest workload
        return $teamMembers->sortBy('active_tasks')->first();
    }

    /**
     * Get roles that are related/compatible with the required role.
     */
    protected function getRelatedRoles(string $role): array
    {
        return match ($role) {
            'Frontend Developer' => ['Full Stack Developer', 'UX/UI Designer'],
            'Backend Developer' => ['Full Stack Developer', 'DevOps Engineer'],
            'Full Stack Developer' => ['Frontend Developer', 'Backend Developer'],
            'QA Engineer' => ['Full Stack Developer', 'Backend Developer'],
            'DevOps Engineer' => ['Backend Developer', 'Full Stack Developer'],
            'Security Specialist' => ['Backend Developer', 'DevOps Engineer'],
            'Technical Writer' => ['Product Owner', 'Full Stack Developer'],
            'UX/UI Designer' => ['Frontend Developer', 'Product Owner'],
            'Product Owner' => ['Scrum Master', 'Technical Writer'],
            'Scrum Master' => ['Product Owner'],
            default => ['Full Stack Developer'],
        };
    }
}
