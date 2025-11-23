<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Auth::user()->teams()->get();
        $ownedTeams = Team::where('owner_id', Auth::id())->get();
        return view('teams.index', compact('teams', 'ownedTeams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'owner_id' => Auth::id(),
        ]);

        $team->members()->attach(Auth::id(), ['role' => 'Owner']);

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);
        return view('teams.show', compact('team'));
    }

    public function invite(Request $request, Team $team)
    {
        $this->authorize('update', $team);
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($team->members->contains($user)) {
            return back()->with('error', 'User is already a member.');
        }

        $team->members()->attach($user->id, ['role' => 'Member']);

        return back()->with('success', 'User invited successfully.');
    }

    public function assignRole(Request $request, Team $team, User $user)
    {
        $this->authorize('update', $team);
        $request->validate([
            'role' => 'required|string|max:255',
        ]);

        $team->members()->updateExistingPivot($user->id, ['role' => $request->role]);

        return back()->with('success', 'Role updated successfully.');
    }
}
