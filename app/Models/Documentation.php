<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $project_id
 * @property int|null $template_id
 * @property string $title
 * @property array|null $content
 * @property int $version
 * @property string $status
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Documentation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'template_id',
        'title',
        'content',
        'version',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the template for this documentation.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(DocumentationTemplate::class, 'template_id');
    }

    /**
     * Get the project for this documentation.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the user who created this documentation.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the diagrams for this documentation.
     */
    public function diagrams(): HasMany
    {
        return $this->hasMany(Diagram::class, 'documentation_id')->orderBy('created_at', 'desc');
    }

    /**
     * Increment the version number.
     */
    public function incrementVersion(): void
    {
        $this->increment('version');
    }

    /**
     * Check if the user can edit this documentation.
     */
    public function canEdit(User $user): bool
    {
        return $this->created_by === $user->id || $user->isAdmin();
    }
}
