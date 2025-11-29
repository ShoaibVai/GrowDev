<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
