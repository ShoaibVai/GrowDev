<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends Notification
{
    use Queueable;

    protected Task $task;
    protected $requirement;

    public function __construct(Task $task, $requirement = null)
    {
        $this->task = $task;
        $this->requirement = $requirement;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('projects.show', $this->task->project);
        $mail = (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->line('A task has been assigned to you: **' . $this->task->title . '**')
            ->line('Project: ' . $this->task->project->name);

        // Include requirement information if linked
        if ($this->requirement) {
            $reqType = $this->requirement instanceof \App\Models\SrsFunctionalRequirement 
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
