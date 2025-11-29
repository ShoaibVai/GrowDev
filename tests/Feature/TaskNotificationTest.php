<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskStatusChanged;
use Tests\TestCase;

class TaskNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_assignment_triggers_notification()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->post(route('projects.tasks.store', $project), [
                'title' => 'New Task',
                'priority' => 'Medium',
                'assigned_to' => $assignee->id,
            ])
            ->assertRedirect();

        $task = Task::where('title', 'New Task')->first();
        $this->assertNotNull($task);
        Notification::assertSentTo($assignee, TaskAssigned::class);
    }

    public function test_task_status_change_triggers_notification()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'Do something',
            'status' => 'To Do',
            'priority' => 'Medium',
            'assigned_to' => $assignee->id,
            'created_by' => $owner->id,
        ]);

        $this->actingAs($owner)
            ->put(route('tasks.update', $task), [
                'title' => $task->title,
                'status' => 'In Progress',
            ])
            ->assertRedirect();

        $task->refresh();
        $this->assertEquals('In Progress', $task->status);
        Notification::assertSentTo($assignee, TaskStatusChanged::class);
    }

    public function test_task_assignment_respects_notification_preferences()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        // Set preference to not send immediate assign emails
        $assignee->notificationPreference()->create([
            'email_on_task_assigned' => false,
            'email_on_task_status_change' => true,
            'email_reminders' => true,
            'digest_frequency' => 'daily',
            'digest_time' => '14:00',
        ]);

        $project = Project::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->post(route('projects.tasks.store', $project), [
                'title' => 'Assigned Task',
                'priority' => 'Medium',
                'assigned_to' => $assignee->id,
            ])
            ->assertRedirect();

        $task = Task::where('title', 'Assigned Task')->first();
        $this->assertNotNull($task);

        // Immediate notification should NOT be sent
        Notification::assertNotSentTo($assignee, \App\Notifications\TaskAssigned::class);

        // Instead, a notification event should be created
        $this->assertDatabaseHas('notification_events', [
            'user_id' => $assignee->id,
            'event_type' => 'task_assigned',
            'sent' => false,
        ]);
    }

    public function test_task_status_change_respects_notification_preferences()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        // Set preference to not send immediate status change emails
        $assignee->notificationPreference()->create([
            'email_on_task_assigned' => true,
            'email_on_task_status_change' => false,
            'email_reminders' => true,
            'digest_frequency' => 'daily',
            'digest_time' => '14:00',
        ]);

        $project = Project::factory()->create(['user_id' => $owner->id]);
        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'Do something',
            'status' => 'To Do',
            'priority' => 'Medium',
            'assigned_to' => $assignee->id,
            'created_by' => $owner->id,
        ]);

        $this->actingAs($owner)
            ->put(route('tasks.update', $task), [
                'title' => $task->title,
                'status' => 'In Progress',
            ])
            ->assertRedirect();

        $task->refresh();
        $this->assertEquals('In Progress', $task->status);

        // Immediate notification should NOT be sent
        Notification::assertNotSentTo($assignee, \App\Notifications\TaskStatusChanged::class);

        // Instead, a notification event should be created
        $this->assertDatabaseHas('notification_events', [
            'user_id' => $assignee->id,
            'event_type' => 'task_status_changed',
            'sent' => false,
        ]);
    }

    public function test_assignment_change_respects_notification_preferences()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $oldAssignee = User::factory()->create();
        $newAssignee = User::factory()->create();
        $newAssignee->notificationPreference()->create([
            'email_on_task_assigned' => false,
            'email_on_task_status_change' => true,
            'email_reminders' => true,
            'digest_frequency' => 'daily',
            'digest_time' => '10:00',
        ]);

        $project = Project::factory()->create(['user_id' => $owner->id]);
        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'Reassign Task',
            'status' => 'To Do',
            'priority' => 'Medium',
            'assigned_to' => $oldAssignee->id,
            'created_by' => $owner->id,
        ]);

        $this->actingAs($owner)
            ->put(route('tasks.update', $task), [
                'title' => $task->title,
                'assigned_to' => $newAssignee->id,
                'status' => $task->status,
            ])
            ->assertRedirect();

        // Immediate notification should NOT be sent to the newAssignee
        Notification::assertNotSentTo($newAssignee, TaskAssigned::class);

        // Instead, a notification event should be created
        $this->assertDatabaseHas('notification_events', [
            'user_id' => $newAssignee->id,
            'event_type' => 'task_assigned',
            'sent' => false,
        ]);
    }
}
