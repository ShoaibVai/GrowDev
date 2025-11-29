<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DigestNotification extends Notification
{
    use Queueable;

    protected $events;

    public function __construct(array $events)
    {
        $this->events = $events;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Your GrowDev Notification Digest')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Here are your recent events:');

        foreach ($this->events as $event) {
            $mail->line('- ' . ($event['message'] ?? $event['event_type']));
        }

        $mail->line('Manage your notification preferences in your profile settings.');
        return $mail;
    }
}
