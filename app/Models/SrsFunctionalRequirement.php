<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SrsFunctionalRequirement extends Model
{
    protected $fillable = [
        'srs_document_id',
        'parent_id',
        'requirement_id',
        'section_number',
        'title',
        'description',
        'acceptance_criteria',
        'source',
        'priority',
        'status',
        'implementation_status',
        'ux_considerations',
        'order',
    ];

    protected $casts = [
        'ux_considerations' => 'array',
    ];

    /**
     * Available implementation statuses.
     */
    public const IMPLEMENTATION_STATUSES = [
        'listed' => 'Listed',
        'work_in_progress' => 'Work in Progress',
        'completed' => 'Completed',
        'compromised' => 'Compromised',
        'under_maintenance' => 'Under Maintenance',
    ];

    /**
     * Get the SRS document this requirement belongs to.
     */
    public function srsDocument(): BelongsTo
    {
        return $this->belongsTo(SrsDocument::class);
    }

    /**
     * Get the parent requirement (for hierarchical structure).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(SrsFunctionalRequirement::class, 'parent_id');
    }

    /**
     * Get child requirements (sub-requirements).
     */
    public function children(): HasMany
    {
        return $this->hasMany(SrsFunctionalRequirement::class, 'parent_id')->orderBy('order');
    }

    public function roleMappings(): MorphMany
    {
        return $this->morphMany(\App\Models\RoleRequirementMapping::class, 'requirement');
    }

    /**
     * Get tasks linked to this requirement.
     */
    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'requirement');
    }

    /**
     * Get all descendants recursively.
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Check if this is a root level requirement.
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Get the depth level of this requirement in the hierarchy.
     */
    public function getDepthAttribute(): int
    {
        return count(explode('.', $this->section_number)) - 1;
    }

    /**
     * Get the implementation status label.
     */
    public function getImplementationStatusLabelAttribute(): string
    {
        return self::IMPLEMENTATION_STATUSES[$this->implementation_status] ?? $this->implementation_status;
    }
}
