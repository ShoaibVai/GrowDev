<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification sent when a task's status changes.
 * Informs the assignee about the status transition.
 */
class TaskStatusChanged extends Notification
{
    use Queueable;

    protected Task $task;
    protected string $oldStatus;

    /**
     * Create a new notification instance.
     *
     * @param Task $task The task whose status changed
     * @param string $oldStatus The previous status before the change
     */
    public function __construct(Task $task, string $oldStatus)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('projects.show', $this->task->project);
        
        return (new MailMessage)
            ->subject('Task Status Updated: ' . $this->task->title)
            ->line("The status of the task \"{$this->task->title}\" has changed from {$this->oldStatus} to {$this->task->status}.")
            ->line('Project: ' . $this->task->project->name)
            ->action('Open Project', $url)
            ->line('Thank you for using GrowDev');
    }
}
