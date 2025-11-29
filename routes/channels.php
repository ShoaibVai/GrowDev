<?php

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Broadcast;
use App\Models\Project;

Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    // Only allow users who are members of the project's team
    $project = Project::find($projectId);
    if (!$project || !$project->team_id) return false;
    $team = $project->team;
    if (!$team) return false;
    return $team->members()->where('user_id', $user->id)->exists();
}, ['guards' => ['web']]);
