#!/usr/bin/env php
<?php
// Check projects in DB
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$projects = App\Models\Project::select('id','name','user_id')->orderBy('id')->get();
echo "Total projects: " . $projects->count() . "\n";
foreach($projects as $p) {
    echo "ID: {$p->id}, Name: {$p->name}, User: {$p->user_id}\n";
}
