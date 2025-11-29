<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Invitation;

class TeamInvitation extends Notification
{
    use Queueable;

    protected Invitation $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $acceptUrl = route('invitations.accept', ['token' => $this->invitation->token]);
        $declineUrl = route('invitations.decline', ['token' => $this->invitation->token]);

        return (new MailMessage)
            ->subject('You’ve been invited to join a team')
            ->greeting('Hello!')
            ->line($this->invitation->inviter->name . ' has invited you to join the team "' . $this->invitation->team->name . '" on GrowDev.')
            ->action('Accept Invitation', $acceptUrl)
            ->line('If you are not interested, you can decline the invitation:')
            ->action('Decline', $declineUrl)
            ->line('If you did not expect this invitation, no action is required.');
    }
}
