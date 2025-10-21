<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'certification_name',
        'issuer',
        'description',
        'issue_date',
        'expiry_date',
        'credential_url',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the user that owns this certification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
