<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\SrsDocument;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SrsNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_srs_creation_respects_notification_preferences()
    {
        Notification::fake();

        $owner = User::factory()->create();
        $member = User::factory()->create();
        $member->notificationPreference()->create([
            'email_on_srs_update' => false,
            'digest_frequency' => 'daily',
            'digest_time' => '10:00',
        ]);

        $team = Team::factory()->create(['owner_id' => $owner->id]);
        $team->members()->attach($owner->id, ['role' => 'Owner']);
        $team->members()->attach($member->id, ['role' => 'Developer']);

        $project = Project::factory()->create(['user_id' => $owner->id, 'team_id' => $team->id]);

        $this->actingAs($owner)
            ->post(route('documentation.srs.store'), [
                'project_id' => $project->id,
                'title' => 'New SRS',
            ])
            ->assertRedirect();

        // No immediate email
        Notification::assertNotSentTo($member, \App\Notifications\SrsUpdated::class);
        // Event queued
        $this->assertDatabaseHas('notification_events', ['user_id' => $member->id, 'event_type' => 'srs_updated', 'sent' => false]);
    }
}
