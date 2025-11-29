<?php

namespace Tests\Feature;

use App\Console\Commands\SendTaskReminders;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Artisan;
use App\Notifications\TaskReminder;
use Tests\TestCase;
use Carbon\Carbon;

class SendTaskRemindersTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_task_reminders_for_upcoming_tasks()
    {
        Notification::fake();
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'Urgent Task',
            'status' => 'To Do',
            'priority' => 'High',
            'assigned_to' => $assignee->id,
            'created_by' => $owner->id,
            'due_date' => Carbon::now()->addHours(12)->toDateString(),
        ]);

        Artisan::call('tasks:send-reminders');

        Notification::assertSentTo($assignee, TaskReminder::class);
    }

    public function test_reminder_respects_notification_preferences()
    {
        Notification::fake();
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $assignee->notificationPreference()->create([
            'email_on_task_assigned' => true,
            'email_on_task_status_change' => true,
            'email_reminders' => false,
            'digest_frequency' => 'daily',
            'digest_time' => '12:00',
        ]);
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'Urgent Task',
            'status' => 'To Do',
            'priority' => 'High',
            'assigned_to' => $assignee->id,
            'created_by' => $owner->id,
            'due_date' => Carbon::now()->addHours(12)->toDateString(),
        ]);

        Artisan::call('tasks:send-reminders');

        Notification::assertNotSentTo($assignee, TaskReminder::class);
        $this->assertDatabaseHas('notification_events', ['user_id' => $assignee->id, 'event_type' => 'task_reminder', 'sent' => false]);
    }
}
