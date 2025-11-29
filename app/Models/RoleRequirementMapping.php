<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RoleRequirementMapping extends Model
{
    protected $fillable = ['role_id', 'requirement_id', 'requirement_type'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function requirement(): MorphTo
    {
        return $this->morphTo();
    }
}
