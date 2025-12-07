<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $category
 * @property string|null $seniority_level
 * @property bool $is_system_role
 * @property int|null $team_id
 * @property int|null $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'seniority_level',
        'is_system_role',
        'team_id',
        'project_id',
    ];

    protected $casts = [
        'is_system_role' => 'boolean',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get tasks that require this role.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'required_role_id');
    }

    // Roles can be assigned to users via team_user pivot role_id; we can also define a relation for convenience
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_user', 'role_id', 'user_id');
    }

    /**
     * Scope for system-defined roles.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system_role', true);
    }

    /**
     * Scope for team-specific roles.
     */
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}
