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
        // Force HTTPS in production (required when behind Vercel/Heroku proxy)
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Register model policies here when AuthServiceProvider isn't registered
        if (class_exists(\App\Models\Team::class) && class_exists(\App\Policies\TeamPolicy::class)) {
            \Illuminate\Support\Facades\Gate::policy(\App\Models\Team::class, \App\Policies\TeamPolicy::class);
        }
    }
}
