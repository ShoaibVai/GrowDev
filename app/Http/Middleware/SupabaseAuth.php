<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SupabaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if we have a Supabase session
        if (!session('supabase_token') || !session('supabase_user')) {
            Auth::logout();
            return redirect('/login');
        }

        // Ensure the Laravel auth user is set from session data
        if (!Auth::check()) {
            $supabaseUser = session('supabase_user');
            
            $user = new User([
                'id' => $supabaseUser['id'],
                'email' => $supabaseUser['email'],
                'name' => $supabaseUser['user_metadata']['name'] ?? $supabaseUser['email'],
                'supabase_id' => $supabaseUser['id'],
                'email_verified_at' => $supabaseUser['email_confirmed_at'] ?? null,
            ]);

            Auth::setUser($user);
        }

        return $next($request);
    }
}