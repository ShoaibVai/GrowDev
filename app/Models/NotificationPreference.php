<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'email_on_task_assigned',
        'email_on_task_status_change',
        'email_reminders',
        'digest_frequency',
        'digest_time',
        'last_digest_sent_at',
        'timezone',
        'digest_day',
        'email_on_team_invitation',
        'email_on_srs_update',
    ];

    protected $casts = [
        'email_on_task_assigned' => 'boolean',
        'email_on_task_status_change' => 'boolean',
        'email_reminders' => 'boolean',
        'last_digest_sent_at' => 'datetime',
        'timezone' => 'string',
        'digest_day' => 'string',
        'email_on_team_invitation' => 'boolean',
        'email_on_srs_update' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
