<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Models\NotificationEvent;
use App\Notifications\TaskReminder;
use App\Models\Task;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send reminders for tasks due within the next 24 hours.';

    public function handle(): int
    {
        $now = Carbon::now();
        $soon = $now->copy()->addDay();
        $startDate = $now->toDateString();
        $endDate = $soon->toDateString();

        $tasks = Task::whereNotNull('due_date')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->whereIn('status', ['To Do', 'In Progress'])
            ->get();

        foreach ($tasks as $task) {
            if ($task->assignee) {
                $pref = $task->assignee->notificationPreference;
                $allowEmail = $pref ? (bool) $pref->email_reminders : true;
                if ($allowEmail) {
                    $task->assignee->notify(new TaskReminder($task));
                } else {
                    if ($pref && $pref->digest_frequency && $pref->digest_frequency !== 'none') {
                        NotificationEvent::create([
                            'user_id' => $task->assignee->id,
                            'event_type' => 'task_reminder',
                            'payload' => ['task_id' => $task->id, 'due_date' => $task->due_date],
                            'sent' => false,
                        ]);
                    }
                }
            }
        }

        $this->info('Reminders sent.');
        return 0;
    }
}
