<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskReminder extends Notification
{
    use Queueable;

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('projects.show', $this->task->project);
        return (new MailMessage)
            ->subject('Upcoming task due: ' . $this->task->title)
            ->line('This task is due soon: ' . $this->task->title)
            ->line('Due date: ' . optional($this->task->due_date)->toDateString())
            ->action('Open Project', $url)
            ->line('Please keep this in mind to meet deadlines.');
    }
}
