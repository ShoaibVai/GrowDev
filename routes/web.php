<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailConfirmationController;

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

// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Email confirmation routes (accessible to all)
Route::get('/auth/confirm', [EmailConfirmationController::class, 'confirm'])->name('auth.confirm');
Route::get('/auth/callback', [EmailConfirmationController::class, 'callback'])->name('auth.callback');
Route::get('/email/verify', [EmailConfirmationController::class, 'show'])->name('verification.notice');
Route::post('/email/resend', [EmailConfirmationController::class, 'resend'])->name('verification.resend');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
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
    
    // Projects resource routes
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
});
// });

// Remove the auth.php requirement for now
// require __DIR__.'/auth.php';