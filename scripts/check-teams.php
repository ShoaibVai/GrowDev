<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
// Boot the kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/', 'GET');
$kernel->handle($request);

use App\Models\User;

$user = User::find(2);
if (!$user) {
    echo "User not found\n";
    exit(1);
}

try {
    $teams = $user->teams()->get();
    echo 'Teams count: ' . $teams->count() . "\n";
    foreach ($teams as $team) {
        echo "- " . $team->name . "\n";
    }
} catch (Throwable $e) {
    echo 'Exception: ' . get_class($e) . " - " . $e->getMessage() . "\n";
}

echo "Done\n";
