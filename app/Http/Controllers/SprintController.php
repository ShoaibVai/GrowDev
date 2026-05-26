<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SprintController extends Controller
{
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $sprints = $project->sprints()->withCount('tasks')->paginate(15);
        return view('sprints.index', compact('project', 'sprints'));
    }

    public function create(Project $project)
    {
        $this->authorize('update', $project);
        return view('sprints.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $project->sprints()->create(array_merge($validated, ['status' => 'planned']));

        return redirect()->route('sprints.index', $project)
            ->with('success', 'Sprint created successfully.');
    }

    public function show(Project $project, Sprint $sprint)
    {
        $this->authorize('view', $project);

        $sprint->load(['tasks' => fn($q) => $q->with(['assignee:id,name', 'requirement'])->orderBy('sort_order')]);

        $tasksByStatus = $sprint->tasks->groupBy('status');

        $progress = $sprint->progress();

        return view('sprints.show', compact('project', 'sprint', 'tasksByStatus', 'progress'));
    }

    public function edit(Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);
        return view('sprints.edit', compact('project', 'sprint'));
    }

    public function update(Request $request, Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'sometimes|in:planned,active,completed,cancelled',
        ]);

        $sprint->update($validated);

        return redirect()->route('sprints.show', [$project, $sprint])
            ->with('success', 'Sprint updated successfully.');
    }

    public function destroy(Project $project, Sprint $sprint)
    {
        $this->authorize('delete', $project);
        $sprint->delete();
        return redirect()->route('sprints.index', $project)
            ->with('success', 'Sprint deleted.');
    }

    public function start(Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);

        // Deactivate any other active sprints in this project
        $project->sprints()->where('status', 'active')->update(['status' => 'planned']);

        $sprint->update(['status' => 'active']);

        return redirect()->route('sprints.show', [$project, $sprint])
            ->with('success', 'Sprint started!');
    }

    public function complete(Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);

        // Move incomplete tasks back to backlog
        $sprint->tasks()->whereNotIn('status', ['Done'])->update(['sprint_id' => null]);

        $sprint->update(['status' => 'completed']);

        return redirect()->route('sprints.show', [$project, $sprint])
            ->with('success', 'Sprint completed. Incomplete tasks moved back to backlog.');
    }

    public function cancel(Project $project, Sprint $sprint)
    {
        $this->authorize('update', $project);

        $sprint->tasks()->update(['sprint_id' => null]);
        $sprint->update(['status' => 'cancelled']);

        return redirect()->route('sprints.index', $project)
            ->with('success', 'Sprint cancelled. Tasks moved back to backlog.');
    }
}
