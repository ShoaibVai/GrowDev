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
        $user = Auth::user();
        $teams = $user->teams()->get();
        $ownedTeams = Team::where('owner_id', $user->id)->get();
        
        $pendingInvitations = \App\Models\Invitation::where('status', 'pending')
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('email', $user->email);
            })
            ->with(['team', 'inviter'])
            ->get();

        return view('teams.index', compact('teams', 'ownedTeams', 'pendingInvitations'));
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
        
        // Eager load members to prevent N+1 queries
        $team->load('members');
        
        $pendingInvitations = $team->invitations()
            ->where('status', 'pending')
            ->with('inviter:id,name,email')
            ->get();
            
        // Get team specific roles AND system roles
        $roles = \App\Models\Role::where('team_id', $team->id)
                    ->orWhere('is_system_role', true)
                    ->get();
                    
        return view('teams.show', compact('team', 'pendingInvitations', 'roles'));
    }

    public function invite(Request $request, Team $team)
    {
        $this->authorize('update', $team);
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        // If a user is already a member
        if ($user && $team->members->contains($user)) {
            return back()->with('error', 'User is already a member.');
        }

        // Create a unique token
        $token = \Illuminate\Support\Str::random(40);
        $invitation = \App\Models\Invitation::create([
            'team_id' => $team->id,
            'user_id' => $user ? $user->id : null,
            'email' => $email,
            'token' => $token,
            'status' => 'pending',
            'expires_at' => now()->addDays(14),
            'created_by' => Auth::id(),
        ]);

        // Send notification: if user exists, notify directly; otherwise route-mail
        if ($user) {
            $pref = $user->notificationPreference;
            $allowEmail = $pref ? (bool) $pref->email_on_team_invitation : true;
            if ($allowEmail) {
                $user->notify(new \App\Notifications\TeamInvitation($invitation));
            } else {
                // queue for digest
                \App\Models\NotificationEvent::create([
                    'user_id' => $user->id,
                    'event_type' => 'team_invitation',
                    'payload' => ['invitation_id' => $invitation->id, 'team_id' => $team->id],
                    'sent' => false,
                ]);
            }
        } else {
            \Illuminate\Support\Facades\Notification::route('mail', $email)
                ->notify(new \App\Notifications\TeamInvitation($invitation));
        }

        return back()->with('success', 'Invitation sent successfully.');
    }

    public function cancelInvitation(Request $request, Team $team, \App\Models\Invitation $invitation)
    {
        $this->authorize('update', $team);

        if ($invitation->team_id !== $team->id) {
            return back()->with('error', 'Invitation does not belong to this team.');
        }

        $invitation->update(['status' => 'declined']);
        return back()->with('success', 'Invitation cancelled.');
    }

    public function assignRole(Request $request, Team $team, User $user)
    {
        $this->authorize('update', $team);
        $request->validate([
            'role' => 'nullable|string|max:255',
            'role_id' => 'nullable|integer|exists:roles,id',
        ]);
        $data = [];
        if ($request->filled('role')) $data['role'] = $request->role;
        
        // Handle role_id update (including setting to null)
        if ($request->has('role_id')) {
            $data['role_id'] = $request->role_id ?: null;
        }

        $team->members()->updateExistingPivot($user->id, $data);

        return back()->with('success', 'Role updated successfully.');
    }

    public function removeMember(Request $request, Team $team, User $user)
    {
        $this->authorize('update', $team);

        // Cannot remove the owner
        if ($team->owner_id === $user->id) {
            return back()->with('error', 'Cannot remove the team owner.');
        }

        // Cannot remove yourself (use leave team instead)
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot remove yourself. Use the leave team option instead.');
        }

        $team->members()->detach($user->id);

        return back()->with('success', 'Member removed successfully.');
    }

    /**
     * Delete a team.
     * Only the team owner can delete a team.
     * This will also remove all members and pending invitations.
     */
    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        // Delete all pending invitations
        $team->invitations()->delete();

        // Delete all roles associated with the team
        $team->roles()->delete();

        // Detach all members
        $team->members()->detach();

        // Delete the team
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}
