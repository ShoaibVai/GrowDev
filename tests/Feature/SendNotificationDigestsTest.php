<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\NotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DigestNotification;
use Tests\TestCase;
use Carbon\Carbon;

class SendNotificationDigestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_digests_and_marks_events_sent()
    {
        Notification::fake();

        $user = User::factory()->create();
        $user->notificationPreference()->create([
            'email_on_task_assigned' => false,
            'email_on_task_status_change' => false,
            'email_reminders' => false,
            'digest_frequency' => 'daily',
            'digest_time' => null,
        ]);

        // create unsent events
        NotificationEvent::create(['user_id' => $user->id, 'event_type' => 'task_assigned', 'payload' => ['task_id' => 1], 'sent' => false]);
        NotificationEvent::create(['user_id' => $user->id, 'event_type' => 'task_status_changed', 'payload' => ['task_id' => 2], 'sent' => false]);

        // run command
        $this->artisan('notifications:send-digests')->assertExitCode(0);

        Notification::assertSentTo($user, DigestNotification::class);

        $this->assertDatabaseHas('notification_events', ['user_id' => $user->id, 'event_type' => 'task_assigned', 'sent' => true]);
        $this->assertDatabaseHas('notification_events', ['user_id' => $user->id, 'event_type' => 'task_status_changed', 'sent' => true]);
    }

    public function test_daily_digest_respects_digest_time()
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::create(2025, 11, 29, 13, 30, 0));

        $user = User::factory()->create();
        $user->notificationPreference()->create([
            'email_on_task_assigned' => false,
            'email_on_task_status_change' => false,
            'email_reminders' => false,
            'digest_frequency' => 'daily',
            'digest_time' => '13:30',
        ]);

        NotificationEvent::create(['user_id' => $user->id, 'event_type' => 'task_assigned', 'payload' => ['task_id' => 1], 'sent' => false]);

        // At 13:30, digest should send
        $this->artisan('notifications:send-digests')->assertExitCode(0);
        Notification::assertSentTo($user, DigestNotification::class);

        // Reset events for next check
        NotificationEvent::where('user_id', $user->id)->update(['sent' => false]);
        $user->refresh();
        $user->notificationPreference->last_digest_sent_at = Carbon::now();
        $user->notificationPreference->save();

        // Now set time to before digest_time
        Carbon::setTestNow(Carbon::create(2025, 11, 29, 13, 29, 0));
        Notification::fake();
        NotificationEvent::create(['user_id' => $user->id, 'event_type' => 'task_assigned', 'payload' => ['task_id' => 2], 'sent' => false]);

        // Should not send because it's before digest_time and already sent today
        $this->artisan('notifications:send-digests')->assertExitCode(0);
        Notification::assertNotSentTo($user, DigestNotification::class);
    }

    public function test_weekly_digest_respects_last_sent()
    {
        Notification::fake();
        Carbon::setTestNow(Carbon::create(2025, 11, 29, 9, 0, 0));

        $user = User::factory()->create();
        $pref = $user->notificationPreference()->create([
            'email_on_task_assigned' => false,
            'email_on_task_status_change' => false,
            'email_reminders' => false,
            'digest_frequency' => 'weekly',
            'digest_time' => '08:00',
        ]);

        // Last sent 8 days ago -> should send
        $pref->last_digest_sent_at = Carbon::now()->subDays(8);
        $pref->save();
        NotificationEvent::create(['user_id' => $user->id, 'event_type' => 'task_assigned', 'payload' => ['task_id' => 3], 'sent' => false]);
        $this->artisan('notifications:send-digests')->assertExitCode(0);
        Notification::assertSentTo($user, DigestNotification::class);

        // Now set last sent 3 days ago -> should NOT send
        Notification::fake();
        $pref->last_digest_sent_at = Carbon::now()->subDays(3);
        $pref->save();
        NotificationEvent::create(['user_id' => $user->id, 'event_type' => 'task_assigned', 'payload' => ['task_id' => 4], 'sent' => false]);
        $this->artisan('notifications:send-digests')->assertExitCode(0);
        Notification::assertNotSentTo($user, DigestNotification::class);
    }

    public function test_daily_digest_with_user_timezone_sends_at_user_time()
    {
        Notification::fake();
        // Server time in UTC; user's timezone is America/Los_Angeles (UTC-8)
        Carbon::setTestNow(Carbon::create(2025, 11, 29, 8, 15, 0, 'UTC')); // 00:15 PST

        $user = User::factory()->create();
        $user->notificationPreference()->create([
            'email_on_task_assigned' => false,
            'email_on_task_status_change' => false,
            'email_reminders' => false,
            'digest_frequency' => 'daily',
            'digest_time' => '00:15',
            'timezone' => 'America/Los_Angeles',
        ]);

        NotificationEvent::create(['user_id' => $user->id, 'event_type' => 'task_reminder', 'payload' => ['task_id' => 12], 'sent' => false]);

        $this->artisan('notifications:send-digests')->assertExitCode(0);
        Notification::assertSentTo($user, DigestNotification::class);
    }

    public function test_weekly_digest_respects_day_in_user_timezone()
    {
        Notification::fake();
        // In UTC it's Friday, but in Asia/Tokyo it's Saturday
        Carbon::setTestNow(Carbon::create(2025, 11, 28, 15, 0, 0, 'UTC')); // 00:00 next day in JST

        $user = User::factory()->create();
        $user->notificationPreference()->create([
            'email_on_task_assigned' => false,
            'email_on_task_status_change' => false,
            'email_reminders' => false,
            'digest_frequency' => 'weekly',
            'digest_time' => '00:00',
            'timezone' => 'Asia/Tokyo',
            'digest_day' => 'sat',
        ]);

        NotificationEvent::create(['user_id' => $user->id, 'event_type' => 'task_assigned', 'payload' => ['task_id' => 13], 'sent' => false]);
        $this->artisan('notifications:send-digests')->assertExitCode(0);
        Notification::assertSentTo($user, DigestNotification::class);
    }
}
