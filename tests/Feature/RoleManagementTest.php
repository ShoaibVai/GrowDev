<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_role_for_team()
    {
        $owner = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $owner->id]);
        $team->members()->attach($owner->id, ['role' => 'Owner']);

        $this->actingAs($owner)
            ->post(route('teams.roles.store', $team), ['name' => 'Developer', 'description' => 'Developer role'])
            ->assertRedirect();

        $this->assertDatabaseHas('roles', ['name' => 'Developer', 'team_id' => $team->id]);
    }

    public function test_can_assign_role_to_member_via_role_id()
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $owner->id]);
        $team->members()->attach($owner->id, ['role' => 'Owner']);
        $team->members()->attach($member->id, ['role' => 'Member']);

        $role = Role::create(['name' => 'QA', 'team_id' => $team->id]);

        $this->actingAs($owner)
            ->patch(route('teams.assignRole', [$team, $member]), ['role_id' => $role->id])
            ->assertRedirect();

        $pivot = \DB::table('team_user')->where('team_id', $team->id)->where('user_id', $member->id)->first();
        $this->assertEquals($role->id, $pivot->role_id);
    }
}
