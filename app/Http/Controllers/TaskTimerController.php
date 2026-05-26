<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\AI\TaskGenerationService;
use Illuminate\Http\Request;

class TaskTimerController extends Controller
{
    public function show(Request $request, Task $task)
    {
        $this->authorizeTaskAccess($request, $task);

        return response()->json($this->timerPayload($task->fresh()));
    }

    public function start(Request $request, Task $task, TaskGenerationService $service)
    {
        $this->authorizeTaskAccess($request, $task);

        if ($task->timer_state !== 'running') {
            $now = now();
            $estimate = $task->time_estimate_hours ?: $task->estimated_hours;

            $task->forceFill([
                'assigned_at' => $task->assigned_at ?? ($task->assigned_to ? $now : null),
                'time_estimate_hours' => $task->time_estimate_hours ?: $task->estimated_hours,
                'due_at' => $task->due_at ?? ($task->assigned_to ? $service->calculateDueAt($estimate) : null),
                'timer_state' => 'running',
                'timer_started_at' => $task->timer_started_at ?? $now,
                'timer_paused_at' => null,
                'last_timer_tick_at' => $now,
                'timer_started_by' => $request->user()->id,
            ])->save();
        }

        return response()->json($this->timerPayload($task->fresh()));
    }

    public function pause(Request $request, Task $task)
    {
        $this->authorizeTaskAccess($request, $task);

        if ($task->timer_state === 'running') {
            $this->applyElapsedTime($task);

            $task->forceFill([
                'timer_state' => 'paused',
                'timer_paused_at' => now(),
                'last_timer_tick_at' => now(),
            ])->save();
        }

        return response()->json($this->timerPayload($task->fresh()));
    }

    public function resume(Request $request, Task $task)
    {
        $this->authorizeTaskAccess($request, $task);

        if (in_array($task->timer_state, ['idle', 'paused'], true)) {
            $task->forceFill([
                'timer_state' => 'running',
                'timer_paused_at' => null,
                'last_timer_tick_at' => now(),
                'timer_started_by' => $request->user()->id,
            ])->save();
        }

        return response()->json($this->timerPayload($task->fresh()));
    }

    public function stop(Request $request, Task $task)
    {
        $this->authorizeTaskAccess($request, $task);

        if ($task->timer_state === 'running') {
            $this->applyElapsedTime($task);
        }

        $task->forceFill([
            'timer_state' => 'completed',
            'timer_paused_at' => null,
            'last_timer_tick_at' => now(),
        ])->save();

        return response()->json($this->timerPayload($task->fresh()));
    }

    private function applyElapsedTime(Task $task): void
    {
        $elapsed = max(0, $task->last_timer_tick_at?->diffInSeconds(now()) ?? 0);

        if ($elapsed > 0) {
            $task->forceFill([
                'time_spent_seconds' => $task->time_spent_seconds + $elapsed,
                'last_timer_tick_at' => now(),
            ])->save();
        }
    }

    private function timerPayload(Task $task): array
    {
        return [
            'task_id' => $task->id,
            'timer_state' => $task->timer_state,
            'time_spent_seconds' => $task->time_spent_seconds,
            'timer_started_at' => $task->timer_started_at?->toISOString(),
            'timer_paused_at' => $task->timer_paused_at?->toISOString(),
            'last_timer_tick_at' => $task->last_timer_tick_at?->toISOString(),
            'due_at' => $task->due_at?->toISOString(),
            'assigned_at' => $task->assigned_at?->toISOString(),
            'is_overdue' => $task->isOverdue(),
        ];
    }

    private function authorizeTaskAccess(Request $request, Task $task): void
    {
        $user = $request->user();
        $task->loadMissing('project.team.members');
        $isTeamMember = $task->project->team && $task->project->team->members->contains('id', $user->id);

        abort_unless($task->isOwnedBy($user) || $task->isAssignedTo($user) || $isTeamMember, 403);
    }
}
