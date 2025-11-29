<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function accept(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Invitation has already been processed.');
        }

        $user = Auth::user();

        if (! $user) {
            // Save token in session for post-login acceptance
            $request->session()->put('invitation_token', $token);
            return redirect()->route('login')->with('info', 'Sign in to accept the team invitation.');
        }

        // Prevent mismatched email acceptance
        if ($user->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'This invitation is for a different email address.');
        }

        // Attach user to team if not already a member
        if (! $invitation->team->members->contains($user)) {
            $invitation->team->members()->attach($user->id, ['role' => 'Member']);
        }

        $invitation->update(['status' => 'accepted', 'user_id' => $user->id]);

        return redirect()->route('teams.show', $invitation->team)->with('success', 'You joined the team successfully.');
    }

    public function decline(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Invitation has already been processed.');
        }

        $invitation->update(['status' => 'declined']);

        return redirect()->route('dashboard')->with('info', 'Invitation declined.');
    }
}
