<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembersSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_summary_returns_counts()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $owner->id]);
        $team->members()->attach($owner->id, ['role' => 'Owner']);
        $team->members()->attach($member->id, ['role' => 'Member']);
        $project = Project::factory()->create(['user_id' => $owner->id, 'team_id' => $team->id]);

        $this->actingAs($owner)
            ->get(route('projects.members.summary', $project))
            ->assertStatus(200)
            ->assertJsonStructure(['members' => [['id','name','email','active_tasks','total_tasks']]]);
    }
}
