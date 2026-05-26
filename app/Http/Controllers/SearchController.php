<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SrsDocument;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|max:100']);
        $q = $request->q;
        $user = Auth::user();

        $projects = Project::where(function ($query) use ($q, $user) {
            $query->where('user_id', $user->id)
                ->orWhereHas('team.members', fn($q2) => $q2->where('user_id', $user->id));
        })->where(function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        })->limit(5)->get(['id', 'name', 'status']);

        $tasks = Task::where(function ($query) use ($q, $user) {
            $query->where('assigned_to', $user->id)
                ->orWhereHas('project', fn($q2) => $q2->where('user_id', $user->id))
                ->orWhereHas('project.team.members', fn($q2) => $q2->where('user_id', $user->id));
        })->where(function ($query) use ($q) {
            $query->where('title', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        })->with('project:id,name')->limit(5)->get(['id', 'title', 'status', 'project_id']);

        $teams = $user->teams()->where('name', 'like', "%{$q}%")->limit(5)->get(['id', 'name']);

        $srsDocs = SrsDocument::where('user_id', $user->id)
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('purpose', 'like', "%{$q}%")
                    ->orWhere('product_scope', 'like', "%{$q}%");
            })->with('project:id,name')->limit(5)->get(['id', 'title', 'project_id']);

        if ($request->expectsJson()) {
            return response()->json(compact('projects', 'tasks', 'teams', 'srsDocs'));
        }

        return view('search.index', compact('q', 'projects', 'tasks', 'teams', 'srsDocs'));
    }
}
