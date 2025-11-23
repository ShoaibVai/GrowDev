<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/', 'GET');
$kernel->handle($request);

use App\Models\User;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

$user = User::find(2);
if (! $user) {
    echo "User not found\n";
    exit(1);
}

Auth::login($user);

$controller = new DashboardController();
try {
    $response = $controller->index(new Illuminate\Http\Request());
    // If the controller returns a view, get its data
    if ($response instanceof Illuminate\Contracts\View\View) {
        $data = $response->getData();
        echo "Dashboard data keys: " . implode(', ', array_keys($data)) . "\n";
        echo "Projects: " . count($data['projects']) . "\n";
        echo "Teams Count: " . ($data['teamsCount'] ?? 'N/A') . "\n";
    } else {
        echo "Controller returned type: " . gettype($response) . PHP_EOL;
    }
} catch (Throwable $e) {
    echo 'Exception: ' . get_class($e) . " - " . $e->getMessage() . "\n";
}

echo "Done\n";
