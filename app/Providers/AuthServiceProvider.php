<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Team;
use App\Models\SrsDocument;
use App\Policies\TeamPolicy;
use App\Policies\SrsDocumentPolicy;

/**
 * AuthServiceProvider
 * 
 * Registers authorization policies for model classes.
 * Policies define authorization logic for actions like view, create, update, delete.
 * 
 * Registered Policies:
 * - TeamPolicy: Controls team-related authorization
 * - SrsDocumentPolicy: Controls SRS document access and modifications
 * 
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     * 
     * Maps model classes to their corresponding authorization policies.
     * These policies are automatically resolved by Laravel's Gate when using $this->authorize()
     * 
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        SrsDocument::class => SrsDocumentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
