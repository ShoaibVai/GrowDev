<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        $project->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'To Do',
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
            'due_date' => $request->due_date,
        ]);

        return back()->with('success', 'Task added successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task->project);

        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:To Do,In Progress,Review,Done',
        ]);

        $task->update($request->only(['title','status','priority','assigned_to','due_date']));

        return back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('update', $task->project);
        $task->delete();
        return back()->with('success', 'Task deleted successfully.');
    }
}
