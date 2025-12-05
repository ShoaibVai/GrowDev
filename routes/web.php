<?php

use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\NotificationController;
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
    Route::get('/profile/digests/preview', [ProfileController::class, 'previewDigests'])->name('profile.digests.preview');
    Route::get('/profile/digests/history', [ProfileController::class, 'digestHistory'])->name('profile.digests.history');
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::get('/profile/cv-pdf', [ProfileController::class, 'generatePDF'])->name('profile.cv-pdf');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    
    // User search API (for AJAX calls from web views - uses session auth)
    Route::get('/web-api/users/search', [\App\Http\Controllers\Api\UserSearchController::class, 'search'])->name('web.users.search');
    
    // Project routes
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/board', [ProjectController::class, 'board'])->name('projects.board');
    Route::get('/projects/{project}/members/summary', [ProjectController::class, 'membersSummary'])->name('projects.members.summary');
    Route::patch('/projects/{project}/requirements/{type}/{requirement}', [ProjectController::class, 'updateRequirementStatus'])->name('projects.requirements.update');

    // Project task routes
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    // Task status change request routes (for assignee approval workflow)
    Route::post('/tasks/{task}/request-status', [TaskController::class, 'requestStatusChange'])->name('tasks.request-status');
    Route::post('/task-status-requests/{statusRequest}/review', [TaskController::class, 'reviewStatusRequest'])->name('tasks.review-status-request');

    // My Tasks page (all tasks assigned to user)
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my-tasks');

    // Team routes
    Route::post('/teams/{team}/invite', [\App\Http\Controllers\TeamController::class, 'invite'])->name('teams.invite');
    Route::delete('/teams/{team}/invitations/{invitation}', [\App\Http\Controllers\TeamController::class, 'cancelInvitation'])->name('teams.invitations.cancel');
    Route::patch('/teams/{team}/members/{user}', [\App\Http\Controllers\TeamController::class, 'assignRole'])->name('teams.assignRole');
    Route::delete('/teams/{team}/members/{user}', [\App\Http\Controllers\TeamController::class, 'removeMember'])->name('teams.removeMember');
    Route::resource('teams', \App\Http\Controllers\TeamController::class);
    // Team role management
    Route::get('/teams/{team}/roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('teams.roles.index');
    Route::post('/teams/{team}/roles', [\App\Http\Controllers\RoleController::class, 'store'])->name('teams.roles.store');
    Route::delete('/teams/{team}/roles/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('teams.roles.destroy');

    // Invitation accept/decline (available to guest to allow sign-in flow)
    Route::get('/invitations/accept/{token}', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
    Route::get('/invitations/decline/{token}', [\App\Http\Controllers\InvitationController::class, 'decline'])->name('invitations.decline');

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
