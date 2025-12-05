<?php

namespace App\Notifications;

use App\Models\TaskStatusRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification sent to project owners when a task assignee requests a status change.
 * Includes details about the requested change and a link to review it.
 */
class TaskStatusChangeRequested extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param TaskStatusRequest $statusRequest The pending status change request
     */
    public function __construct(
        public TaskStatusRequest $statusRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $task = $this->statusRequest->task;
        $requester = $this->statusRequest->requester;

        return (new MailMessage)
            ->subject('Task Status Change Request: ' . $task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("{$requester->name} has requested a status change for the task \"{$task->title}\".")
            ->line("**Current Status:** {$this->statusRequest->current_status}")
            ->line("**Requested Status:** {$this->statusRequest->requested_status}")
            ->when($this->statusRequest->notes, fn($mail) => $mail->line("**Notes:** {$this->statusRequest->notes}"))
            ->action('Review Request', url("/tasks/{$task->id}"))
            ->line('Please review and approve or reject this request.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'task_status_change_requested',
            'task_id' => $this->statusRequest->task_id,
            'task_title' => $this->statusRequest->task->title,
            'requester_id' => $this->statusRequest->requested_by,
            'requester_name' => $this->statusRequest->requester->name,
            'current_status' => $this->statusRequest->current_status,
            'requested_status' => $this->statusRequest->requested_status,
            'notes' => $this->statusRequest->notes,
        ];
    }
}
