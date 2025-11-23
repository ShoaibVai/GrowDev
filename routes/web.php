<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DocumentationController;
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

    // SDD Documentation routes
    Route::get('/documentation/sdd', [DocumentationController::class, 'indexSdd'])->name('documentation.sdd.index');
    Route::get('/documentation/sdd/create', [DocumentationController::class, 'createSdd'])->name('documentation.sdd.create');
    Route::post('/documentation/sdd', [DocumentationController::class, 'storeSdd'])->name('documentation.sdd.store');
    Route::get('/documentation/sdd/{sddDocument}/edit', [DocumentationController::class, 'editSdd'])->name('documentation.sdd.edit');
    Route::put('/documentation/sdd/{sddDocument}', [DocumentationController::class, 'updateSdd'])->name('documentation.sdd.update');
    Route::get('/documentation/sdd/{sddDocument}/pdf', [DocumentationController::class, 'generateSddPdf'])->name('documentation.sdd.pdf');
    Route::delete('/documentation/sdd/{sddDocument}', [DocumentationController::class, 'destroySdd'])->name('documentation.sdd.destroy');

    // API endpoint for text to diagram conversion
    Route::post('/api/documentation/text-to-diagram', [DocumentationController::class, 'convertTextToDiagram']);
});

require __DIR__.'/auth.php';
