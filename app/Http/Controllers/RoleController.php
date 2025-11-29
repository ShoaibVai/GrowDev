<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index(Team $team)
    {
        $this->authorize('update', $team);
        $roles = $team->roles()->get();
        return view('teams.roles.index', compact('team', 'roles'));
    }

    public function store(Request $request, Team $team)
    {
        $this->authorize('update', $team);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $role = $team->roles()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        return redirect()->route('teams.roles.index', $team)->with('success', 'Role created');
    }

    public function destroy(Team $team, Role $role)
    {
        $this->authorize('update', $team);
        if ($role->team_id !== $team->id) {
            return back()->with('error', 'Role does not belong to this team.');
        }
        $role->delete();
        return back()->with('success', 'Role deleted');
    }
}
