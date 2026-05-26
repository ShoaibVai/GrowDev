<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskTimerReminder extends Notification
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
            ->subject('Task due soon: '.$this->task->title)
            ->line('This task is approaching its due time.')
            ->line('Task: '.$this->task->title)
            ->line('Due: '.($this->task->due_at?->toDayDateTimeString() ?? 'Not set'))
            ->action('Open Task', route('tasks.show', $this->task));
    }
}
