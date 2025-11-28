<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SrsDocument extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'purpose',
        'document_conventions',
        'intended_audience',
        'product_scope',
        'references',
        'project_overview',
        'scope',
        'product_perspective',
        'product_features',
        'user_classes',
        'operating_environment',
        'design_constraints',
        'constraints',
        'assumptions',
        'dependencies',
        'external_interfaces',
        'system_features',
        'data_requirements',
        'appendices',
        'glossary',
        'version',
        'status',
    ];

    /**
     * Get the user who owns this document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project this SRS is attached to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the functional requirements for this document.
     */
    public function functionalRequirements(): HasMany
    {
        return $this->hasMany(SrsFunctionalRequirement::class)->orderBy('order');
    }

    /**
     * Get root-level functional requirements only.
     */
    public function rootFunctionalRequirements(): HasMany
    {
        return $this->hasMany(SrsFunctionalRequirement::class)
            ->whereNull('parent_id')
            ->orderBy('order');
    }

    /**
     * Get the non-functional requirements for this document.
     */
    public function nonFunctionalRequirements(): HasMany
    {
        return $this->hasMany(SrsNonFunctionalRequirement::class)->orderBy('order');
    }

    /**
     * Get root-level non-functional requirements only.
     */
    public function rootNonFunctionalRequirements(): HasMany
    {
        return $this->hasMany(SrsNonFunctionalRequirement::class)
            ->whereNull('parent_id')
            ->orderBy('order');
    }
}
