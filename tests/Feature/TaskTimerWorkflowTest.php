<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskOverdueEscalated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TaskTimerWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_sets_assigned_at_due_at_and_time_estimate(): void
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)
            ->post(route('projects.tasks.store', $project), [
                'title' => 'Timed task',
                'priority' => 'Medium',
                'assigned_to' => $assignee->id,
                'estimated_hours' => 4,
            ])
            ->assertRedirect();

        $task = Task::where('title', 'Timed task')->firstOrFail();

        $this->assertNotNull($task->assigned_at);
        $this->assertNotNull($task->due_at);
        $this->assertSame('4.00', $task->time_estimate_hours);
        $this->assertSame('idle', $task->timer_state);
    }

    public function test_timer_start_pause_resume_stop_updates_seconds_and_state(): void
    {
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $assignee->id]);
        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'Timer task',
            'priority' => 'Medium',
            'status' => 'To Do',
            'assigned_to' => $assignee->id,
        ]);

        $this->actingAs($assignee)->postJson("/tasks/{$task->id}/timer/start")->assertOk();
        $this->travel(90)->seconds();
        $this->actingAs($assignee)->postJson("/tasks/{$task->id}/timer/pause")->assertOk();

        $this->assertGreaterThanOrEqual(90, $task->fresh()->time_spent_seconds);
        $this->assertSame('paused', $task->fresh()->timer_state);

        $this->actingAs($assignee)->postJson("/tasks/{$task->id}/timer/resume")->assertOk();
        $this->actingAs($assignee)->postJson("/tasks/{$task->id}/timer/stop")->assertOk();

        $this->assertSame('completed', $task->fresh()->timer_state);
    }

    public function test_tick_command_sends_reminders_and_escalates_overdue_tasks(): void
    {
        Notification::fake();

        $owner = User::factory()->create();
        $scaffoldOwner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $scaffold = Task::create([
            'project_id' => $project->id,
            'title' => 'Board scaffold',
            'priority' => 'High',
            'status' => 'Done',
            'is_scaffold' => true,
            'scaffold_owner_id' => $scaffoldOwner->id,
        ]);

        $dependent = Task::create([
            'project_id' => $project->id,
            'title' => 'Overdue dependent',
            'priority' => 'High',
            'status' => 'In Progress',
            'assigned_to' => $assignee->id,
            'scaffold_task_id' => $scaffold->id,
            'scaffold_owner_id' => $scaffoldOwner->id,
            'due_at' => now()->subHour(),
        ]);

        Artisan::call('task:tick-timers');

        Notification::assertSentTo($scaffoldOwner, TaskOverdueEscalated::class);
        $this->assertNotNull($dependent->fresh()->overdue_escalated_at);
    }
}
