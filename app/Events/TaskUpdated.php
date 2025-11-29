<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task->load('assignee');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('project.' . $this->task->project_id);
    }

    public function broadcastWith()
    {
        return [
            'task' => [
                'id' => $this->task->id,
                'title' => $this->task->title,
                'status' => $this->task->status,
                'assignee' => $this->task->assignee ? ['id' => $this->task->assignee->id, 'name' => $this->task->assignee->name] : null,
            ]
        ];
    }
}
