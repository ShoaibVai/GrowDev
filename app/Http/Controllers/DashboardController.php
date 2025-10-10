<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\SupabaseServiceEnhanced;

class DashboardController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get user's projects and stats
        $projects = $this->supabase->getUserProjects($user->id);
        $stats = $this->calculateUserStats($projects);

        return Inertia::render('Dashboard', [
            'projects' => $projects,
            'stats' => $stats
        ]);
    }

    private function calculateUserStats($projects)
    {
        $totalProjects = count($projects);
        $activeProjects = count(array_filter($projects, function ($project) {
            return in_array($project['stage'], ['Planning', 'Design', 'Development', 'Testing']);
        }));

        // Mock data for now - these would come from actual queries
        $pendingTasks = 12;
        $teamMembers = 8;

        return [
            'totalProjects' => $totalProjects,
            'activeProjects' => $activeProjects,
            'pendingTasks' => $pendingTasks,
            'teamMembers' => $teamMembers
        ];
    }
}