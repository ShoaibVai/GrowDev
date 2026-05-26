<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdueEscalated extends Notification
{
    use Queueable;

    public function __construct(public Task $task)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Overdue task escalated: '.$this->task->title)
            ->line('A task has passed its due time and needs attention.')
            ->line('Task: '.$this->task->title)
            ->line('Component: '.($this->task->component ?? 'Unassigned'))
            ->line('Due: '.($this->task->due_at?->toDayDateTimeString() ?? 'Not set'))
            ->action('Open Task', route('tasks.show', $this->task));
    }
}
