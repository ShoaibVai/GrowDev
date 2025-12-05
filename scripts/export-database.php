<?php

/**
 * Script to export current database data for seeder generation
 * Run with: php scripts/export-database.php
 * 
 * This script exports all data from the database in a format that can be
 * easily used to recreate the ProductionDataSeeder.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Task;
use App\Models\Role;
use App\Models\Invitation;
use App\Models\SrsDocument;
use App\Models\SrsFunctionalRequirement;
use App\Models\SrsNonFunctionalRequirement;
use App\Models\NotificationPreference;
use App\Models\NotificationEvent;

echo "=== DATABASE EXPORT ===\n\n";

// Users
echo "--- USERS ---\n";
$users = User::all();
foreach ($users as $user) {
    echo json_encode([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
        'totp_secret' => $user->totp_secret,
        'created_at' => $user->created_at?->toDateTimeString(),
    ], JSON_PRETTY_PRINT) . "\n";
}

// Teams
echo "\n--- TEAMS ---\n";
$teams = Team::all();
foreach ($teams as $team) {
    echo json_encode([
        'id' => $team->id,
        'name' => $team->name,
        'description' => $team->description,
        'owner_id' => $team->owner_id,
        'created_at' => $team->created_at?->toDateTimeString(),
    ], JSON_PRETTY_PRINT) . "\n";
}

// Team Members (pivot)
echo "\n--- TEAM MEMBERS ---\n";
foreach ($teams as $team) {
    $members = $team->members()->get();
    foreach ($members as $member) {
        echo json_encode([
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]) . "\n";
    }
}

// Roles
echo "\n--- ROLES ---\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo json_encode([
        'id' => $role->id,
        'team_id' => $role->team_id,
        'user_id' => $role->user_id,
        'role' => $role->role,
    ]) . "\n";
}

// Invitations
echo "\n--- INVITATIONS ---\n";
$invitations = Invitation::all();
foreach ($invitations as $inv) {
    echo json_encode([
        'id' => $inv->id,
        'team_id' => $inv->team_id,
        'email' => $inv->email,
        'token' => $inv->token,
        'status' => $inv->status,
    ]) . "\n";
}

// Projects
echo "\n--- PROJECTS ---\n";
$projects = Project::all();
foreach ($projects as $project) {
    echo json_encode([
        'id' => $project->id,
        'name' => $project->name,
        'description' => $project->description,
        'team_id' => $project->team_id,
        'user_id' => $project->user_id,
        'created_at' => $project->created_at?->toDateTimeString(),
    ], JSON_PRETTY_PRINT) . "\n";
}

// Project Members (pivot) - check if relationship exists
echo "\n--- PROJECT MEMBERS ---\n";
try {
    foreach ($projects as $project) {
        if (method_exists($project, 'members')) {
            $members = $project->members()->get();
            foreach ($members as $member) {
                echo json_encode([
                    'project_id' => $project->id,
                    'user_id' => $member->id,
                    'role' => $member->pivot->role ?? null,
                ]) . "\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Skipped - relationship not defined\n";
}

// Tasks
echo "\n--- TASKS ---\n";
$tasks = Task::all();
foreach ($tasks as $task) {
    echo json_encode([
        'id' => $task->id,
        'title' => $task->title,
        'description' => $task->description,
        'status' => $task->status,
        'priority' => $task->priority,
        'project_id' => $task->project_id,
        'assigned_to' => $task->assigned_to,
        'due_date' => $task->due_date,
        'created_at' => $task->created_at?->toDateTimeString(),
    ], JSON_PRETTY_PRINT) . "\n";
}

// SRS Documents
echo "\n--- SRS DOCUMENTS ---\n";
$srsDocs = SrsDocument::all();
foreach ($srsDocs as $doc) {
    echo json_encode([
        'id' => $doc->id,
        'project_id' => $doc->project_id,
        'user_id' => $doc->user_id,
        'title' => $doc->title,
        'version' => $doc->version,
        'purpose' => $doc->purpose,
        'references' => $doc->references,
        'product_perspective' => $doc->product_perspective,
        'product_features' => $doc->product_features,
        'user_classes' => $doc->user_classes,
        'operating_environment' => $doc->operating_environment,
        'constraints' => $doc->constraints,
        'assumptions' => $doc->assumptions,
    ], JSON_PRETTY_PRINT) . "\n";
}

// SRS Functional Requirements
echo "\n--- SRS FUNCTIONAL REQUIREMENTS ---\n";
$funcReqs = SrsFunctionalRequirement::all();
foreach ($funcReqs as $req) {
    echo json_encode([
        'id' => $req->id,
        'srs_document_id' => $req->srs_document_id,
        'requirement_id' => $req->requirement_id,
        'section_number' => $req->section_number,
        'title' => $req->title,
        'description' => $req->description,
        'priority' => $req->priority,
        'parent_id' => $req->parent_id,
    ]) . "\n";
}

// SRS Non-Functional Requirements
echo "\n--- SRS NON-FUNCTIONAL REQUIREMENTS ---\n";
$nonFuncReqs = SrsNonFunctionalRequirement::all();
foreach ($nonFuncReqs as $req) {
    echo json_encode([
        'id' => $req->id,
        'srs_document_id' => $req->srs_document_id,
        'requirement_id' => $req->requirement_id,
        'section_number' => $req->section_number,
        'category' => $req->category,
        'title' => $req->title,
        'description' => $req->description,
        'priority' => $req->priority,
        'parent_id' => $req->parent_id,
    ]) . "\n";
}

// Notification Events
echo "\n--- NOTIFICATION EVENTS ---\n";
$events = NotificationEvent::all();
foreach ($events as $event) {
    echo json_encode([
        'id' => $event->id,
        'name' => $event->name,
        'description' => $event->description,
        'category' => $event->category,
    ]) . "\n";
}

// Notification Preferences
echo "\n--- NOTIFICATION PREFERENCES ---\n";
$prefs = NotificationPreference::all();
foreach ($prefs as $pref) {
    echo json_encode([
        'id' => $pref->id,
        'user_id' => $pref->user_id,
        'notification_event_id' => $pref->notification_event_id,
        'email_enabled' => $pref->email_enabled,
        'in_app_enabled' => $pref->in_app_enabled,
    ]) . "\n";
}

echo "\n=== EXPORT COMPLETE ===\n";
