<?php

namespace App\Console\Commands;

use App\Models\NotificationEvent;
use App\Models\Task;
use App\Notifications\TaskOverdueEscalated;
use App\Notifications\TaskTimerReminder;
use Illuminate\Console\Command;

class TickTaskTimers extends Command
{
    protected $signature = 'task:tick-timers {--dry-run}';

    protected $description = 'Tick running task timers, send due reminders, and escalate overdue tasks.';

    public function handle(): int
    {
        $now = now();
        $dryRun = (bool) $this->option('dry-run');

        Task::runningTimers()
            ->chunkById(100, function ($tasks) use ($now, $dryRun) {
                foreach ($tasks as $task) {
                    $elapsed = max(0, $task->last_timer_tick_at?->diffInSeconds($now) ?? 0);

                    if ($elapsed === 0) {
                        continue;
                    }

                    if (!$dryRun) {
                        $task->forceFill([
                            'time_spent_seconds' => $task->time_spent_seconds + $elapsed,
                            'last_timer_tick_at' => $now,
                        ])->save();
                    }
                }
            });

        Task::query()
            ->whereNotNull('due_at')
            ->whereNotIn('status', ['Done', 'completed', 'cancelled'])
            ->with(['assignee.notificationPreference', 'project.user.notificationPreference', 'project.team.owner.notificationPreference', 'scaffoldOwner.notificationPreference'])
            ->chunkById(100, function ($tasks) use ($now, $dryRun) {
                foreach ($tasks as $task) {
                    $this->sendReminderIfThresholdReached($task, $now, $dryRun);
                    $this->escalateIfOverdue($task, $now, $dryRun);
                }
            });

        $this->info('Task timers ticked.');

        return self::SUCCESS;
    }

    private function sendReminderIfThresholdReached(Task $task, $now, bool $dryRun): void
    {
        if (!$task->assignee || !$task->due_at || $task->due_at->isPast()) {
            return;
        }

        $thresholds = config('tasks.timers.reminder_threshold_hours', [24, 4]);
        $maxThreshold = max($thresholds ?: [24]);

        if ($task->due_at->diffInHours($now) > $maxThreshold) {
            return;
        }

        if ($task->last_reminded_at && $task->last_reminded_at->greaterThan($now->copy()->subHour())) {
            return;
        }

        if ($dryRun) {
            return;
        }

        $pref = $task->assignee->notificationPreference;
        $allowEmail = $pref ? (bool) $pref->email_reminders : true;

        if ($allowEmail) {
            $task->assignee->notify(new TaskTimerReminder($task));
        } else {
            NotificationEvent::create([
                'user_id' => $task->assignee->id,
                'event_type' => 'task_timer_reminder',
                'payload' => [
                    'task_id' => $task->id,
                    'due_at' => $task->due_at,
                ],
                'sent' => false,
            ]);
        }

        $task->forceFill(['last_reminded_at' => $now])->save();
    }

    private function escalateIfOverdue(Task $task, $now, bool $dryRun): void
    {
        if (!$task->due_at || !$task->due_at->isPast() || $task->overdue_escalated_at) {
            return;
        }

        if ($dryRun) {
            return;
        }

        $recipient = $task->is_scaffold
            ? ($task->project->team?->owner ?? $task->project->user)
            : ($task->scaffoldOwner ?? $task->project->team?->owner ?? $task->project->user);

        if ($recipient) {
            $recipient->notify(new TaskOverdueEscalated($task));
        }

        $task->forceFill(['overdue_escalated_at' => $now])->save();
    }
}
