<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->projects()->with('tasks')->latest();
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
        $teams = Auth::user()->teams()->pluck('teams.name', 'teams.id');
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
            'team_id' => [
                'nullable',
                'exists:teams,id',
                Rule::requiredIf(fn () => $request->input('type') === 'team'),
            ],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validated['type'] === 'solo') {
            $validated['team_id'] = null;
        }

        $project = Auth::user()->projects()->create(array_merge($validated, [
            'source' => 'auto',
        ]));

        return redirect()
            ->route('documentation.srs.create', ['project_id' => $project->id])
            ->with('success', 'Project created successfully! Let’s document the SRS next.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Request $request)
    {
        $this->authorize('view', $project);

        $tasksQuery = $project->tasks()->with('requirement')->latest();
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
        $members = $project->team
            ? $project->team->members()->pluck('users.name', 'users.id')
            : collect([auth()->id() => 'Me']);

        // Get SRS documents for this project with requirements
        $srsDocument = $project->srsDocuments()->with([
            'functionalRequirements' => fn($q) => $q->whereNull('parent_id')->with('children'),
            'nonFunctionalRequirements' => fn($q) => $q->whereNull('parent_id')->with('children'),
        ])->first();

        $functionalRequirements = $srsDocument ? $srsDocument->functionalRequirements : collect();
        $nonFunctionalRequirements = $srsDocument ? $srsDocument->nonFunctionalRequirements : collect();

        // Flatten requirements for task dropdown (include children)
        $allFunctionalReqs = $srsDocument 
            ? \App\Models\SrsFunctionalRequirement::where('srs_document_id', $srsDocument->id)->orderBy('section_number')->get()
            : collect();
        $allNonFunctionalReqs = $srsDocument 
            ? \App\Models\SrsNonFunctionalRequirement::where('srs_document_id', $srsDocument->id)->orderBy('section_number')->get()
            : collect();

        return view('projects.show', compact(
            'project', 'tasks', 'members', 'srsDocument',
            'functionalRequirements', 'nonFunctionalRequirements',
            'allFunctionalReqs', 'allNonFunctionalReqs'
        ));
    }

    public function board(Project $project)
    {
        $this->authorize('view', $project);
        $tasks = $project->tasks()->orderBy('created_at')->get()->groupBy('status');
        return view('projects.board', compact('project', 'tasks'));
    }

    public function membersSummary(Project $project)
    {
        $this->authorize('view', $project);
        $members = $project->team ? $project->team->members : collect([auth()->user()]);
        $summary = $members->map(function ($m) use ($project) {
            $activeTasks = $project->tasks()->where('assigned_to', $m->id)->whereIn('status', ['To Do', 'In Progress', 'Review'])->count();
            $totalTasks = $project->tasks()->where('assigned_to', $m->id)->count();
            return [
                'id' => $m->id,
                'name' => $m->name,
                'email' => $m->email,
                'active_tasks' => $activeTasks,
                'total_tasks' => $totalTasks,
            ];
        });
        return response()->json(['members' => $summary]);
    }

    /**
     * Update requirement implementation status.
     */
    public function updateRequirementStatus(Request $request, Project $project, string $type, int $requirement)
    {
        $this->authorize('update', $project);

        $request->validate([
            'implementation_status' => 'required|in:listed,work_in_progress,completed,compromised,under_maintenance',
        ]);

        if ($type === 'functional') {
            $req = \App\Models\SrsFunctionalRequirement::findOrFail($requirement);
        } else {
            $req = \App\Models\SrsNonFunctionalRequirement::findOrFail($requirement);
        }

        // Verify the requirement belongs to this project's SRS
        $srs = $project->srsDocuments()->first();
        if (!$srs || $req->srs_document_id !== $srs->id) {
            return back()->with('error', 'Requirement not found for this project.');
        }

        $req->update(['implementation_status' => $request->implementation_status]);

        return back()->with('success', 'Requirement status updated.');
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
            'team_id' => [
                'nullable',
                'exists:teams,id',
                Rule::requiredIf(fn () => $request->input('type') === 'team'),
            ],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validated['type'] === 'solo') {
            $validated['team_id'] = null;
        }

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
