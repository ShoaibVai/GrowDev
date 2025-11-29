<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskStatusChanged extends Notification
{
    use Queueable;

    protected Task $task;
    protected string $oldStatus;

    public function __construct(Task $task, string $oldStatus)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('projects.show', $this->task->project);
        return (new MailMessage)
            ->subject('Task Status Updated: ' . $this->task->title)
            ->line('The status of the task "' . $this->task->title . '" has changed from ' . $this->oldStatus . ' to ' . $this->task->status . '.')
            ->line('Project: ' . $this->task->project->name)
            ->action('Open Project', $url)
            ->line('Thank you for using GrowDev');
    }
}
