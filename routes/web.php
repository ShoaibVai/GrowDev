<?php

use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::get('/profile/cv-pdf', [ProfileController::class, 'generatePDF'])->name('profile.cv-pdf');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Project routes
    Route::resource('projects', ProjectController::class);

    // Project task routes
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Team routes
    Route::post('/teams/{team}/invite', [\App\Http\Controllers\TeamController::class, 'invite'])->name('teams.invite');
    Route::patch('/teams/{team}/members/{user}', [\App\Http\Controllers\TeamController::class, 'assignRole'])->name('teams.assignRole');
    Route::resource('teams', \App\Http\Controllers\TeamController::class);

    // SRS Documentation routes
    Route::get('/documentation/srs', [DocumentationController::class, 'indexSrs'])->name('documentation.srs.index');
    Route::get('/documentation/srs/create', [DocumentationController::class, 'createSrs'])->name('documentation.srs.create');
    Route::post('/documentation/srs', [DocumentationController::class, 'storeSrs'])->name('documentation.srs.store');
    Route::get('/documentation/srs/{srsDocument}/edit', [DocumentationController::class, 'editSrs'])->name('documentation.srs.edit');
    Route::put('/documentation/srs/{srsDocument}', [DocumentationController::class, 'updateSrs'])->name('documentation.srs.update');
    Route::get('/documentation/srs/{srsDocument}/pdf', [DocumentationController::class, 'generateSrsPdf'])->name('documentation.srs.pdf');
    Route::delete('/documentation/srs/{srsDocument}', [DocumentationController::class, 'destroySrs'])->name('documentation.srs.destroy');

});

require __DIR__.'/auth.php';
