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
        $query = Auth::user()->projects()
            ->with(['tasks' => fn($q) => $q->select('id', 'project_id', 'status', 'assigned_to')])
            ->latest();
            
        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
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

        // Get SRS documents for this project with requirements - optimized single query
        $srsDocument = $project->srsDocuments()->with([
            'functionalRequirements' => fn($q) => $q->select('id', 'srs_document_id', 'parent_id', 'title', 'section_number', 'priority', 'implementation_status')->orderBy('section_number'),
            'nonFunctionalRequirements' => fn($q) => $q->select('id', 'srs_document_id', 'parent_id', 'title', 'section_number', 'priority', 'implementation_status', 'category')->orderBy('section_number'),
        ])->first();

        $functionalRequirements = $srsDocument ? $srsDocument->functionalRequirements->whereNull('parent_id') : collect();
        $nonFunctionalRequirements = $srsDocument ? $srsDocument->nonFunctionalRequirements->whereNull('parent_id') : collect();

        // Use already loaded data instead of querying again
        $allFunctionalReqs = $srsDocument ? $srsDocument->functionalRequirements : collect();
        $allNonFunctionalReqs = $srsDocument ? $srsDocument->nonFunctionalRequirements : collect();

        return view('projects.show', compact(
            'project', 'tasks', 'members', 'srsDocument',
            'functionalRequirements', 'nonFunctionalRequirements',
            'allFunctionalReqs', 'allNonFunctionalReqs'
        ));
    }

    public function board(Project $project)
    {
        $this->authorize('view', $project);
        $tasks = $project->tasks()
            ->with(['assignee:id,name', 'requirement'])
            ->orderBy('created_at')
            ->get()
            ->groupBy('status');
        return view('projects.board', compact('project', 'tasks'));
    }

    public function membersSummary(Project $project)
    {
        $this->authorize('view', $project);
        $members = $project->team ? $project->team->members : collect([auth()->user()]);
        
        // Optimize with single query using conditional aggregation
        $memberIds = $members->pluck('id');
        $taskCounts = \App\Models\Task::where('project_id', $project->id)
            ->whereIn('assigned_to', $memberIds)
            ->selectRaw('assigned_to, COUNT(*) as total_tasks')
            ->selectRaw("SUM(CASE WHEN status IN ('To Do', 'In Progress', 'Review') THEN 1 ELSE 0 END) as active_tasks")
            ->groupBy('assigned_to')
            ->pluck('active_tasks', 'assigned_to')
            ->union(
                \App\Models\Task::where('project_id', $project->id)
                    ->whereIn('assigned_to', $memberIds)
                    ->selectRaw('assigned_to, COUNT(*) as total_tasks')
                    ->groupBy('assigned_to')
                    ->pluck('total_tasks', 'assigned_to')
            );
        
        $summary = $members->map(function ($m) use ($taskCounts) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'email' => $m->email,
                'active_tasks' => $taskCounts[$m->id]['active'] ?? 0,
                'total_tasks' => $taskCounts[$m->id]['total'] ?? 0,
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
