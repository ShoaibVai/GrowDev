<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SrsNonFunctionalRequirement extends Model
{
    protected $fillable = [
        'srs_document_id',
        'parent_id',
        'requirement_id',
        'section_number',
        'title',
        'description',
        'category',
        'acceptance_criteria',
        'measurement',
        'target_value',
        'source',
        'priority',
        'status',
        'order',
    ];

    /**
     * The available categories for non-functional requirements.
     */
    public const CATEGORIES = [
        'performance' => 'Performance',
        'security' => 'Security',
        'reliability' => 'Reliability',
        'availability' => 'Availability',
        'maintainability' => 'Maintainability',
        'scalability' => 'Scalability',
        'usability' => 'Usability',
        'compatibility' => 'Compatibility',
        'compliance' => 'Compliance',
        'other' => 'Other',
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
        return $this->belongsTo(SrsNonFunctionalRequirement::class, 'parent_id');
    }

    /**
     * Get child requirements (sub-requirements).
     */
    public function children(): HasMany
    {
        return $this->hasMany(SrsNonFunctionalRequirement::class, 'parent_id')->orderBy('order');
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
     * Get the category display name.
     */
    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}
