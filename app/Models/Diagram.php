<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $documentation_id
 * @property string $type
 * @property string $mermaid_syntax
 * @property string|null $title
 * @property string|null $description
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Diagram extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'documentation_id',
        'type',
        'mermaid_syntax',
        'title',
        'description',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'mermaid_syntax' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the documentation that this diagram belongs to.
     */
    public function documentation(): BelongsTo
    {
        return $this->belongsTo(Documentation::class, 'documentation_id');
    }

    /**
     * Get the user who created this diagram.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Validate Mermaid syntax (basic validation).
     */
    public static function validateMermaidSyntax(string $syntax): bool
    {
        // Basic validation - check if syntax is not empty
        // More advanced validation could be done via external API
        return !empty(trim($syntax)) && strlen($syntax) >= 3;
    }

    /**
     * Get diagram preview URL for rendering.
     */
    public function getPreviewUrl(): string
    {
        return route('diagrams.preview', $this->id);
    }

    /**
     * Export diagram as SVG.
     */
    public function exportAsSvg(): string
    {
        // This would integrate with Mermaid's CLI or API
        // For now, returning placeholder
        return '<svg><!-- Mermaid SVG --></svg>';
    }
}
