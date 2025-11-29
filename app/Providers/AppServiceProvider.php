<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model policies here when AuthServiceProvider isn't registered
        if (class_exists(\App\Models\Team::class) && class_exists(\App\Policies\TeamPolicy::class)) {
            \Illuminate\Support\Facades\Gate::policy(\App\Models\Team::class, \App\Policies\TeamPolicy::class);
        }
    }
}
