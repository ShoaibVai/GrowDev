<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileNotificationPreferencesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_notification_preferences(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Notify Tester',
            'email' => 'notify@example.com',
            'email_on_task_assigned' => 0,
            'email_on_task_status_change' => 1,
            'email_reminders' => 0,
            'digest_frequency' => 'daily',
            'digest_time' => '13:30',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect('/profile');

        $user->refresh();

        $this->assertNotNull($user->notificationPreference);
        $this->assertFalse($user->notificationPreference->email_on_task_assigned);
        $this->assertTrue($user->notificationPreference->email_on_task_status_change);
        $this->assertFalse($user->notificationPreference->email_reminders);
        $this->assertSame('daily', $user->notificationPreference->digest_frequency);
        $this->assertSame('13:30', $user->notificationPreference->digest_time);
    }
}
// end of file
