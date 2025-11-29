<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskStatusRequest;
use App\Models\User;
use App\Models\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\TaskUpdated;

class TaskController extends Controller
{
    /**
     * Display the task detail page.
     * Accessible by project owner and task assignee.
     */
    public function show(Task $task)
    {
        $user = Auth::user();
        
        // Check if user is owner or assignee
        if (!$task->isOwnedBy($user) && !$task->isAssignedTo($user)) {
            abort(403, 'You do not have access to this task.');
        }

        $task->load([
            'project.user',
            'assignee',
            'creator',
            'requirement',
            'pendingStatusRequest.requester',
            'statusRequests' => fn($q) => $q->latest()->limit(10),
        ]);

        // Get the SRS document for this project
        $srsDocument = $task->project->srsDocuments()->with([
            'functionalRequirements',
            'nonFunctionalRequirements',
        ])->first();

        // Check if current user is the project owner
        $isOwner = $task->isOwnedBy($user);
        $isAssignee = $task->isAssignedTo($user);

        // Get pending status requests for the owner to review
        $pendingRequests = [];
        if ($isOwner) {
            $pendingRequests = TaskStatusRequest::whereHas('task', function($q) use ($task) {
                $q->where('project_id', $task->project_id);
            })->where('approval_status', 'pending')
            ->with(['task', 'requester'])
            ->latest()
            ->get();
        }

        return view('tasks.show', compact(
            'task',
            'srsDocument',
            'isOwner',
            'isAssignee',
            'pendingRequests'
        ));
    }

    /**
     * Request a status change (for assignees).
     */
    public function requestStatusChange(Request $request, Task $task)
    {
        $user = Auth::user();

        // Only assignees can request status changes
        if (!$task->isAssignedTo($user)) {
            abort(403, 'Only the task assignee can request status changes.');
        }

        // Check if there's already a pending request
        if ($task->pendingStatusRequest) {
            return back()->with('error', 'There is already a pending status change request for this task.');
        }

        $request->validate([
            'requested_status' => 'required|in:To Do,In Progress,Review,Done',
            'notes' => 'nullable|string|max:1000',
        ]);

        // If requesting same status, reject
        if ($task->status === $request->requested_status) {
            return back()->with('error', 'Task is already in the requested status.');
        }

        $statusRequest = TaskStatusRequest::create([
            'task_id' => $task->id,
            'requested_by' => $user->id,
            'current_status' => $task->status,
            'requested_status' => $request->requested_status,
            'notes' => $request->notes,
        ]);

        // Notify project owner
        $owner = $task->project->user;
        if ($owner) {
            $owner->notify(new \App\Notifications\TaskStatusChangeRequested($statusRequest));
        }

        return back()->with('success', 'Status change request submitted. Waiting for owner approval.');
    }

    /**
     * Approve or reject a status change request (for project owners).
     */
    public function reviewStatusRequest(Request $request, TaskStatusRequest $statusRequest)
    {
        $user = Auth::user();
        $task = $statusRequest->task;

        // Only project owner can review
        if (!$task->isOwnedBy($user)) {
            abort(403, 'Only the project owner can review status change requests.');
        }

        // Check if already reviewed
        if (!$statusRequest->isPending()) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $statusRequest->update([
            'approval_status' => $request->action === 'approve' ? 'approved' : 'rejected',
            'reviewed_by' => $user->id,
            'review_notes' => $request->review_notes,
            'reviewed_at' => now(),
        ]);

        if ($request->action === 'approve') {
            $oldStatus = $task->status;
            $task->update(['status' => $statusRequest->requested_status]);

            // Log activity
            \App\Models\TaskActivity::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'action' => 'status_changed',
                'old_status' => $oldStatus,
                'new_status' => $task->status,
                'notes' => 'Approved status change request',
            ]);

            // Broadcast update
            event(new TaskUpdated($task));

            // Notify assignee of approval
            if ($task->assignee) {
                $task->assignee->notify(new \App\Notifications\TaskStatusRequestReviewed($statusRequest, 'approved'));
            }

            return back()->with('success', 'Status change approved. Task status updated.');
        } else {
            // Notify assignee of rejection
            if ($task->assignee) {
                $task->assignee->notify(new \App\Notifications\TaskStatusRequestReviewed($statusRequest, 'rejected'));
            }

            return back()->with('success', 'Status change request rejected.');
        }
    }

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

    /**
     * Display all tasks assigned to the current user.
     */
    public function myTasks(Request $request)
    {
        $user = Auth::user();
        
        $query = Task::where('assigned_to', $user->id)
            ->with(['project', 'requirement', 'pendingStatusRequest']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Sort
        $sort = $request->get('sort', 'due_date');
        $direction = $request->get('direction', 'asc');
        
        if ($sort === 'due_date') {
            $query->orderByRaw('due_date IS NULL, due_date ' . $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        $tasks = $query->paginate(15);

        // Get status counts for filters
        $statusCounts = Task::where('assigned_to', $user->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('tasks.my-tasks', compact('tasks', 'statusCounts'));
    }
}
