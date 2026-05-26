<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LayeredTaskGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_layered_generation_stores_scaffold_prompt_payload_and_dependencies(): void
    {
        config(['queue.default' => 'sync', 'services.openrouter.api_key' => 'test-key']);

        Http::fakeSequence()
            ->push(['choices' => [['message' => ['content' => json_encode([
                'tasks' => [
                    [
                        'temp_id' => 'T1',
                        'title' => 'Build board baseline',
                        'description' => 'Create shared board structure.',
                        'priority' => 'High',
                        'component' => 'Project board',
                        'predicted_files' => ['resources/views/projects/board.blade.php'],
                        'is_scaffold' => true,
                        'required_role' => 'Full Stack Developer',
                        'required_skills' => ['Laravel'],
                        'estimated_hours' => 6,
                        'dependencies' => [],
                    ],
                    [
                        'temp_id' => 'T2',
                        'title' => 'Add board filters',
                        'description' => 'Use baseline board contracts.',
                        'priority' => 'Medium',
                        'component' => 'Project board',
                        'predicted_files' => ['resources/views/projects/board.blade.php'],
                        'is_scaffold' => false,
                        'required_role' => 'Frontend Developer',
                        'required_skills' => ['JavaScript'],
                        'estimated_hours' => 4,
                        'dependencies' => [],
                    ],
                ],
            ])]]]])
            ->push(['choices' => [['message' => ['content' => json_encode([
                'scaffold_temp_id' => 'T1',
                'component' => 'Project board',
                'predicted_files' => ['resources/views/projects/board.blade.php'],
                'interface_contracts' => ['views' => ['resources/views/projects/board.blade.php']],
                'prompt_section' => '### Coding AI Prompt scaffold T1',
                'brief' => 'Board scaffold prompt',
                'expected_outputs' => ['Board baseline'],
            ])]]]])
            ->push(['choices' => [['message' => ['content' => json_encode([
                'task_temp_id' => 'T2',
                'scaffold_temp_id' => 'T1',
                'prompt_section' => '### Coding AI Prompt depends on scaffold T1',
                'brief' => 'Board filter task prompt',
                'uses_scaffold_contracts' => true,
                'referenced_files' => ['resources/views/projects/board.blade.php'],
                'test_plan' => ['Feature test filters'],
                'expected_outputs' => ['Filters work'],
            ])]]]]);

        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);

        $start = $this->actingAs($owner)
            ->postJson(route('projects.ai-tasks.layered.start', $project), ['mock_ai' => false])
            ->assertOk()
            ->assertJsonStructure(['run_id'])
            ->json();

        $this->actingAs($owner)
            ->postJson(route('projects.ai-tasks.layered.commit', [$project, $start['run_id']]))
            ->assertOk()
            ->assertJson(['success' => true, 'created' => 2]);

        $scaffold = Task::where('project_id', $project->id)->where('is_scaffold', true)->firstOrFail();
        $dependent = Task::where('project_id', $project->id)->where('is_scaffold', false)->firstOrFail();

        $this->assertSame($scaffold->id, $dependent->scaffold_task_id);
        $this->assertTrue($dependent->dependencies()->whereKey($scaffold->id)->exists());
        $this->assertStringContainsString('Coding AI Prompt', $dependent->prompt_section);
        $this->assertArrayHasKey('tasks', $dependent->prompt_payload);
        $this->assertArrayHasKey('scaffolds', $scaffold->prompt_payload);
    }

    public function test_conflict_detection_auto_creates_one_scaffold_for_overlapping_predicted_files(): void
    {
        config(['queue.default' => 'sync', 'services.openrouter.api_key' => 'test-key']);

        Http::fakeSequence()
            ->push(['choices' => [['message' => ['content' => json_encode([
                'tasks' => [
                    [
                        'temp_id' => 'T1',
                        'title' => 'Add board filters',
                        'description' => 'Filter board cards.',
                        'priority' => 'Medium',
                        'component' => 'Project board',
                        'predicted_files' => ['resources/views/projects/board.blade.php'],
                        'is_scaffold' => false,
                        'required_role' => 'Frontend Developer',
                        'estimated_hours' => 4,
                        'dependencies' => [],
                    ],
                    [
                        'temp_id' => 'T2',
                        'title' => 'Add board counters',
                        'description' => 'Count board cards.',
                        'priority' => 'Medium',
                        'component' => 'Project board',
                        'predicted_files' => ['resources/views/projects/board.blade.php'],
                        'is_scaffold' => false,
                        'required_role' => 'Frontend Developer',
                        'estimated_hours' => 3,
                        'dependencies' => [],
                    ],
                ],
            ])]]]])
            ->push(['choices' => [['message' => ['content' => json_encode([
                'scaffold_temp_id' => 'SCF-PROJECT-BOARD',
                'component' => 'Project board',
                'predicted_files' => ['resources/views/projects/board.blade.php'],
                'interface_contracts' => ['views' => ['resources/views/projects/board.blade.php']],
                'prompt_section' => '### Coding AI Prompt auto scaffold',
                'brief' => 'Auto board scaffold prompt',
                'expected_outputs' => ['Board scaffold'],
            ])]]]])
            ->push(['choices' => [['message' => ['content' => json_encode([
                'task_temp_id' => 'T1',
                'scaffold_temp_id' => 'SCF-PROJECT-BOARD',
                'prompt_section' => '### Coding AI Prompt filter task',
                'brief' => 'Filter task prompt',
                'uses_scaffold_contracts' => true,
            ])]]]])
            ->push(['choices' => [['message' => ['content' => json_encode([
                'task_temp_id' => 'T2',
                'scaffold_temp_id' => 'SCF-PROJECT-BOARD',
                'prompt_section' => '### Coding AI Prompt counter task',
                'brief' => 'Counter task prompt',
                'uses_scaffold_contracts' => true,
            ])]]]]);

        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);

        $runId = $this->actingAs($owner)
            ->postJson(route('projects.ai-tasks.layered.start', $project), ['mock_ai' => false])
            ->assertOk()
            ->json('run_id');

        $this->actingAs($owner)->postJson(route('projects.ai-tasks.layered.commit', [$project, $runId]))->assertOk();

        $this->assertSame(1, Task::where('project_id', $project->id)->where('is_scaffold', true)->count());
        $this->assertSame(2, Task::where('project_id', $project->id)->where('is_scaffold', false)->whereNotNull('scaffold_task_id')->count());
    }

    public function test_prompt_payload_redacts_secrets_before_storage(): void
    {
        config(['queue.default' => 'sync', 'services.openrouter.api_key' => 'test-key']);

        Http::fakeSequence()
            ->push(['choices' => [['message' => ['content' => json_encode([
                'tasks' => [
                    [
                        'temp_id' => 'T1',
                        'title' => 'Build secret-safe baseline',
                        'description' => 'Do not store sk-test-secret123456.',
                        'priority' => 'High',
                        'component' => 'Secrets',
                        'predicted_files' => ['app/Services/Secrets.php'],
                        'is_scaffold' => true,
                        'required_role' => 'Backend Developer',
                        'estimated_hours' => 2,
                        'dependencies' => [],
                    ],
                ],
            ])]]]])
            ->push(['choices' => [['message' => ['content' => json_encode([
                'scaffold_temp_id' => 'T1',
                'component' => 'Secrets',
                'predicted_files' => ['app/Services/Secrets.php'],
                'interface_contracts' => ['services' => ['app/Services/Secrets.php']],
                'prompt_section' => 'Never store Bearer abcdefghijklmnop.',
                'brief' => 'Secret-safe prompt',
                'expected_outputs' => ['Safe storage'],
            ])]]]]);

        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);

        $runId = $this->actingAs($owner)
            ->postJson(route('projects.ai-tasks.layered.start', $project), ['mock_ai' => false])
            ->assertOk()
            ->json('run_id');

        $this->actingAs($owner)->postJson(route('projects.ai-tasks.layered.commit', [$project, $runId]))->assertOk();

        $payload = json_encode(Task::firstOrFail()->prompt_payload);

        $this->assertStringNotContainsString('sk-test-secret123456', $payload);
        $this->assertStringNotContainsString('Bearer abcdefghijklmnop', $payload);
        $this->assertStringContainsString('[REDACTED]', $payload);
    }
}
