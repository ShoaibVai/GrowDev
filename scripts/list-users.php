<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/', 'GET');
$kernel->handle($request);

use App\Models\User;

$users = User::all();
foreach ($users as $user) {
    echo "User: {$user->id} - {$user->name} ({$user->email})\n";
}
