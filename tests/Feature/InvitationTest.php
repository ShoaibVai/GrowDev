<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TeamInvitation;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_invite_creates_invitation_and_sends_notification()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $owner->id]);
        $team->members()->attach($owner->id, ['role' => 'Owner']);

        $this->actingAs($owner)
            ->post(route('teams.invite', $team), ['email' => $invitee->email])
            ->assertRedirect();

        $this->assertDatabaseHas('invitations', ['email' => $invitee->email, 'team_id' => $team->id]);

        Notification::assertSentTo($invitee, TeamInvitation::class);
    }

    public function test_invite_respects_notification_preferences()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $invitee->notificationPreference()->create([
            'email_on_task_assigned' => true,
            'email_on_task_status_change' => true,
            'email_reminders' => true,
            'email_on_team_invitation' => false,
            'digest_frequency' => 'daily',
            'digest_time' => '10:00'
        ]);
        $team = Team::factory()->create(['owner_id' => $owner->id]);
        $team->members()->attach($owner->id, ['role' => 'Owner']);

        $this->actingAs($owner)
            ->post(route('teams.invite', $team), ['email' => $invitee->email])
            ->assertRedirect();

        $this->assertDatabaseHas('invitations', ['email' => $invitee->email, 'team_id' => $team->id]);

        Notification::assertNotSentTo($invitee, TeamInvitation::class);
        $this->assertDatabaseHas('notification_events', ['user_id' => $invitee->id, 'event_type' => 'team_invitation', 'sent' => false]);
    }

    public function test_accept_invitation_attaches_user_to_team()
    {
        $owner = User::factory()->create();
        $invitee = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $owner->id]);
        $team->members()->attach($owner->id, ['role' => 'Owner']);

        $invitation = Invitation::create([
            'team_id' => $team->id,
            'email' => $invitee->email,
            'token' => 'accept-token-123',
            'status' => 'pending',
            'created_by' => $owner->id,
        ]);

        $this->actingAs($invitee)
            ->get(route('invitations.accept', ['token' => $invitation->token]))
            ->assertRedirect(route('teams.show', $team));

        $this->assertDatabaseHas('team_user', ['team_id' => $team->id, 'user_id' => $invitee->id]);
        $this->assertDatabaseHas('invitations', ['id' => $invitation->id, 'status' => 'accepted']);
    }
}
