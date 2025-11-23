<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $projectsQuery = $user->projects()->latest();
        $projects = $projectsQuery->take(6)->get();

        $totalProjects = $user->projects()->count();
        $activeProjects = $user->projects()->where('status', 'active')->count();
        $completedProjects = $user->projects()->where('status', 'completed')->count();

        $teams = $user->teams()->get();
        $teamsCount = $teams->count();

        // Tasks assigned to the user
        $tasksAssignedQuery = Task::where('assigned_to', $user->id)->latest();
        $tasksAssigned = $tasksAssignedQuery->take(6)->get();
        $openTasksCount = $tasksAssignedQuery->whereIn('status', ['To Do', 'In Progress', 'Review'])->count();

        // Upcoming tasks (next 7 days)
        $upcomingTasks = Task::where('assigned_to', $user->id)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->orderBy('due_date')
            ->take(6)
            ->get();

        // Recent Documents
        $recentSrs = $user->srsDocuments()->latest()->take(6)->get();
        $recentSdd = $user->sddDocuments()->latest()->take(6)->get();

        return view('dashboard', compact(
            'projects', 'totalProjects', 'activeProjects', 'completedProjects',
            'teams', 'teamsCount', 'tasksAssigned', 'openTasksCount', 'upcomingTasks',
            'recentSrs', 'recentSdd'
        ));
    }
}
