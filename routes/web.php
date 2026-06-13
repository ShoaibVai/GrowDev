<?php

use App\Http\Controllers\AITaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskPromptController;
use App\Http\Controllers\TaskTimerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

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
    
    // Global Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');

    // Theme preference
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');

    // User search API (for AJAX calls from web views - uses session auth)
    Route::get('/web-api/users/search', [\App\Http\Controllers\Api\UserSearchController::class, 'search'])->name('web.users.search');
    
    // Project routes (whereNumber prevents DB errors on non-numeric IDs like /projects/a)
    Route::resource('projects', ProjectController::class)->whereNumber('project');
    Route::get('/projects/{project}/board', [ProjectController::class, 'board'])->whereNumber('project')->name('projects.board');
    Route::get('/projects/{project}/members/summary', [ProjectController::class, 'membersSummary'])->whereNumber('project')->name('projects.members.summary');
    Route::patch('/projects/{project}/requirements/{type}/{requirement}', [ProjectController::class, 'updateRequirementStatus'])->whereNumber('project')->name('projects.requirements.update');

    // Sprint routes (nested under projects)
    Route::resource('projects.sprints', SprintController::class)->shallow()->whereNumber('project', 'sprint');
    Route::post('/projects/{project}/sprints/{sprint}/start', [SprintController::class, 'start'])->whereNumber('project')->name('sprints.start');
    Route::post('/projects/{project}/sprints/{sprint}/complete', [SprintController::class, 'complete'])->whereNumber('project')->name('sprints.complete');
    Route::post('/projects/{project}/sprints/{sprint}/cancel', [SprintController::class, 'cancel'])->whereNumber('project')->name('sprints.cancel');

    // AI Task Generation routes
    Route::get('/projects/{project}/ai-tasks', [AITaskController::class, 'preview'])->whereNumber('project')->name('projects.ai-tasks.preview');
    Route::post('/projects/{project}/ai-tasks/generate', [AITaskController::class, 'generate'])->whereNumber('project')->name('projects.ai-tasks.generate');
    Route::post('/projects/{project}/ai-tasks/store', [AITaskController::class, 'store'])->whereNumber('project')->name('projects.ai-tasks.store');
    Route::post('/projects/{project}/ai-tasks/layered/start', [AITaskController::class, 'startLayeredGeneration'])->whereNumber('project')->name('projects.ai-tasks.layered.start');
    Route::get('/projects/{project}/ai-tasks/layered/{runId}', [AITaskController::class, 'layeredStatus'])->whereNumber('project')->name('projects.ai-tasks.layered.status');
    Route::post('/projects/{project}/ai-tasks/layered/{runId}/commit', [AITaskController::class, 'commitLayeredGeneration'])->whereNumber('project')->name('projects.ai-tasks.layered.commit');

    // Project task routes
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/{task}/prompt', [TaskPromptController::class, 'show'])->name('tasks.prompt.show');
    Route::get('/tasks/{task}/timer', [TaskTimerController::class, 'show'])->name('tasks.timer.show');
    Route::post('/tasks/{task}/timer/start', [TaskTimerController::class, 'start'])->name('tasks.timer.start');
    Route::post('/tasks/{task}/timer/pause', [TaskTimerController::class, 'pause'])->name('tasks.timer.pause');
    Route::post('/tasks/{task}/timer/resume', [TaskTimerController::class, 'resume'])->name('tasks.timer.resume');
    Route::post('/tasks/{task}/timer/stop', [TaskTimerController::class, 'stop'])->name('tasks.timer.stop');
    
    // Task status change request routes (for assignee approval workflow)
    Route::post('/tasks/{task}/request-status', [TaskController::class, 'requestStatusChange'])->name('tasks.request-status');
    Route::post('/task-status-requests/{statusRequest}/review', [TaskController::class, 'reviewStatusRequest'])->name('tasks.review-status-request');

    // Task comments
    Route::get('/tasks/{task}/comments', [CommentController::class, 'index'])->name('tasks.comments.index');
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])->name('tasks.comments.store');
    Route::delete('/tasks/{task}/comments/{comment}', [CommentController::class, 'destroy'])->name('tasks.comments.destroy');

    // Task time logging
    Route::post('/tasks/{task}/time-logs', [TaskController::class, 'logTime'])->name('tasks.time-logs.store');
    Route::get('/tasks/{task}/time-logs', [TaskController::class, 'timeLogs'])->name('tasks.time-logs.index');

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

    // SRS Documentation routes
    Route::get('/documentation/srs', [DocumentationController::class, 'indexSrs'])->name('documentation.srs.index');
    Route::get('/documentation/srs/create', [DocumentationController::class, 'createSrs'])->name('documentation.srs.create');
    Route::post('/documentation/srs', [DocumentationController::class, 'storeSrs'])->name('documentation.srs.store');
    Route::get('/documentation/srs/{srsDocument}/edit', [DocumentationController::class, 'editSrs'])->name('documentation.srs.edit');
    Route::put('/documentation/srs/{srsDocument}', [DocumentationController::class, 'updateSrs'])->name('documentation.srs.update');
    Route::get('/documentation/srs/{srsDocument}/pdf', [DocumentationController::class, 'generateSrsPdf'])->name('documentation.srs.pdf');
    Route::delete('/documentation/srs/{srsDocument}', [DocumentationController::class, 'destroySrs'])->name('documentation.srs.destroy');

    // Admin Routes
    Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/export/{type}', [\App\Http\Controllers\AdminController::class, 'export'])->name('admin.export');

});

// Invitation accept/decline (publicly accessible to allow redirection to login)
Route::get('/invitations/accept/{token}', [\App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');
Route::get('/invitations/decline/{token}', [\App\Http\Controllers\InvitationController::class, 'decline'])->name('invitations.decline');

require __DIR__.'/auth.php';
