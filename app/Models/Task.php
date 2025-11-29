<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
