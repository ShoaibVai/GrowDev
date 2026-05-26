<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Task model representing a work item within a project.
 * 
 * Tasks can be linked to SRS requirements (functional or non-functional) via
 * polymorphic relationship. Status changes follow an approval workflow where
 * assignees request changes and project owners approve/reject them.
 *
 * @property int $id
 * @property int $project_id
 * @property string $title
 * @property string|null $description
 * @property string $priority Low, Medium, High, or Critical
 * @property string $status To Do, In Progress, Review, or Done
 * @property int|null $assigned_to User ID of the assignee
 * @property int|null $created_by User ID of the creator
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property string|null $category
 * @property string|null $requirement_type Model class for linked requirement
 * @property int|null $requirement_id ID of the linked requirement
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Project $project
 * @property-read User|null $assignee
 * @property-read User|null $creator
 * @property-read Model|null $requirement
 * @property-read TaskStatusRequest|null $pendingStatusRequest
 */
class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'ai_generated_description',
        'is_ai_generated',
        'ai_generation_run_uuid',
        'prompt_schema_version',
        'priority',
        'status',
        'assigned_to',
        'assigned_at',
        'created_by',
        'due_date',
        'due_at',
        'estimated_hours',
        'time_estimate_hours',
        'category',
        'component',
        'component_key',
        'predicted_files',
        'interface_contracts',
        'requirement_type',
        'requirement_id',
        'required_role_id',
        'required_role',
        'is_scaffold',
        'scaffold_owner_id',
        'scaffold_task_id',
        'scaffold_merged_at',
        'scaffold_exceptions',
        'prompt_section',
        'prompt_payload',
        'prompt_brief',
        'timer_state',
        'time_spent_seconds',
        'timer_started_at',
        'timer_paused_at',
        'last_timer_tick_at',
        'timer_started_by',
        'last_reminded_at',
        'overdue_escalated_at',
        'sprint_id',
        'sort_order',
    ];

    protected $casts = [
        'due_date' => 'date',
        'assigned_at' => 'datetime',
        'due_at' => 'datetime',
        'predicted_files' => 'array',
        'interface_contracts' => 'array',
        'scaffold_exceptions' => 'array',
        'prompt_payload' => 'array',
        'is_ai_generated' => 'boolean',
        'is_scaffold' => 'boolean',
        'scaffold_merged_at' => 'datetime',
        'time_estimate_hours' => 'decimal:2',
        'time_spent_seconds' => 'integer',
        'timer_started_at' => 'datetime',
        'timer_paused_at' => 'datetime',
        'last_timer_tick_at' => 'datetime',
        'last_reminded_at' => 'datetime',
        'overdue_escalated_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the linked requirement (functional or non-functional).
     */
    public function requirement(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all status change requests for this task.
     */
    public function statusRequests(): HasMany
    {
        return $this->hasMany(TaskStatusRequest::class);
    }

    /**
     * Get the pending status change request if any.
     */
    public function pendingStatusRequest(): HasOne
    {
        return $this->hasOne(TaskStatusRequest::class)->where('approval_status', 'pending');
    }

    /**
     * Get the required role for this task.
     */
    public function requiredRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'required_role_id');
    }

    public function scaffoldOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scaffold_owner_id');
    }

    public function scaffoldTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'scaffold_task_id');
    }

    public function scaffoldDependents(): HasMany
    {
        return $this->hasMany(Task::class, 'scaffold_task_id');
    }

    public function timerStarter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'timer_started_by');
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }

    /**
     * Get tasks that this task depends on.
     */
    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id')
            ->withTimestamps();
    }

    /**
     * Get tasks that depend on this task.
     */
    public function dependents()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id')
            ->withTimestamps();
    }

    /**
     * Check if user is the project owner.
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->project->user_id === $user->id;
    }

    /**
     * Check if user is the assignee.
     */
    public function isAssignedTo(User $user): bool
    {
        return $this->assigned_to === $user->id;
    }

    /**
     * Scope a query to only include tasks assigned to a specific user.
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope a query to only include active (not completed/cancelled) tasks.
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['Done', 'completed', 'cancelled']);
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query
            ->where(function ($q) {
                $q->where('due_at', '<', now())
                    ->orWhere(function ($legacy) {
                        $legacy->whereNull('due_at')->where('due_date', '<', now()->toDateString());
                    });
            })
            ->whereNotIn('status', ['Done', 'completed', 'cancelled']);
    }

    public function scopeScaffolds($query)
    {
        return $query->where('is_scaffold', true);
    }

    public function scopeForComponent($query, ?string $componentKey)
    {
        return $query->where('component_key', $componentKey);
    }

    public function scopeRunningTimers($query)
    {
        return $query->where('timer_state', 'running')->whereNotNull('last_timer_tick_at');
    }

    public function scopeOverdueByDueAt($query)
    {
        return $query->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->whereNotIn('status', ['Done', 'completed', 'cancelled']);
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue(): bool
    {
        if (in_array($this->status, ['Done', 'completed', 'cancelled'], true)) {
            return false;
        }

        if ($this->due_at) {
            return $this->due_at->isPast();
        }

        return $this->due_date && $this->due_date->isPast();
    }

    public function isScaffoldComplete(): bool
    {
        return $this->scaffold_merged_at !== null || in_array($this->status, ['Done', 'completed'], true);
    }
}
