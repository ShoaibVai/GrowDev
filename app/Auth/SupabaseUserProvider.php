<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;

class SupabaseUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        // Check session for user data
        $supabaseUser = session('supabase_user');
        
        if ($supabaseUser && $supabaseUser['id'] == $identifier) {
            return new User([
                'id' => $supabaseUser['id'],
                'email' => $supabaseUser['email'],
                'name' => $supabaseUser['user_metadata']['name'] ?? $supabaseUser['email'],
                'supabase_id' => $supabaseUser['id'],
                'email_verified_at' => $supabaseUser['email_confirmed_at'] ?? null,
            ]);
        }
        
        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        // Not implemented for Supabase (uses JWT tokens)
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Not implemented for Supabase
    }

    public function retrieveByCredentials(array $credentials)
    {
        // Not used in our flow as Supabase handles credential verification
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // Supabase handles credential validation
        return true;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // Not applicable for Supabase
    }
}