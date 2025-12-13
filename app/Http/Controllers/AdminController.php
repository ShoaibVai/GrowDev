<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        
        $users = User::withCount(['projects', 'teams'])->latest()->paginate(20);
        $projects = Project::with(['user', 'team'])->latest()->paginate(20);
        $teams = Team::withCount('members')->latest()->paginate(20);

        return view('admin.index', compact('users', 'projects', 'teams'));
    }

    public function export($type)
    {
        $this->authorizeAdmin();

        $filename = $type . '_export_' . date('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'users') {
                fputcsv($file, ['ID', 'Name', 'Email', 'Joined At', 'Projects Count', 'Teams Count']);
                User::chunk(100, function($users) use ($file) {
                    foreach ($users as $user) {
                        fputcsv($file, [
                            $user->id, 
                            $user->name, 
                            $user->email, 
                            $user->created_at,
                            $user->projects()->count(),
                            $user->teams()->count()
                        ]);
                    }
                });
            } elseif ($type === 'projects') {
                fputcsv($file, ['ID', 'Name', 'Owner', 'Status', 'Created At']);
                Project::with('user')->chunk(100, function($projects) use ($file) {
                    foreach ($projects as $project) {
                        fputcsv($file, [
                            $project->id,
                            $project->name,
                            $project->user->name ?? 'N/A',
                            $project->status,
                            $project->created_at
                        ]);
                    }
                });
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
    }
}
