<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Projects - Optimized to reduce queries
        $projects = $user->projects()->with('tasks')->latest()->take(6)->get();

        $projectStats = $user->projects()
            ->selectRaw('count(*) as total')
            ->selectRaw("sum(case when status = 'active' then 1 else 0 end) as active")
            ->selectRaw("sum(case when status = 'completed' then 1 else 0 end) as completed")
            ->first();

        $totalProjects = $projectStats->total ?? 0;
        $activeProjects = $projectStats->active ?? 0;
        $completedProjects = $projectStats->completed ?? 0;

        $teams = $user->teams()->get();
        $teamsCount = $teams->count();

        // Tasks assigned to the user - with eager loading
        $tasksAssigned = Task::where('assigned_to', $user->id)
            ->with('project:id,name,status')
            ->latest()
            ->take(6)
            ->get();
        
        $openTasksCount = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['To Do', 'In Progress', 'Review'])
            ->count();

        // Upcoming tasks (next 7 days) - with eager loading
        $upcomingTasks = Task::where('assigned_to', $user->id)
            ->with('project:id,name')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->orderBy('due_date')
            ->take(6)
            ->get();

        // Recent SRS documents only
        $recentSrs = $user->srsDocuments()->latest()->take(6)->get();

        // Pending invitations for the current user
        $pendingInvitations = Invitation::where('email', $user->email)
            ->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->with(['team', 'inviter'])
            ->latest()
            ->get();

        return view('dashboard-modern', compact(
            'projects', 'totalProjects', 'activeProjects', 'completedProjects',
            'teams', 'teamsCount', 'tasksAssigned', 'openTasksCount', 'upcomingTasks',
            'recentSrs', 'pendingInvitations'
        ));
    }
}
