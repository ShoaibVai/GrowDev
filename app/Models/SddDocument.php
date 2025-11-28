<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SddDocument extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'design_overview',
        'architecture_overview',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(SddComponent::class)->orderBy('order');
    }

    public function diagrams(): HasMany
    {
        return $this->hasMany(SddDiagram::class);
    }
}
