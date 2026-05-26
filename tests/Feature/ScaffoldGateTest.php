<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScaffoldGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_scaffold_gate_blocks_overlapping_files_before_scaffold_is_merged(): void
    {
        config(['tasks.ci_gate_token' => 'ci-secret']);

        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);

        Task::create([
            'project_id' => $project->id,
            'title' => 'Board scaffold',
            'priority' => 'High',
            'status' => 'In Progress',
            'is_scaffold' => true,
            'component_key' => 'project-board',
            'component' => 'Project board',
            'predicted_files' => ['resources/views/projects/board.blade.php'],
            'scaffold_owner_id' => $owner->id,
        ]);

        $this->withHeader('Authorization', 'Bearer ci-secret')
            ->postJson('/api/ci/scaffold-gate', [
                'project_id' => $project->id,
                'changed_files' => ['resources/views/projects/board.blade.php'],
                'pr_number' => 123,
                'actor' => 'dev-user',
            ])
            ->assertStatus(409)
            ->assertJson(['allowed' => false]);
    }

    public function test_scaffold_gate_allows_completed_scaffold(): void
    {
        config(['tasks.ci_gate_token' => 'ci-secret']);

        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);

        Task::create([
            'project_id' => $project->id,
            'title' => 'Board scaffold',
            'priority' => 'High',
            'status' => 'Done',
            'is_scaffold' => true,
            'component_key' => 'project-board',
            'component' => 'Project board',
            'predicted_files' => ['resources/views/projects/board.blade.php'],
            'scaffold_owner_id' => $owner->id,
        ]);

        $this->withHeader('Authorization', 'Bearer ci-secret')
            ->postJson('/api/ci/scaffold-gate', [
                'project_id' => $project->id,
                'changed_files' => ['resources/views/projects/board.blade.php'],
                'pr_number' => 123,
                'actor' => 'dev-user',
            ])
            ->assertOk()
            ->assertJson(['allowed' => true]);
    }
}
