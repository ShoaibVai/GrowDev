<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sprint extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'goal',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePlanned($query)
    {
        return $query->where('status', 'planned');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function progress(): array
    {
        $counts = $this->tasks()
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as done', ['Done'])
            ->first();

        $total = (int) ($counts->total ?? 0);
        $done = (int) ($counts->done ?? 0);

        if ($total === 0) {
            return ['total' => 0, 'done' => 0, 'percentage' => 0];
        }

        return [
            'total' => $total,
            'done' => $done,
            'percentage' => round(($done / $total) * 100),
        ];
    }
}
