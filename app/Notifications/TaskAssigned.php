<?php

namespace App\Notifications;

use App\Models\SrsFunctionalRequirement;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification sent when a task is assigned to a user.
 * Includes details about the task and any linked SRS requirement.
 */
class TaskAssigned extends Notification
{
    use Queueable;

    protected Task $task;
    protected $requirement;

    /**
     * Create a new notification instance.
     *
     * @param Task $task The task that was assigned
     * @param mixed $requirement Optional linked SRS requirement (FR or NFR)
     */
    public function __construct(Task $task, $requirement = null)
    {
        $this->task = $task;
        $this->requirement = $requirement;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('projects.show', $this->task->project);
        $mail = (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->line('A task has been assigned to you: **' . $this->task->title . '**')
            ->line('Project: ' . $this->task->project->name);

        // Include requirement information if the task is linked to an SRS requirement
        if ($this->requirement) {
            $reqType = $this->requirement instanceof SrsFunctionalRequirement
                ? 'Functional Requirement' 
                : 'Non-Functional Requirement';
            $mail->line('**Linked Requirement:**')
                ->line("- Type: {$reqType}")
                ->line("- Section: {$this->requirement->section_number}")
                ->line("- Title: {$this->requirement->title}");
        }

        return $mail
            ->action('Open Project', $url)
            ->line('Thank you for using GrowDev');
    }
}
