<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Task $task)
    {
        $task->loadMissing('project.team.members');
        $user = Auth::user();
        $isTeamMember = $task->project->team && $task->project->team->members->contains('id', $user->id);
        if (!$task->isOwnedBy($user) && !$task->isAssignedTo($user) && !$isTeamMember) {
            abort(403);
        }

        $comments = $task->comments()->with('user:id,name')->latest()->paginate(20);

        return response()->json($comments);
    }

    public function store(Request $request, Task $task)
    {
        $task->loadMissing('project.team.members');
        $user = Auth::user();
        $isTeamMember = $task->project->team && $task->project->team->members->contains('id', $user->id);
        if (!$task->isOwnedBy($user) && !$task->isAssignedTo($user) && !$isTeamMember) {
            abort(403);
        }

        $request->validate(['body' => 'required|string|max:10000']);

        $comment = $task->comments()->create([
            'user_id' => $user->id,
            'body' => $request->body,
        ]);

        $comment->load('user:id,name');

        return response()->json($comment, 201);
    }

    public function destroy(Task $task, Comment $comment)
    {
        $user = Auth::user();
        if ($comment->user_id !== $user->id) {
            abort(403, 'You can only delete your own comments.');
        }

        // Allow deletion within 5 minutes
        if ($comment->created_at->diffInMinutes(now()) > 5) {
            return back()->with('error', 'Comments can only be deleted within 5 minutes of posting.');
        }

        $comment->delete();

        return request()->expectsJson()
            ? response()->json(['message' => 'Comment deleted.'])
            : back()->with('success', 'Comment deleted.');
    }
}
