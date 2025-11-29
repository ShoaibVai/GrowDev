<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Models\SrsDocument;
use App\Models\SrsFunctionalRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SrsRoleMappingTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_mapping_is_saved_for_functional_requirement()
    {
        $owner = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $owner->id]);
        $team->members()->attach($owner->id, ['role' => 'Owner']);
        $project = Project::factory()->create(['user_id' => $owner->id, 'team_id' => $team->id]);
        $role = Role::create(['name' => 'Dev', 'team_id' => $team->id]);
        $srs = SrsDocument::create(['project_id' => $project->id, 'title' => 'SRS', 'user_id' => $owner->id]);

        $payload = [
            'project_id' => $project->id,
            'title' => 'SRS',
            'functional_requirements' => [
                [
                    'section_number' => '4.1',
                    'requirement_id' => 'FR-401',
                    'title' => 'A requirement',
                    'description' => 'Desc',
                    'priority' => 'medium',
                    'status' => 'draft',
                    'roles' => [$role->id],
                ],
            ],
        ];

        $this->actingAs($owner)
            ->put(route('documentation.srs.update', $srs), $payload)
            ->assertRedirect();

        $req = SrsFunctionalRequirement::where('requirement_id', 'FR-401')->first();
        $this->assertNotNull($req);
        $this->assertDatabaseHas('role_requirement_mappings', ['role_id' => $role->id, 'requirement_id' => $req->id]);
    }
}
