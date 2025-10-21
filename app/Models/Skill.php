<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'skill_name',
        'proficiency',
        'order',
    ];

    /**
     * Get the user that owns this skill.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
