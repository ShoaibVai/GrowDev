<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'projects' => [],
        'stats' => [
            'totalProjects' => 0,
            'activeProjects' => 0,
            'pendingTasks' => 0,
            'teamMembers' => 1
        ]
    ]);
})->name('dashboard');

Route::get('/projects', function () {
    return Inertia::render('Projects/Index', [
        'projects' => []
    ]);
})->name('projects.index');

Route::get('/projects/create', function () {
    return Inertia::render('Projects/Create');
})->name('projects.create');

// Temporarily comment out auth-protected routes
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
//     // Projects
//     Route::resource('projects', ProjectController::class);
//     Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
// });

// Remove the auth.php requirement for now
// require __DIR__.'/auth.php';