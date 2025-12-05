<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'priority',
        'status',
        'assigned_to',
        'created_by',
        'due_date',
        'category',
        'requirement_type',
        'requirement_id',
    ];

    protected $casts = [
        'due_date' => 'date',
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
}
