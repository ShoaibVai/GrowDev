<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\SupabaseService;

class ProjectController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $projects = $this->supabase->getUserProjects($user->id);

        return Inertia::render('Projects/Index', [
            'projects' => $projects
        ]);
    }

    public function create()
    {
        return Inertia::render('Projects/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'stage' => 'required|in:Idea,Planning,Design,Development,Testing,Deployment',
            'type' => 'required|in:solo,team',
            'tech_stack' => 'array',
            'deadline' => 'nullable|date'
        ]);

        $projectData = array_merge($validated, [
            'owner_id' => $request->user()->id,
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString()
        ]);

        $project = $this->supabase->createProject($projectData);

        return redirect()->route('projects.show', $project['id'])
                        ->with('success', 'Project created successfully!');
    }

    public function show(Request $request, $id)
    {
        $project = $this->supabase->getProject($id);
        $messages = $this->supabase->getProjectMessages($id);
        
        // Check if user has access to this project
        $user = $request->user();
        if ($project['owner_id'] !== $user->id && 
            !$this->supabase->isProjectMember($id, $user->id)) {
            abort(403);
        }

        return Inertia::render('Projects/Show', [
            'project' => $project,
            'messages' => $messages
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'stage' => 'sometimes|in:Idea,Planning,Design,Development,Testing,Deployment',
            'tech_stack' => 'sometimes|array',
            'deadline' => 'sometimes|nullable|date'
        ]);

        $validated['updated_at'] = now()->toISOString();
        
        $project = $this->supabase->updateProject($id, $validated);

        return back()->with('success', 'Project updated successfully!');
    }

    public function destroy($id)
    {
        $this->supabase->deleteProject($id);
        
        return redirect()->route('projects.index')
                        ->with('success', 'Project deleted successfully!');
    }

    public function addMember(Request $request, $id)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:Developer,UI/UX,Tester,Project Manager'
        ]);

        // Find user by email
        $user = $this->supabase->getUserByEmail($validated['email']);
        
        if (!$user) {
            return back()->withErrors(['email' => 'User not found with this email.']);
        }

        $this->supabase->addProjectMember($id, $user['id'], $validated['role']);

        return back()->with('success', 'Team member added successfully!');
    }
}