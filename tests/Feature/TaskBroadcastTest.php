<?php

namespace Tests\Feature;

use App\Events\TaskUpdated;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskBroadcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_update_triggers_broadcast_event()
    {
        Event::fake([TaskUpdated::class]);
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'To be broadcast',
            'status' => 'To Do',
            'priority' => 'Low',
            'assigned_to' => $assignee->id,
            'created_by' => $owner->id,
        ]);

        $this->actingAs($owner)
            ->put(route('tasks.update', $task), [
                'title' => $task->title,
                'status' => 'In Progress',
            ])
            ->assertRedirect();

        Event::assertDispatched(TaskUpdated::class, function ($e) use ($task) {
            return $e->task->id === $task->id;
        });
    }
}
