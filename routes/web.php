<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailConfirmationController;
use App\Http\Controllers\SupabaseTestController;

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

// Test route for Supabase connection
Route::get('/test-supabase', [AuthController::class, 'testSupabase'])->name('test.supabase');

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
    // Route::resource('projects', ProjectController::class);
});

// Supabase Test Routes (with CSRF exemption for testing)
Route::group(['prefix' => 'supabase-test'], function () {
    Route::get('/', [SupabaseTestController::class, 'index'])->name('supabase.test');
    Route::get('/connection', [SupabaseTestController::class, 'testConnection']);
    Route::post('/signup', [SupabaseTestController::class, 'testSignup']);
    Route::post('/signin', [SupabaseTestController::class, 'testSignin']);
    Route::get('/users', [SupabaseTestController::class, 'listUsers']);
    Route::get('/schema', [SupabaseTestController::class, 'checkSchema']);
    Route::post('/profile', [SupabaseTestController::class, 'getProfile']);
});

//     Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
// });

// Remove the auth.php requirement for now
// require __DIR__.'/auth.php';