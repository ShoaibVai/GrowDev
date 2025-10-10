<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;

// This is a placeholder User model for compatibility with Laravel's auth system
// All authentication is handled by Supabase - this model represents Supabase users
class User implements Authenticatable
{
    public $id;
    public $email;
    public $name;
    public $supabase_id;
    public $email_verified_at;
    
    public function __construct($attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }
    }
    
    public function getAuthIdentifierName()
    {
        return 'id';
    }
    
    public function getAuthIdentifier()
    {
        return $this->id;
    }
    
    public function getAuthPassword()
    {
        return null; // Supabase handles passwords
    }
    
    public function getRememberToken()
    {
        return null;
    }
    
    public function setRememberToken($value)
    {
        // Not implemented for Supabase auth
    }
    
    public function getRememberTokenName()
    {
        return null;
    }
    
    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }
    
    public function __set($key, $value)
    {
        $this->$key = $value;
    }
}