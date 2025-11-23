<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'totp_secret',
        'phone_number',
        'professional_summary',
        'location',
        'website',
        'linkedin_url',
        'github_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'totp_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the projects for the user.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the teams the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withPivot('role')->withTimestamps();
    }

    /**
     * Get the teams the user owns.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    /**
     * Get the work experiences for the user.
     */
    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class)->orderBy('order');
    }

    /**
     * Get the educations for the user.
     */
    public function educations(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('order');
    }

    /**
     * Get the skills for the user.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('order');
    }

    /**
     * Get the certifications for the user.
     */
    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class)->orderBy('order');
    }

    /**
     * Get the SRS documents for the user.
     */
    public function srsDocuments(): HasMany
    {
        return $this->hasMany(SrsDocument::class);
    }

    /**
     * Get the SDD documents for the user.
     */
    public function sddDocuments(): HasMany
    {
        return $this->hasMany(SddDocument::class);
    }
}
