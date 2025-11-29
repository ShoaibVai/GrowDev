<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Team;
use App\Policies\TeamPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
