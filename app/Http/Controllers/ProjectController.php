<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->projects()->latest();
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        // Sorting
        switch ($request->get('sort')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                // latest is default - already applied
                break;
        }

        $projects = $query->paginate(12)->withQueryString();
        $teams = Auth::user()->teams()->pluck('name', 'id');
        return view('projects.index', compact('projects', 'teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Auth::user()->teams()->get();
        return view('projects.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,completed,on_hold',
            'type' => 'required|in:solo,team',
            'team_id' => 'nullable|exists:teams,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

    $project = Auth::user()->projects()->create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Request $request)
    {
        $this->authorize('view', $project);

        $tasksQuery = $project->tasks()->latest();
        if ($request->filled('q')) {
            $tasksQuery->where('title', 'like', '%' . $request->q . '%')
                ->orWhere('description', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('status')) {
            $tasksQuery->where('status', $request->status);
        }
        if ($request->filled('assigned_to')) {
            $tasksQuery->where('assigned_to', $request->assigned_to);
        }

    $tasks = $tasksQuery->get();
    $members = $project->team ? $project->team->members()->pluck('name', 'id') : collect([auth()->id() => 'Me']);

    return view('projects.show', compact('project', 'tasks', 'members'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
    $teams = Auth::user()->teams()->get();
    return view('projects.edit', compact('project', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,completed,on_hold',
            'type' => 'required|in:solo,team',
            'team_id' => 'nullable|exists:teams,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

    $project->update($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Project deleted successfully!');
    }
}
