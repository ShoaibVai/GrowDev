<?php

namespace App\Notifications;

use App\Models\TaskStatusRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification sent to task assignees when their status change request is reviewed.
 * Informs them whether the request was approved or rejected.
 */
class TaskStatusRequestReviewed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param TaskStatusRequest $statusRequest The reviewed status change request
     * @param string $decision Either 'approved' or 'rejected'
     */
    public function __construct(
        public TaskStatusRequest $statusRequest,
        public string $decision
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $task = $this->statusRequest->task;
        $isApproved = $this->decision === 'approved';

        $mail = (new MailMessage)
            ->subject('Task Status Change ' . ucfirst($this->decision) . ': ' . $task->title)
            ->greeting('Hello ' . $notifiable->name . '!');

        if ($isApproved) {
            $mail->line("Your status change request for \"{$task->title}\" has been **approved**.")
                 ->line("The task status has been updated from **{$this->statusRequest->current_status}** to **{$this->statusRequest->requested_status}**.");
        } else {
            $mail->line("Your status change request for \"{$task->title}\" has been **rejected**.")
                 ->line("The task remains in status: **{$this->statusRequest->current_status}**.");
        }

        if ($this->statusRequest->review_notes) {
            $mail->line("**Reviewer Notes:** {$this->statusRequest->review_notes}");
        }

        return $mail->action('View Task', url("/tasks/{$task->id}"));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'task_status_request_reviewed',
            'task_id' => $this->statusRequest->task_id,
            'task_title' => $this->statusRequest->task->title,
            'decision' => $this->decision,
            'current_status' => $this->statusRequest->current_status,
            'requested_status' => $this->statusRequest->requested_status,
            'review_notes' => $this->statusRequest->review_notes,
        ];
    }
}
