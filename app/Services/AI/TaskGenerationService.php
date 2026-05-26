<?php

namespace App\Services\AI;

use App\Jobs\GenerateTaskOutlineJob;
use App\Models\Project;
use App\Models\Role;
use App\Models\SrsDocument;
use App\Models\SrsFunctionalRequirement;
use App\Models\SrsNonFunctionalRequirement;
use App\Models\Task;
use App\Models\User;
use Carbon\CarbonImmutable;
use Database\Seeders\SystemRolesSeeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TaskGenerationService
{
    public const PROMPT_SCHEMA_VERSION = 1;

    protected string $apiEndpoint;
    protected ?string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiEndpoint = config('services.openrouter.endpoint', 'https://openrouter.ai/api/v1/chat/completions');
        $this->apiKey = config('services.openrouter.api_key') ?: '';
        $this->model = config('services.openrouter.model', 'openai/gpt-3.5-turbo');
    }

    public function generateTasks(Project $project, ?SrsDocument $srsDocument = null): array
    {
        $runId = $this->startLayeredGeneration(
            $project,
            $srsDocument,
            auth()->id() ?? $project->user_id,
            $this->shouldUseMockAi()
        );

        return [
            'success' => true,
            'run_id' => $runId,
            'status' => 'queued',
        ];
    }

    public function startLayeredGeneration(Project $project, ?SrsDocument $srsDocument, int $requestedBy, bool $mockAi = false): string
    {
        $runId = (string) Str::uuid();

        Cache::put($this->runKey($runId), [
            'run_id' => $runId,
            'status' => 'queued',
            'project_id' => $project->id,
            'srs_document_id' => $srsDocument?->id,
            'requested_by' => $requestedBy,
            'mock_ai' => $mockAi,
            'layer' => 0,
        ], $this->cacheTtl());

        GenerateTaskOutlineJob::dispatch($project->id, $srsDocument?->id, $runId, $requestedBy, $mockAi)
            ->onQueue('ai');

        return $runId;
    }

    public function generateOutline(Project $project, ?SrsDocument $srsDocument, string $runId, bool $mockAi = false): array
    {
        $payload = $this->buildPayload($project, $srsDocument);
        $payload['team'] = $this->getTeamComposition($project);

        $raw = $mockAi ? $this->mockOutline($payload) : $this->callLayer('outline', $payload);
        $outline = $this->validateOutline($raw);
        $outline = $this->detectPredictedFileConflicts($outline);
        $outline = $this->ensureSingleScaffoldPerComponent($outline, $project);
        $outline = $this->assignTasksToTeam($project, $outline);

        Cache::put($this->runLayerKey($runId, 'outline'), [
            'raw' => $this->redactSecrets($raw),
            'normalized' => $outline,
        ], $this->cacheTtl());

        $this->putRunStatus($runId, [
            'status' => 'outline_complete',
            'layer' => 1,
        ]);

        return $outline;
    }

    public function generateScaffolds(Project $project, string $runId, array $outline, bool $mockAi = false): array
    {
        $scaffoldTasks = collect($outline)->where('is_scaffold', true)->values();
        $results = [];

        foreach ($scaffoldTasks as $task) {
            $payload = [
                'project' => $project->only(['id', 'name', 'description', 'type']),
                'scaffold_task' => $task,
                'component_tasks' => collect($outline)->where('component_key', $task['component_key'])->values()->all(),
            ];

            $raw = $mockAi ? $this->mockScaffoldPrompt($task) : $this->callLayer('scaffold', $payload);
            $results[$task['temp_id']] = $this->validateScaffoldPrompt($raw, $task);
            $results[$task['temp_id']]['raw_response'] = $this->redactSecrets($raw);
        }

        Cache::put($this->runLayerKey($runId, 'scaffolds'), $results, $this->cacheTtl());

        $this->putRunStatus($runId, [
            'status' => 'scaffolds_complete',
            'layer' => 2,
        ]);

        return $results;
    }

    public function generateTaskPrompts(Project $project, string $runId, array $outline, array $scaffolds, bool $mockAi = false): array
    {
        $results = [];

        foreach (collect($outline)->where('is_scaffold', false) as $task) {
            $scaffold = $scaffolds[$task['scaffold_temp_id'] ?? ''] ?? null;
            $payload = [
                'project' => $project->only(['id', 'name', 'description', 'type']),
                'task' => $task,
                'scaffold' => $scaffold,
            ];

            $raw = $mockAi ? $this->mockTaskPrompt($task, $scaffold) : $this->callLayer('task', $payload);
            $results[$task['temp_id']] = $this->validateTaskPrompt($raw, $task, $scaffold);
            $results[$task['temp_id']]['raw_response'] = $this->redactSecrets($raw);
        }

        Cache::put($this->runLayerKey($runId, 'tasks'), $results, $this->cacheTtl());

        $this->putRunStatus($runId, [
            'status' => 'ready_to_commit',
            'layer' => 3,
        ]);

        return $results;
    }

    public function commitLayeredGeneration(Project $project, string $runId, int $createdBy): Collection
    {
        $outline = Cache::get($this->runLayerKey($runId, 'outline'))['normalized'] ?? null;
        $scaffolds = Cache::get($this->runLayerKey($runId, 'scaffolds'), []);
        $taskPrompts = Cache::get($this->runLayerKey($runId, 'tasks'), []);

        if (!$outline) {
            throw new \RuntimeException('Layered generation outline is missing or expired.');
        }

        return DB::transaction(function () use ($project, $runId, $createdBy, $outline, $scaffolds, $taskPrompts) {
            $createdByTempId = [];

            foreach (collect($outline)->where('is_scaffold', true) as $item) {
                $prompt = $scaffolds[$item['temp_id']] ?? [];
                $createdByTempId[$item['temp_id']] = $this->persistGeneratedTask(
                    $project,
                    $item,
                    $prompt,
                    null,
                    $runId,
                    $createdBy,
                    'scaffolds'
                );
            }

            foreach (collect($outline)->where('is_scaffold', false) as $item) {
                $scaffoldTask = $createdByTempId[$item['scaffold_temp_id'] ?? ''] ?? null;
                $prompt = $taskPrompts[$item['temp_id']] ?? [];
                $task = $this->persistGeneratedTask(
                    $project,
                    $item,
                    $prompt,
                    $scaffoldTask,
                    $runId,
                    $createdBy,
                    'tasks'
                );

                if ($scaffoldTask) {
                    $task->dependencies()->syncWithoutDetaching([$scaffoldTask->id]);
                }

                $createdByTempId[$item['temp_id']] = $task;
            }

            $this->putRunStatus($runId, [
                'status' => 'committed',
                'layer' => 3,
                'created_task_ids' => collect($createdByTempId)->pluck('id')->values()->all(),
            ]);

            return collect($createdByTempId)->values();
        });
    }

    public function getLayeredStatus(string $runId): array
    {
        $state = Cache::get($this->runKey($runId));

        if (!$state) {
            throw new \RuntimeException('Generation run was not found or has expired.');
        }

        $outline = Cache::get($this->runLayerKey($runId, 'outline'))['normalized'] ?? [];
        $scaffolds = Cache::get($this->runLayerKey($runId, 'scaffolds'), []);
        $taskPrompts = Cache::get($this->runLayerKey($runId, 'tasks'), []);

        return array_merge($state, [
            'preview' => $this->buildPreview($outline, $scaffolds, $taskPrompts),
        ]);
    }

    public function putRunStatus(string $runId, array $attributes): void
    {
        Cache::put($this->runKey($runId), array_merge(Cache::get($this->runKey($runId), []), $attributes), $this->cacheTtl());
    }

    public function runKey(string $runId): string
    {
        return "ai_task_generation:{$runId}";
    }

    public function runLayerKey(string $runId, string $layer): string
    {
        return "ai_task_generation:{$runId}:{$layer}";
    }

    protected function buildPayload(Project $project, ?SrsDocument $srsDocument): array
    {
        $functionalRequirements = [];
        $nonFunctionalRequirements = [];

        if ($srsDocument) {
            $srsDocument->load(['functionalRequirements', 'nonFunctionalRequirements']);

            $functionalRequirements = $srsDocument->functionalRequirements->map(fn ($req) => [
                'id' => $req->id,
                'section' => $req->section_number,
                'title' => $req->title,
                'description' => $req->description,
                'priority' => $req->priority,
                'acceptance_criteria' => $req->acceptance_criteria,
            ])->toArray();

            $nonFunctionalRequirements = $srsDocument->nonFunctionalRequirements->map(fn ($req) => [
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
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'type' => $project->type,
                'status' => $project->status,
            ],
            'team' => $this->getTeamComposition($project),
            'functional_requirements' => $functionalRequirements,
            'non_functional_requirements' => $nonFunctionalRequirements,
            'available_roles' => $this->availableRoleNames($project),
        ];
    }

    protected function getTeamComposition(Project $project): array
    {
        return $this->getTeamMembersWithRolesAndSkills($project)
            ->map(fn ($member) => [
                'user_id' => $member->id,
                'name' => $member->name,
                'role' => $member->role,
                'skills' => $member->skills,
                'active_tasks' => $member->active_tasks,
            ])
            ->values()
            ->all();
    }

    protected function getTeamMembersWithRolesAndSkills(Project $project): Collection
    {
        if (!$project->team) {
            $user = User::with('skills')->find($project->user_id) ?? $project->user;

            return collect([$this->memberPayload($user, 'Full Stack Developer', $project)]);
        }

        $members = $project->team->members()
            ->with(['skills'])
            ->withPivot(['role', 'role_id'])
            ->get();

        $roleIds = $members->pluck('pivot.role_id')->filter()->unique();
        $roles = Role::whereIn('id', $roleIds)->pluck('name', 'id');

        return $members->map(function ($member) use ($roles, $project) {
            $role = $member->pivot->role_id ? ($roles[$member->pivot->role_id] ?? null) : null;

            return $this->memberPayload($member, $role ?? $member->pivot->role ?? 'Team Member', $project);
        });
    }

    protected function memberPayload(User $user, string $role, Project $project): object
    {
        return (object) [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $role,
            'skills' => $user->skills
                ? $user->skills->map(fn ($skill) => [
                    'name' => $skill->skill_name,
                    'proficiency' => $skill->proficiency,
                ])->values()->all()
                : [],
            'active_tasks' => Task::where('project_id', $project->id)
                ->where('assigned_to', $user->id)
                ->whereIn('status', ['To Do', 'In Progress', 'Review'])
                ->count(),
        ];
    }

    protected function validateOutline(array $raw): array
    {
        $items = $raw['tasks'] ?? $raw;

        if (!is_array($items)) {
            throw new \RuntimeException('Layer 1 outline must contain a tasks array.');
        }

        return collect($items)
            ->values()
            ->map(fn ($task, $index) => $this->normalizeOutlineTask(is_array($task) ? $task : [], $index))
            ->all();
    }

    protected function normalizeOutlineTask(array $task, int $index): array
    {
        $component = trim((string) ($task['component'] ?? 'General'));
        $predictedFiles = $this->normalizeStringArray($task['predicted_files'] ?? []);
        $requiredRole = trim((string) ($task['required_role'] ?? 'Full Stack Developer'));
        $tempId = trim((string) ($task['temp_id'] ?? 'T'.($index + 1)));

        return [
            'temp_id' => $tempId !== '' ? $tempId : 'T'.($index + 1),
            'title' => Str::limit(trim((string) ($task['title'] ?? 'Generated task')), 255, ''),
            'description' => trim((string) ($task['description'] ?? '')),
            'priority' => $this->validatePriority((string) ($task['priority'] ?? 'Medium')),
            'component' => $component !== '' ? $component : 'General',
            'component_key' => $this->componentKey($task['component_key'] ?? $component),
            'predicted_files' => $predictedFiles,
            'is_scaffold' => (bool) ($task['is_scaffold'] ?? false),
            'required_role' => $requiredRole !== '' ? $requiredRole : 'Full Stack Developer',
            'required_skills' => $this->normalizeStringArray($task['required_skills'] ?? []),
            'estimated_hours' => max(1, min(200, (int) ($task['estimated_hours'] ?? 4))),
            'requirement_type' => $this->validateRequirementType($task['requirement_type'] ?? null),
            'requirement_id' => $task['requirement_id'] ?? null,
            'dependencies' => $this->normalizeStringArray($task['dependencies'] ?? []),
            'conflict_group' => null,
            'overlapping_files' => [],
        ];
    }

    protected function detectPredictedFileConflicts(array $tasks): array
    {
        $count = count($tasks);

        if ($count === 0) {
            return [];
        }

        $parents = range(0, max(0, $count - 1));

        $find = function (int $index) use (&$parents, &$find): int {
            if ($parents[$index] !== $index) {
                $parents[$index] = $find($parents[$index]);
            }

            return $parents[$index];
        };

        $union = function (int $a, int $b) use (&$parents, $find): void {
            $rootA = $find($a);
            $rootB = $find($b);

            if ($rootA !== $rootB) {
                $parents[$rootB] = $rootA;
            }
        };

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $sharedFiles = array_values(array_intersect($tasks[$i]['predicted_files'], $tasks[$j]['predicted_files']));
                $sameComponent = $tasks[$i]['component_key'] === $tasks[$j]['component_key'];

                if ($sameComponent || $sharedFiles !== []) {
                    $union($i, $j);
                    $tasks[$i]['overlapping_files'] = array_values(array_unique(array_merge($tasks[$i]['overlapping_files'], $sharedFiles)));
                    $tasks[$j]['overlapping_files'] = array_values(array_unique(array_merge($tasks[$j]['overlapping_files'], $sharedFiles)));
                }
            }
        }

        $groups = [];
        foreach (array_keys($tasks) as $index) {
            $groups[$find($index)][] = $index;
        }

        foreach ($groups as $root => $indexes) {
            $componentKey = $tasks[$indexes[0]]['component_key'];
            $component = $tasks[$indexes[0]]['component'];

            foreach ($indexes as $index) {
                $tasks[$index]['component_key'] = $componentKey;
                $tasks[$index]['component'] = $component;
                $tasks[$index]['conflict_group'] = 'G'.$root;
            }
        }

        return $tasks;
    }

    protected function ensureSingleScaffoldPerComponent(array $tasks, Project $project): array
    {
        $normalized = collect($tasks);
        $result = collect();

        foreach ($normalized->groupBy('component_key') as $componentKey => $componentTasks) {
            $componentTasks = $componentTasks->values();
            $needsScaffold = $componentTasks->count() > 1 || $componentTasks->contains('is_scaffold', true);
            $scaffolds = $componentTasks->where('is_scaffold', true)->values();
            $scaffold = null;

            if ($needsScaffold && $scaffolds->isEmpty()) {
                $scaffold = $this->makeAutoScaffoldTask($componentKey, $componentTasks);
                $result->push($scaffold);
            } elseif ($needsScaffold) {
                $scaffold = $scaffolds->sortByDesc(fn ($task) => ($task['estimated_hours'] ?? 0) + count($task['predicted_files'] ?? []))->first();
            }

            foreach ($componentTasks as $task) {
                if ($scaffold && $task['temp_id'] === $scaffold['temp_id']) {
                    $task['is_scaffold'] = true;
                    $task['scaffold_temp_id'] = null;
                } elseif ($scaffold) {
                    $task['is_scaffold'] = false;
                    $task['scaffold_temp_id'] = $scaffold['temp_id'];
                    $task['dependencies'] = array_values(array_unique(array_merge($task['dependencies'], [$scaffold['temp_id']])));
                } else {
                    $task['scaffold_temp_id'] = null;
                }

                $result->push($task);
            }
        }

        return $result->unique('temp_id')->values()->all();
    }

    protected function makeAutoScaffoldTask(string $componentKey, Collection $componentTasks): array
    {
        $first = $componentTasks->first();
        $files = $componentTasks->pluck('predicted_files')->flatten()->unique()->values()->all();
        $role = $componentTasks->pluck('required_role')->countBy()->sortDesc()->keys()->first() ?? 'Full Stack Developer';

        return [
            'temp_id' => 'SCF-'.Str::upper(Str::slug($componentKey, '-')),
            'title' => 'Scaffold: '.$first['component'],
            'description' => 'Create the baseline scaffold, shared contracts, and file layout for '.$first['component'].'.',
            'priority' => 'High',
            'component' => $first['component'],
            'component_key' => $componentKey,
            'predicted_files' => $files,
            'is_scaffold' => true,
            'required_role' => $role,
            'required_skills' => $componentTasks->pluck('required_skills')->flatten()->unique()->values()->all(),
            'estimated_hours' => max(2, min(12, (int) ceil($componentTasks->avg('estimated_hours') ?: 4))),
            'requirement_type' => null,
            'requirement_id' => null,
            'dependencies' => [],
            'conflict_group' => $first['conflict_group'] ?? null,
            'overlapping_files' => $files,
            'scaffold_temp_id' => null,
        ];
    }

    public function assignTasksToTeam(Project $project, array $tasks): array
    {
        $members = $this->getTeamMembersWithRolesAndSkills($project);

        return collect($tasks)->map(function (array $task) use ($project, $members) {
            $assignee = !empty($task['is_scaffold'])
                ? $this->selectScaffoldOwner($project, $task, $members)
                : $this->findBestAssignee($task, $members);

            $task['assigned_to'] = $assignee?->id;
            $task['assignee_name'] = $assignee?->name;

            if (!empty($task['is_scaffold'])) {
                $task['scaffold_owner_id'] = $assignee?->id;
            }

            return $task;
        })->all();
    }

    protected function selectScaffoldOwner(Project $project, array $task, Collection $members): ?object
    {
        return $members
            ->sortByDesc(fn ($member) => $this->scaffoldOwnerScore($project, $task, $member))
            ->first();
    }

    protected function scaffoldOwnerScore(Project $project, array $task, object $member): float
    {
        $score = $this->assigneeScore($task, $member);

        $priorOwnership = Task::where('project_id', $project->id)
            ->where('component_key', $task['component_key'] ?? null)
            ->where('scaffold_owner_id', $member->id)
            ->count();

        return $score + ($priorOwnership * 8);
    }

    protected function findBestAssignee(array $task, Collection $members): ?object
    {
        return $members
            ->sortByDesc(fn ($member) => $this->assigneeScore($task, $member))
            ->first();
    }

    protected function assigneeScore(array $task, object $member): float
    {
        $requiredRole = $task['required_role'] ?? 'Full Stack Developer';
        $score = 0.0;

        if (strcasecmp($member->role, $requiredRole) === 0) {
            $score += 50;
        } elseif (in_array($member->role, $this->getRelatedRoles($requiredRole), true)) {
            $score += 25;
        }

        $neededSkills = collect($task['required_skills'] ?? [])
            ->merge($this->skillsFromFiles($task['predicted_files'] ?? []))
            ->map(fn ($skill) => Str::lower($skill))
            ->unique();

        foreach ($member->skills as $skill) {
            if ($neededSkills->contains(Str::lower($skill['name']))) {
                $score += match ($skill['proficiency'] ?? 'intermediate') {
                    'expert' => 12,
                    'advanced' => 9,
                    'intermediate' => 6,
                    default => 3,
                };
            }
        }

        return $score - ((int) $member->active_tasks * 4);
    }

    protected function persistGeneratedTask(
        Project $project,
        array $item,
        array $prompt,
        ?Task $scaffoldTask,
        string $runId,
        int $createdBy,
        string $payloadBucket
    ): Task {
        $promptSection = $prompt['prompt_section'] ?? $this->fallbackPromptSection($item, $scaffoldTask);
        $promptSection = (string) $this->redactSecrets($promptSection);
        $item = $this->redactSecrets($item);
        $prompt = $this->redactSecrets($prompt);
        $description = trim(($item['description'] ?? '') . "\n\n" . $promptSection);
        $requirement = $this->resolveRequirement($item);

        return $project->tasks()->create([
            'title' => $item['title'],
            'description' => $description,
            'ai_generated_description' => $item['description'] ?? null,
            'prompt_section' => $promptSection,
            'prompt_payload' => [
                'outline' => $item,
                $payloadBucket => [
                    $item['temp_id'] => $prompt,
                ],
                'scaffold' => $scaffoldTask ? [
                    'id' => $scaffoldTask->id,
                    'component' => $scaffoldTask->component,
                    'predicted_files' => $scaffoldTask->predicted_files,
                    'interface_contracts' => $scaffoldTask->interface_contracts,
                ] : null,
            ],
            'prompt_brief' => Str::limit($prompt['brief'] ?? $item['title'], 240),
            'component' => $item['component'] ?? null,
            'component_key' => $item['component_key'] ?? null,
            'predicted_files' => $item['predicted_files'] ?? [],
            'interface_contracts' => $prompt['interface_contracts'] ?? [],
            'required_role' => $item['required_role'] ?? null,
            'required_role_id' => $this->roleIdForName($item['required_role'] ?? null),
            'assigned_to' => $item['assigned_to'] ?? null,
            'assigned_at' => !empty($item['assigned_to']) ? now() : null,
            'due_at' => !empty($item['assigned_to']) ? $this->calculateDueAt($item['estimated_hours'] ?? null) : null,
            'estimated_hours' => $item['estimated_hours'] ?? null,
            'time_estimate_hours' => $item['estimated_hours'] ?? null,
            'priority' => $item['priority'] ?? 'Medium',
            'status' => 'To Do',
            'created_by' => $createdBy,
            'requirement_type' => $requirement['type'],
            'requirement_id' => $requirement['id'],
            'is_ai_generated' => true,
            'ai_generation_run_uuid' => $runId,
            'prompt_schema_version' => self::PROMPT_SCHEMA_VERSION,
            'is_scaffold' => (bool) ($item['is_scaffold'] ?? false),
            'scaffold_owner_id' => $item['scaffold_owner_id'] ?? $scaffoldTask?->scaffold_owner_id,
            'scaffold_task_id' => $scaffoldTask?->id,
        ]);
    }

    protected function callLayer(string $layer, array $payload): array
    {
        if ($this->shouldUseMockAi()) {
            return match ($layer) {
                'outline' => $this->mockOutline($payload),
                'scaffold' => $this->mockScaffoldPrompt($payload['scaffold_task']),
                'task' => $this->mockTaskPrompt($payload['task'], $payload['scaffold'] ?? null),
                default => throw new \InvalidArgumentException("Unknown AI layer [{$layer}]."),
            };
        }

        $response = Http::withoutVerifying()
            ->timeout(config('tasks.ai.timeout_seconds', 90))
            ->retry(2, 500)
            ->withToken($this->apiKey)
            ->post($this->apiEndpoint, [
                'model' => $this->model,
                'messages' => $this->messagesForLayer($layer, $payload),
                'temperature' => 0.2,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException("AI {$layer} request failed: ".$response->body());
        }

        return json_decode($response->json('choices.0.message.content'), true, flags: JSON_THROW_ON_ERROR);
    }

    protected function messagesForLayer(string $layer, array $payload): array
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return match ($layer) {
            'outline' => [
                ['role' => 'system', 'content' => 'You are a senior software delivery planner. Return only valid JSON. Create implementation tasks from SRS requirements and team composition. Include component-aware grouping, predicted files, role/skill needs, workload-sensitive estimates, dependencies, and scaffold suggestions. Prefer one scaffold for each connected component or page touched by multiple tasks.'],
                ['role' => 'user', 'content' => 'Project/SRS/team JSON: '.$json.'. Return { "tasks": [ { "temp_id": "T1", "title": "", "description": "", "priority": "Low|Medium|High|Critical", "component": "", "predicted_files": [], "is_scaffold": false, "required_role": "", "required_skills": [], "estimated_hours": 1, "requirement_type": "functional|non_functional|null", "requirement_id": null, "dependencies": [] } ] }. Use stable temp ids. If several tasks touch the same component or overlapping files, mark one baseline scaffold candidate.'],
            ],
            'scaffold' => [
                ['role' => 'system', 'content' => 'You are a principal engineer writing a coding-AI scaffold prompt. Return only valid JSON. Define the baseline implementation contract for one component so later tasks can build safely on it. Be specific about file layout, API contracts, interfaces, test hooks, and merge expectations.'],
                ['role' => 'user', 'content' => 'Project JSON, scaffold task, and all component tasks: '.$json.'. Return { "scaffold_temp_id": "", "component": "", "predicted_files": [], "interface_contracts": { "routes": [], "controllers": [], "models": [], "views": [], "events": [], "tests": [] }, "prompt_section": "", "brief": "", "expected_outputs": [] }. The prompt_section must name the scaffold owner role, component, files, contracts, and completion criteria.'],
            ],
            'task' => [
                ['role' => 'system', 'content' => 'You are a senior coding-AI prompt engineer. Return only valid JSON. Create a task-specific prompt that depends on the scaffold artifacts. The prompt must prevent duplicate scaffolding, reference exact contracts/file paths, request tests, and list expected outputs.'],
                ['role' => 'user', 'content' => 'Project JSON, task JSON, and scaffold JSON: '.$json.'. Return { "task_temp_id": "", "scaffold_temp_id": "", "prompt_section": "", "brief": "", "uses_scaffold_contracts": true, "referenced_files": [], "test_plan": [], "expected_outputs": [] }. The prompt_section must include component name, predicted_files, interface contracts, scaffold id/temp id, dependency warning, and coding instructions.'],
            ],
            default => throw new \InvalidArgumentException("Unknown AI layer [{$layer}]."),
        };
    }

    protected function validateScaffoldPrompt(array $raw, array $task): array
    {
        foreach (['scaffold_temp_id', 'component', 'prompt_section', 'brief'] as $field) {
            if (!array_key_exists($field, $raw)) {
                throw new \RuntimeException("Layer 2 scaffold prompt missing [{$field}].");
            }
        }

        return [
            'scaffold_temp_id' => (string) $raw['scaffold_temp_id'],
            'component' => (string) $raw['component'],
            'predicted_files' => $this->normalizeStringArray($raw['predicted_files'] ?? $task['predicted_files'] ?? []),
            'interface_contracts' => is_array($raw['interface_contracts'] ?? null) ? $raw['interface_contracts'] : [],
            'prompt_section' => (string) $raw['prompt_section'],
            'brief' => (string) $raw['brief'],
            'expected_outputs' => $this->normalizeStringArray($raw['expected_outputs'] ?? []),
        ];
    }

    protected function validateTaskPrompt(array $raw, array $task, ?array $scaffold): array
    {
        foreach (['task_temp_id', 'prompt_section', 'brief'] as $field) {
            if (!array_key_exists($field, $raw)) {
                throw new \RuntimeException("Layer 3 task prompt missing [{$field}].");
            }
        }

        if (($raw['uses_scaffold_contracts'] ?? false) !== true && $scaffold !== null) {
            throw new \RuntimeException('Layer 3 task prompt must reference scaffold contracts.');
        }

        return [
            'task_temp_id' => (string) $raw['task_temp_id'],
            'scaffold_temp_id' => (string) ($raw['scaffold_temp_id'] ?? $task['scaffold_temp_id'] ?? ''),
            'prompt_section' => (string) $raw['prompt_section'],
            'brief' => (string) $raw['brief'],
            'uses_scaffold_contracts' => (bool) ($raw['uses_scaffold_contracts'] ?? false),
            'referenced_files' => $this->normalizeStringArray($raw['referenced_files'] ?? $task['predicted_files'] ?? []),
            'test_plan' => $this->normalizeStringArray($raw['test_plan'] ?? []),
            'expected_outputs' => $this->normalizeStringArray($raw['expected_outputs'] ?? []),
            'interface_contracts' => $scaffold['interface_contracts'] ?? [],
        ];
    }

    public function redactSecrets(mixed $value): mixed
    {
        if (is_array($value)) {
            $redacted = [];

            foreach ($value as $key => $item) {
                if (preg_match('/(api[_-]?key|token|secret|password|bearer)/i', (string) $key)) {
                    $redacted[$key] = '[REDACTED]';
                    continue;
                }

                $redacted[$key] = $this->redactSecrets($item);
            }

            return $redacted;
        }

        if (is_string($value)) {
            $patterns = [
                '/Bearer\s+[A-Za-z0-9._~+\-\/]+=*/i',
                '/sk-[A-Za-z0-9_\-]{8,}/',
                '/ghp_[A-Za-z0-9_]{8,}/',
                '/xox[baprs]-[A-Za-z0-9\-]{8,}/',
            ];

            return preg_replace($patterns, '[REDACTED]', $value);
        }

        return $value;
    }

    public function calculateDueAt(?float $hours): ?\Carbon\CarbonInterface
    {
        $hours = $hours ?: (float) config('tasks.timers.default_estimate_hours', 4);
        $remainingMinutes = max(1, (int) ceil($hours * 60));
        $cursor = CarbonImmutable::now();
        [$startHour, $startMinute] = array_map('intval', explode(':', config('tasks.timers.workday_start', '09:00')));
        [$endHour, $endMinute] = array_map('intval', explode(':', config('tasks.timers.workday_end', '17:00')));

        while ($remainingMinutes > 0) {
            if (config('tasks.timers.skip_weekends', true) && $cursor->isWeekend()) {
                $cursor = $cursor->addDay()->setTime($startHour, $startMinute);
                continue;
            }

            $workStart = $cursor->setTime($startHour, $startMinute);
            $workEnd = $cursor->setTime($endHour, $endMinute);

            if ($cursor->lessThan($workStart)) {
                $cursor = $workStart;
            }

            if ($cursor->greaterThanOrEqualTo($workEnd)) {
                $cursor = $cursor->addDay()->setTime($startHour, $startMinute);
                continue;
            }

            $availableMinutes = $cursor->diffInMinutes($workEnd);
            $consume = min($remainingMinutes, $availableMinutes);
            $cursor = $cursor->addMinutes($consume);
            $remainingMinutes -= $consume;
        }

        return $cursor;
    }

    protected function mockOutline(array $payload): array
    {
        $tasks = [];
        $taskIndex = 1;
        $requirements = collect($payload['functional_requirements'] ?? [])
            ->merge($payload['non_functional_requirements'] ?? []);

        if ($requirements->isEmpty()) {
            $requirements = collect([
                [
                    'id' => null,
                    'title' => $payload['project']['name'].' baseline',
                    'description' => $payload['project']['description'] ?: 'Project baseline implementation.',
                    'priority' => 'High',
                ],
            ]);
        }

        foreach ($requirements->take(3) as $req) {
            $component = Str::headline(Str::limit($req['title'] ?? 'Application', 40, ''));
            $componentKey = $this->componentKey($component);
            $files = $this->guessFilesForRequirement($req);

            $tasks[] = [
                'temp_id' => 'T'.$taskIndex++,
                'title' => 'Scaffold: '.$component,
                'description' => 'Create the baseline scaffold and contracts for '.$component.'.',
                'priority' => $this->validatePriority(ucfirst($req['priority'] ?? 'High')),
                'component' => $component,
                'component_key' => $componentKey,
                'predicted_files' => $files,
                'is_scaffold' => true,
                'required_role' => $this->suggestRole($req['title'] ?? '', $req['description'] ?? ''),
                'required_skills' => $this->skillsFromFiles($files),
                'estimated_hours' => 4,
                'requirement_type' => isset($req['category']) ? 'non_functional' : 'functional',
                'requirement_id' => $req['id'] ?? null,
                'dependencies' => [],
            ];

            $tasks[] = [
                'temp_id' => 'T'.$taskIndex++,
                'title' => 'Implement: '.Str::limit($req['title'] ?? 'Requirement', 90, ''),
                'description' => $req['description'] ?? 'Implement requirement.',
                'priority' => $this->validatePriority(ucfirst($req['priority'] ?? 'Medium')),
                'component' => $component,
                'component_key' => $componentKey,
                'predicted_files' => $files,
                'is_scaffold' => false,
                'required_role' => $this->suggestRole($req['title'] ?? '', $req['description'] ?? ''),
                'required_skills' => $this->skillsFromFiles($files),
                'estimated_hours' => 6,
                'requirement_type' => isset($req['category']) ? 'non_functional' : 'functional',
                'requirement_id' => $req['id'] ?? null,
                'dependencies' => [],
            ];
        }

        return ['tasks' => $tasks];
    }

    protected function mockScaffoldPrompt(array $task): array
    {
        $files = $task['predicted_files'] ?? [];

        return [
            'scaffold_temp_id' => $task['temp_id'],
            'component' => $task['component'],
            'predicted_files' => $files,
            'interface_contracts' => [
                'routes' => [],
                'controllers' => array_values(array_filter($files, fn ($file) => str_contains($file, 'Controller.php'))),
                'models' => array_values(array_filter($files, fn ($file) => str_contains($file, 'Models/'))),
                'views' => array_values(array_filter($files, fn ($file) => str_ends_with($file, '.blade.php'))),
                'events' => [],
                'tests' => ['tests/Feature/'.Str::studly($task['component']).'Test.php'],
            ],
            'prompt_section' => "### Coding AI Prompt\nComponent: {$task['component']}\nScaffold Temp ID: {$task['temp_id']}\nCreate the baseline file layout, route/API contracts, interfaces, and tests before dependent tasks merge.\nPredicted files: ".implode(', ', $files),
            'brief' => 'Create the baseline scaffold for '.$task['component'].'.',
            'expected_outputs' => ['Baseline files exist', 'Contracts are documented', 'Scaffold tests pass'],
        ];
    }

    protected function mockTaskPrompt(array $task, ?array $scaffold): array
    {
        $scaffoldId = $task['scaffold_temp_id'] ?? ($scaffold['scaffold_temp_id'] ?? 'none');
        $files = $task['predicted_files'] ?? [];

        return [
            'task_temp_id' => $task['temp_id'],
            'scaffold_temp_id' => $scaffoldId,
            'prompt_section' => "### Coding AI Prompt\nComponent: {$task['component']}\nTask Temp ID: {$task['temp_id']}\nScaffold: {$scaffoldId}\nUse the scaffold contracts and do not duplicate baseline structure. Update predicted files: ".implode(', ', $files)."\nAdd or update tests and report expected outputs.",
            'brief' => 'Implement '.$task['title'].' using scaffold '.$scaffoldId.'.',
            'uses_scaffold_contracts' => true,
            'referenced_files' => $files,
            'test_plan' => ['Add feature coverage for '.$task['component']],
            'expected_outputs' => ['Task behavior works', 'Tests pass'],
        ];
    }

    protected function buildPreview(array $outline, array $scaffolds, array $taskPrompts): array
    {
        return [
            'scaffolds' => collect($outline)->where('is_scaffold', true)->map(fn ($task) => [
                'temp_id' => $task['temp_id'],
                'component' => $task['component'],
                'component_key' => $task['component_key'],
                'scaffold_owner_id' => $task['scaffold_owner_id'] ?? null,
                'assigned_to' => $task['assigned_to'] ?? null,
                'predicted_files' => $task['predicted_files'] ?? [],
                'prompt_brief' => $scaffolds[$task['temp_id']]['brief'] ?? $task['title'],
            ])->values()->all(),
            'tasks' => collect($outline)->where('is_scaffold', false)->map(fn ($task) => [
                'temp_id' => $task['temp_id'],
                'title' => $task['title'],
                'component' => $task['component'],
                'assigned_to' => $task['assigned_to'] ?? null,
                'scaffold_temp_id' => $task['scaffold_temp_id'] ?? null,
                'depends_on' => $task['dependencies'] ?? [],
                'predicted_files' => $task['predicted_files'] ?? [],
                'prompt_brief' => $taskPrompts[$task['temp_id']]['brief'] ?? $task['title'],
            ])->values()->all(),
            'conflicts' => collect($outline)
                ->filter(fn ($task) => !empty($task['overlapping_files']))
                ->groupBy('component_key')
                ->map(fn ($items) => [
                    'component' => $items->first()['component'],
                    'overlapping_files' => $items->pluck('overlapping_files')->flatten()->unique()->values()->all(),
                    'scaffold_temp_id' => $items->firstWhere('is_scaffold', true)['temp_id'] ?? $items->first()['scaffold_temp_id'] ?? null,
                ])
                ->values()
                ->all(),
        ];
    }

    protected function fallbackPromptSection(array $item, ?Task $scaffoldTask): string
    {
        $scaffold = $scaffoldTask ? '#'.$scaffoldTask->id : ($item['scaffold_temp_id'] ?? 'self');

        return "### Coding AI Prompt\nComponent: {$item['component']}\nScaffold: {$scaffold}\nPredicted files: ".implode(', ', $item['predicted_files'] ?? [])."\nUse the scaffold contracts, add tests, and keep changes inside this task scope.";
    }

    protected function resolveRequirement(array $item): array
    {
        $type = null;
        $id = null;

        if (!empty($item['requirement_id']) && !empty($item['requirement_type'])) {
            if ($item['requirement_type'] === 'functional' && SrsFunctionalRequirement::find($item['requirement_id'])) {
                $type = SrsFunctionalRequirement::class;
                $id = $item['requirement_id'];
            }

            if ($item['requirement_type'] === 'non_functional' && SrsNonFunctionalRequirement::find($item['requirement_id'])) {
                $type = SrsNonFunctionalRequirement::class;
                $id = $item['requirement_id'];
            }
        }

        return ['type' => $type, 'id' => $id];
    }

    protected function roleIdForName(?string $name): ?int
    {
        if (!$name) {
            return null;
        }

        return Role::where('name', $name)->value('id');
    }

    protected function availableRoleNames(Project $project): array
    {
        $roles = collect(SystemRolesSeeder::getSystemRoleNames());

        if ($project->team_id) {
            $roles = $roles->merge(Role::where('team_id', $project->team_id)->pluck('name'));
        }

        return $roles->unique()->values()->all();
    }

    protected function normalizeStringArray(mixed $values): array
    {
        if (!is_array($values)) {
            return [];
        }

        return collect($values)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function validatePriority(string $priority): string
    {
        $priority = ucfirst(Str::lower(trim($priority)));

        return in_array($priority, ['Low', 'Medium', 'High', 'Critical'], true) ? $priority : 'Medium';
    }

    protected function validateRequirementType(?string $type): ?string
    {
        return in_array($type, ['functional', 'non_functional'], true) ? $type : null;
    }

    protected function componentKey(mixed $value): string
    {
        $key = Str::slug((string) $value);

        return $key !== '' ? $key : 'general';
    }

    protected function shouldUseMockAi(): bool
    {
        return empty($this->apiKey)
            || str_starts_with($this->apiKey, 'dummy')
            || str_starts_with($this->apiKey, 'sk-dummy');
    }

    protected function cacheTtl(): \DateTimeInterface
    {
        return now()->addHours((int) config('tasks.ai.cache_ttl_hours', 24));
    }

    protected function guessFilesForRequirement(array $requirement): array
    {
        $title = Str::slug($requirement['title'] ?? 'feature', '-');

        return [
            'app/Http/Controllers/'.Str::studly($title).'Controller.php',
            'resources/views/'.str_replace('-', '/', $title).'.blade.php',
            'tests/Feature/'.Str::studly($title).'Test.php',
        ];
    }

    protected function skillsFromFiles(array $files): array
    {
        $skills = [];

        foreach ($files as $file) {
            if (str_ends_with($file, '.php')) {
                $skills[] = 'PHP';
                $skills[] = 'Laravel';
            }
            if (str_ends_with($file, '.js')) {
                $skills[] = 'JavaScript';
            }
            if (str_ends_with($file, '.blade.php')) {
                $skills[] = 'Blade';
                $skills[] = 'Laravel';
            }
            if (str_contains($file, 'database/migrations')) {
                $skills[] = 'Database';
            }
        }

        return array_values(array_unique($skills));
    }

    protected function suggestRole(string $title, string $description): string
    {
        $content = Str::lower($title.' '.$description);

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
