<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class ScaffoldGateController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'changed_files' => 'required|array|min:1',
            'changed_files.*' => 'required|string',
            'pr_number' => 'nullable',
            'actor' => 'nullable|string',
            'repo' => 'nullable|string',
            'sha' => 'nullable|string',
        ]);

        $changedFiles = collect($validated['changed_files'])
            ->map(fn ($file) => str_replace('\\', '/', trim($file)))
            ->filter()
            ->unique()
            ->values();

        $blocking = Task::query()
            ->with('project.team')
            ->where('project_id', $validated['project_id'])
            ->where('is_scaffold', true)
            ->whereNull('scaffold_merged_at')
            ->whereNotIn('status', ['Done', 'completed'])
            ->get()
            ->filter(function (Task $scaffold) use ($changedFiles, $validated) {
                if (!$this->arraysOverlap($scaffold->predicted_files ?? [], $changedFiles->all())) {
                    return false;
                }

                return !$this->hasApprovedException($scaffold, $validated, $changedFiles->all());
            })
            ->values();

        if ($blocking->isNotEmpty()) {
            return response()->json([
                'allowed' => false,
                'blocking_scaffolds' => $blocking->map(fn (Task $task) => [
                    'task_id' => $task->id,
                    'component' => $task->component,
                    'scaffold_owner_id' => $task->scaffold_owner_id,
                    'predicted_files' => $task->predicted_files ?? [],
                ])->values(),
            ], 409);
        }

        return response()->json([
            'allowed' => true,
            'blocking_scaffolds' => [],
        ]);
    }

    private function arraysOverlap(array $scaffoldFiles, array $changedFiles): bool
    {
        $scaffoldFiles = collect($scaffoldFiles)
            ->map(fn ($file) => str_replace('\\', '/', trim((string) $file)))
            ->filter();

        return $scaffoldFiles->contains(fn ($file) => in_array($file, $changedFiles, true));
    }

    private function hasApprovedException(Task $scaffold, array $payload, array $changedFiles): bool
    {
        $exceptions = collect($scaffold->scaffold_exceptions ?? []);
        $allowedApprovers = array_filter([
            $scaffold->scaffold_owner_id,
            $scaffold->project?->user_id,
            $scaffold->project?->team?->owner_id,
        ]);

        return $exceptions->contains(function ($exception) use ($payload, $changedFiles, $allowedApprovers) {
            if (!is_array($exception)) {
                return false;
            }

            if (!empty($exception['expires_at']) && now()->greaterThan(\Carbon\Carbon::parse($exception['expires_at']))) {
                return false;
            }

            if (!empty($exception['pr_number']) && (string) $exception['pr_number'] !== (string) ($payload['pr_number'] ?? '')) {
                return false;
            }

            if (!empty($exception['actor']) && $exception['actor'] !== ($payload['actor'] ?? null)) {
                return false;
            }

            if (!empty($exception['approved_by_user_id']) && !in_array((int) $exception['approved_by_user_id'], $allowedApprovers, true)) {
                return false;
            }

            if (!empty($exception['files']) && !$this->arraysOverlap($exception['files'], $changedFiles)) {
                return false;
            }

            return true;
        });
    }
}
