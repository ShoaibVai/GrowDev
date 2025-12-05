<?php

namespace App\Console\Commands;

use App\Models\NotificationEvent;
use App\Models\SrsDocument;
use App\Models\User;
use App\Notifications\DigestNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Command to send consolidated notification digests to users.
 * 
 * Users can configure their notification preferences to receive:
 * - Daily digests: Sent once per day at their preferred time
 * - Weekly digests: Sent once per week on their preferred day
 * 
 * Events are grouped and sent as a single email instead of individual notifications.
 */
class SendNotificationDigests extends Command
{
    protected $signature = 'notifications:send-digests';
    protected $description = 'Send consolidated notification digests to users with digest preferences.';

    public function handle(): int
    {
        // Group all unsent notification events by user
        $events = NotificationEvent::where('sent', false)->get()->groupBy('user_id');
        $now = Carbon::now();

        foreach ($events as $userId => $userEvents) {
            $user = User::find($userId);
            if (!$user) {
                continue;
            }

            $pref = $user->notificationPreference;
            if (!$pref || $pref->digest_frequency === 'none') {
                continue;
            }

            // Convert current time to user's timezone (fallback to server timezone)
            $userNow = $this->getUserLocalTime($now, $pref->timezone);

            // Check if it's time to send the digest based on user preferences
            if (!$this->shouldSendDigest($pref, $userNow)) {
                continue;
            }

            // Build digest payload with human-readable messages
            $payload = $userEvents->map(fn($e) => [
                'event_type' => $e->event_type,
                'payload' => $e->payload,
                'message' => $this->formatEvent($e),
            ])->values()->toArray();

            // Send the consolidated digest notification
            $user->notify(new DigestNotification($payload));

            // Mark all events as sent and update last digest timestamp
            NotificationEvent::whereIn('id', $userEvents->pluck('id')->toArray())
                ->update(['sent' => true]);
            
            $pref->last_digest_sent_at = $now;
            $pref->save();
        }

        $this->info('Digests sent.');
        return 0;
    }

    /**
     * Get the current time in the user's timezone.
     */
    private function getUserLocalTime(Carbon $now, ?string $timezone): Carbon
    {
        if (!$timezone) {
            return $now->copy();
        }

        try {
            return $now->copy()->setTimezone($timezone);
        } catch (\Exception $e) {
            return $now->copy();
        }
    }

    /**
     * Determine if a digest should be sent based on user preferences.
     */
    private function shouldSendDigest($pref, Carbon $userNow): bool
    {
        // Check if it's past the user's preferred digest time
        if ($pref->digest_time) {
            $digestTime = Carbon::parse($pref->digest_time, $pref->timezone ?? config('app.timezone'))
                ->setDate($userNow->year, $userNow->month, $userNow->day)
                ->setTimezone($userNow->getTimezone());
            
            if ($userNow->lt($digestTime)) {
                return false; // Not yet time for today's digest
            }
        }

        // Check frequency-specific rules
        if ($pref->digest_frequency === 'daily') {
            return $this->shouldSendDailyDigest($pref, $userNow);
        }

        if ($pref->digest_frequency === 'weekly') {
            return $this->shouldSendWeeklyDigest($pref, $userNow);
        }

        return false;
    }

    /**
     * Check if daily digest should be sent (once per day).
     */
    private function shouldSendDailyDigest($pref, Carbon $userNow): bool
    {
        if (!$pref->last_digest_sent_at) {
            return true;
        }

        $lastSent = Carbon::parse($pref->last_digest_sent_at)
            ->setTimezone($pref->timezone ?? config('app.timezone'));
        
        return !$lastSent->isSameDay($userNow);
    }

    /**
     * Check if weekly digest should be sent (once per 7 days, on preferred day).
     */
    private function shouldSendWeeklyDigest($pref, Carbon $userNow): bool
    {
        // Check if we've already sent within 7 days
        if ($pref->last_digest_sent_at) {
            $lastSent = Carbon::parse($pref->last_digest_sent_at)
                ->setTimezone($pref->timezone ?? config('app.timezone'));
            
            if ($lastSent->diffInDays($userNow) < 7) {
                return false;
            }
        }

        // Check if today is the user's preferred day
        if ($pref->digest_day) {
            $currentDay = strtolower($userNow->format('D')); // e.g., 'mon', 'tue'
            $preferredDay = strtolower($pref->digest_day);
            
            if ($currentDay !== $preferredDay) {
                return false;
            }
        }

        return true;
    }

    /**
     * Format a notification event into a human-readable message.
     */
    private function formatEvent(NotificationEvent $event): string
    {
        switch ($event->event_type) {
            case 'task_assigned':
                return 'A task was assigned to you.';
            
            case 'task_status_changed':
                return 'A task status in one of your projects changed.';
            
            case 'task_reminder':
                return 'You have a task due soon.';
            
            case 'team_invitation':
                return 'You have a pending team invitation.';
            
            case 'srs_updated':
                if (!empty($event->payload['srs_id'])) {
                    $srs = SrsDocument::find($event->payload['srs_id']);
                    if ($srs) {
                        return 'SRS updated: ' . $srs->title;
                    }
                }
                return 'A SRS document was updated.';
            
            default:
                return ucfirst(str_replace('_', ' ', $event->event_type));
        }
    }
}
