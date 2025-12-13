<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * SrsDocument Model
 * 
 * Represents a Software Requirements Specification (SRS) document.
 * SRS documents define all functional and non-functional requirements for a software project.
 * 
 * Key Features:
 * - Multiple sections for comprehensive requirements documentation
 * - Support for functional and non-functional requirements
 * - Version tracking and status management
 * - Associated with a user and optional project
 * 
 * @property int $id Primary key
 * @property int $user_id Owner of the SRS document
 * @property int|null $project_id Associated project (if any)
 * @property string $title Document title
 * @property string|null $description Brief description of the SRS
 * @property string|null $purpose Document purpose and objectives
 * @property string|null $document_conventions Conventions used in the document
 * @property string|null $intended_audience Target audience for the SRS
 * @property string|null $product_scope Scope of the software product
 * @property string|null $references Related documents and references
 * @property string|null $project_overview High-level overview of the project
 * @property string|null $scope Detailed scope of requirements
 * @property string|null $product_perspective How the product fits in the ecosystem
 * @property string|null $product_features Major features of the product
 * @property string|null $user_classes Different types of users
 * @property string|null $operating_environment Operating environment specifications
 * @property string|null $design_constraints Design and implementation constraints
 * @property string|null $constraints Overall constraints on the product
 * @property string|null $assumptions Assumptions made in requirements
 * @property string|null $dependencies External dependencies
 * @property string|null $external_interfaces External system interfaces
 * @property string|null $system_features System features and capabilities
 * @property string|null $data_requirements Data storage and management requirements
 * @property string|null $appendices Additional appendices
 * @property string|null $glossary Glossary of terms
 * @property string|null $version Document version number
 * @property string|null $status Document status (draft, finalized, etc.)
 * @property \Illuminate\Support\Carbon|null $created_at Creation timestamp
 * @property \Illuminate\Support\Carbon|null $updated_at Last update timestamp
 */
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
