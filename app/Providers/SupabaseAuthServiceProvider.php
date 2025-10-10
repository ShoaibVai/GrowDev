<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Auth\SupabaseUserProvider;

class SupabaseAuthServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Auth::provider('supabase', function ($app, array $config) {
            return new SupabaseUserProvider();
        });
    }
}