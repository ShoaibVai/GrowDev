<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_status_change_creates_activity_log()
    {
        $owner = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);
        $task = Task::create([
            'project_id' => $project->id,
            'title' => 'Activity Task',
            'status' => 'To Do',
            'priority' => 'Low',
            'assigned_to' => $assignee->id,
            'created_by' => $owner->id,
        ]);

        $this->actingAs($owner)
            ->put(route('tasks.update', $task), ['title' => $task->title, 'status' => 'In Progress'])
            ->assertRedirect();

        $this->assertDatabaseHas('task_activities', ['task_id' => $task->id, 'old_status' => 'To Do', 'new_status' => 'In Progress']);
    }
}
