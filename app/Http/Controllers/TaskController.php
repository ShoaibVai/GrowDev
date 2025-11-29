<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\TaskUpdated;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'requirement_type' => 'nullable|in:functional,non_functional',
            'requirement_id' => 'nullable|integer',
        ]);

        // Map requirement type to model class
        $requirementType = null;
        $requirementId = null;
        $requirement = null;
        
        if ($request->filled('requirement_id') && $request->filled('requirement_type')) {
            if ($request->requirement_type === 'functional') {
                $requirementType = \App\Models\SrsFunctionalRequirement::class;
                $requirement = \App\Models\SrsFunctionalRequirement::find($request->requirement_id);
            } else {
                $requirementType = \App\Models\SrsNonFunctionalRequirement::class;
                $requirement = \App\Models\SrsNonFunctionalRequirement::find($request->requirement_id);
            }
            $requirementId = $request->requirement_id;
        }

        $task = $project->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'To Do',
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
            'due_date' => $request->due_date,
            'requirement_type' => $requirementType,
            'requirement_id' => $requirementId,
        ]);

        // Notify assignee if set
        if ($task->assigned_to) {
            $assignee = User::find($task->assigned_to);
            if ($assignee) {
                $pref = $assignee->notificationPreference;
                $allowEmail = $pref ? (bool) $pref->email_on_task_assigned : true;
                if ($allowEmail) {
                    $assignee->notify(new \App\Notifications\TaskAssigned($task, $requirement));
                } else {
                    // store event for digest
                    NotificationEvent::create([
                        'user_id' => $assignee->id,
                        'event_type' => 'task_assigned',
                        'payload' => [
                            'task_id' => $task->id, 
                            'project_id' => $project->id, 
                            'assigned_by' => auth()->id(),
                            'requirement' => $requirement ? [
                                'type' => $request->requirement_type,
                                'id' => $requirement->id,
                                'title' => $requirement->title,
                                'section' => $requirement->section_number,
                            ] : null,
                        ],
                        'sent' => false,
                    ]);
                }
            }
        }

        // Broadcast task creation for realtime Kanban
        event(new TaskUpdated($task));

        return back()->with('success', 'Task added successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task->project);

        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:To Do,In Progress,Review,Done',
            'priority' => 'nullable|in:Low,Medium,High,Critical',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'requirement_type' => 'nullable|in:functional,non_functional',
            'requirement_id' => 'nullable|integer',
        ]);

        $oldStatus = $task->status;
        $oldAssignee = $task->assigned_to;
        
        // Handle requirement update
        $requirementType = null;
        $requirementId = null;
        $requirement = null;
        
        if ($request->filled('requirement_id') && $request->filled('requirement_type')) {
            if ($request->requirement_type === 'functional') {
                $requirementType = \App\Models\SrsFunctionalRequirement::class;
                $requirement = \App\Models\SrsFunctionalRequirement::find($request->requirement_id);
            } else {
                $requirementType = \App\Models\SrsNonFunctionalRequirement::class;
                $requirement = \App\Models\SrsNonFunctionalRequirement::find($request->requirement_id);
            }
            $requirementId = $request->requirement_id;
        }
        
        $task->update([
            'title' => $request->title,
            'status' => $request->status,
            'priority' => $request->priority ?? $task->priority,
            'assigned_to' => $request->has('assigned_to') ? $request->assigned_to : $task->assigned_to,
            'due_date' => $request->has('due_date') ? $request->due_date : $task->due_date,
            'requirement_type' => $request->has('requirement_type') ? $requirementType : $task->requirement_type,
            'requirement_id' => $request->has('requirement_id') ? $requirementId : $task->requirement_id,
        ]);

        // Send status change notification if status changed
        // Status change notifications
        if ($oldStatus !== $task->status) {
            $assignee = User::find($task->assigned_to);
            if ($assignee) {
                $pref = $assignee->notificationPreference;
                $allowEmail = $pref ? (bool) $pref->email_on_task_status_change : true;
                if ($allowEmail) {
                    $assignee->notify(new \App\Notifications\TaskStatusChanged($task, $oldStatus));
                } else {
                    NotificationEvent::create([
                        'user_id' => $assignee->id,
                        'event_type' => 'task_status_changed',
                        'payload' => ['task_id' => $task->id, 'old_status' => $oldStatus, 'new_status' => $task->status],
                        'sent' => false,
                    ]);
                }
            }
            // Broadcast task update for realtime Kanban
            event(new TaskUpdated($task));
        }

        // Assignment change notifications
        if ($oldAssignee !== $task->assigned_to && $task->assigned_to) {
            $newAssignee = User::find($task->assigned_to);
            if ($newAssignee) {
                $pref = $newAssignee->notificationPreference;
                $allowEmail = $pref ? (bool) $pref->email_on_task_assigned : true;
                if ($allowEmail) {
                    $newAssignee->notify(new \App\Notifications\TaskAssigned($task, $requirement));
                } else {
                    NotificationEvent::create([
                        'user_id' => $newAssignee->id,
                        'event_type' => 'task_assigned',
                        'payload' => [
                            'task_id' => $task->id, 
                            'project_id' => $task->project_id, 
                            'assigned_by' => auth()->id(),
                            'requirement' => $requirement ? [
                                'type' => $request->requirement_type,
                                'id' => $requirement->id,
                                'title' => $requirement->title,
                                'section' => $requirement->section_number,
                            ] : null,
                        ],
                        'sent' => false,
                    ]);
                }
            }
        }

        // Log task activity
        if ($oldStatus !== $task->status) {
            \App\Models\TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'action' => 'status_changed',
                'old_status' => $oldStatus,
                'new_status' => $task->status,
                'notes' => null,
            ]);
        }

        return back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('update', $task->project);
        $task->delete();
        return back()->with('success', 'Task deleted successfully.');
    }
}
