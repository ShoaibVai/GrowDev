<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationEvent;
use App\Models\User;
use App\Notifications\DigestNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendNotificationDigests extends Command
{
    protected $signature = 'notifications:send-digests';
    protected $description = 'Send consolidated notification digests to users with digest preferences.';

    public function handle(): int
    {
        // Group unsent events by user
        $events = NotificationEvent::where('sent', false)->get()->groupBy('user_id');
        $now = Carbon::now();

        foreach ($events as $userId => $userEvents) {
            $user = User::find($userId);
            if (!$user) continue;

            $pref = $user->notificationPreference;
            if (!$pref || $pref->digest_frequency === 'none') continue;

            // Build user's now (timezone-aware), default to server timezone if not set
            try {
                $userNow = $pref->timezone ? $now->copy()->setTimezone($pref->timezone) : $now->copy();
            } catch (\Exception $e) {
                $userNow = $now->copy();
            }

            // If a digest_time is set, ensure user's current time is at/after digest time.
            // Use server timezone for now; future: use user's timezone.
            if ($pref->digest_time) {
                $digestTime = Carbon::parse($pref->digest_time, $pref->timezone ?? config('app.timezone'))->setDate($userNow->year, $userNow->month, $userNow->day)->setTimezone($userNow->getTimezone());
                if ($userNow->lt($digestTime)) {
                    // not yet time to send digest for this user
                    continue;
                }
            }

            // Respect digest frequency: daily (once per day), weekly (once per 7 days)
            if ($pref->digest_frequency === 'daily') {
                if ($pref->last_digest_sent_at && Carbon::parse($pref->last_digest_sent_at)->setTimezone($pref->timezone ?? config('app.timezone'))->isSameDay($userNow)) {
                    continue; // already sent today
                }
            }
            if ($pref->digest_frequency === 'weekly') {
                if ($pref->last_digest_sent_at && Carbon::parse($pref->last_digest_sent_at)->setTimezone($pref->timezone ?? config('app.timezone'))->diffInDays($userNow) < 7) {
                    continue; // already sent within last 7 days
                }
                // If user set digest_day, ensure today is that day
                if ($pref->digest_day) {
                    $weekday = strtolower($userNow->format('D'));
                    $map = [
                        'sun' => 'Sun', 'mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat'
                    ];
                    $weekdayShort = strtolower($userNow->format('D'));
                    // Convert PHP short day (Mon) to our lower-case key eg mon
                    $shortMap = ['sun' => 'Sun', 'mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat'];
                    if ($shortMap[$pref->digest_day] ?? null) {
                        $desired = strtolower($shortMap[$pref->digest_day]);
                        if (strtolower($userNow->format('D')) !== strtolower($desired)) {
                            continue;
                        }
                    }
                }
            }

            // Simplified: send digest for all users with a digest enabled
            $payload = $userEvents->map(fn($e) => ['event_type' => $e->event_type, 'payload' => $e->payload, 'message' => $this->formatEvent($e)])
                ->values()
                ->toArray();

            $user->notify(new DigestNotification($payload));

            // Mark these events as sent
            NotificationEvent::whereIn('id', $userEvents->pluck('id')->toArray())->update(['sent' => true]);
            $pref->last_digest_sent_at = $now;
            $pref->save();
        }

        $this->info('Digests sent.');
        return 0;
    }

    private function formatEvent(NotificationEvent $event): string
    {
        // Provide a small human-friendly message based on event type
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
                    $srs = \App\Models\SrsDocument::find($event->payload['srs_id']);
                    if ($srs) return 'SRS updated: ' . $srs->title;
                }
                return 'A SRS document was updated.';
            default:
                return $event->event_type;
        }
    }
}
