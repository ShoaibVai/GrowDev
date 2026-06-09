<?php

// Vercel serverless entry point for Laravel
// Overrides storage paths to /tmp (Vercel's only writable directory)

define('LARAVEL_START', microtime(true));

// Set up writable /tmp storage directories
$tmpStoragePath = $_ENV['APP_STORAGE_PATH'] ?? '/tmp/storage';

$writableDirs = [
    'framework/views',
    'framework/cache',
    'framework/sessions',
    'framework/testing',
    'logs',
];

foreach ($writableDirs as $dir) {
    $path = $tmpStoragePath . '/' . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->useStoragePath($tmpStoragePath);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
