<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/', 'GET');
$kernel->handle($request);

use App\Models\User;
use App\Models\Team;

$teams = Team::with('members')->get();
foreach ($teams as $team) {
    echo "Team: {$team->id} - {$team->name} (Owner: {$team->owner_id})\n";
    foreach ($team->members as $member) {
        echo " - Member: {$member->id} - {$member->name} (pivot role: {$member->pivot->role})\n";
    }
}

if ($teams->isEmpty()) {
    echo "No teams found\n";
}
