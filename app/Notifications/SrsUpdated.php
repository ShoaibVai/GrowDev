<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\SrsDocument;

class SrsUpdated extends Notification
{
    use Queueable;

    protected SrsDocument $srs;
    protected array $changedSections;

    public function __construct(SrsDocument $srs, array $changedSections = [])
    {
        $this->srs = $srs;
        $this->changedSections = $changedSections;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('documentation.srs.edit', $this->srs);
        $mail = (new MailMessage)
            ->subject('SRS Updated: ' . $this->srs->title)
            ->line('The SRS document "' . $this->srs->title . '" has been updated.');

        if (!empty($this->changedSections)) {
            $mail->line('Changed sections:');
            foreach ($this->changedSections as $type => $entries) {
                foreach ($entries as $entry) {
                    $label = ($entry['type'] ?? 'changed') . ' ' . ($entry['section'] ?? '');
                    $mail->line("- [{$type}] {$label} : " . ($entry['title'] ?? ($entry['new_title'] ?? '')));
                }
            }
        }

        $mail->action('View SRS', $url)->line('Please review the changes.');
        return $mail;
    }
}
