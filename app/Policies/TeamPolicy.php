<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the team.
     */
    public function view(User $user, Team $team): bool
    {
        // Owner can view
        if ($team->owner_id === $user->id) {
            return true;
        }
        // Team members can view
        return $team->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Team $team): bool
    {
        // Allow owners and admins
        if ($team->owner_id === $user->id) return true;
        $member = $team->members()->where('user_id', $user->id)->first();
        if ($member && ($member->pivot->role === 'Admin' || $member->pivot->role === 'Owner')) {
            return true;
        }
        return false;
    }
}
