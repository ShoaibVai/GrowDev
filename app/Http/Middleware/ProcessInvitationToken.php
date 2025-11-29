<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Invitation;

class ProcessInvitationToken
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && $request->session()->has('invitation_token')) {
            $token = $request->session()->pull('invitation_token');
            $invitation = Invitation::where('token', $token)->first();
            if ($invitation && $invitation->status === 'pending' && $invitation->email === auth()->user()->email) {
                // Attach user if not already member
                if (! $invitation->team->members->contains(auth()->user())) {
                    $invitation->team->members()->attach(auth()->id(), ['role' => 'Member']);
                }
                $invitation->update(['status' => 'accepted', 'user_id' => auth()->id()]);
                // flash message
                $request->session()->flash('success', 'You joined the team successfully.');
            }
        }

        return $next($request);
    }
}
