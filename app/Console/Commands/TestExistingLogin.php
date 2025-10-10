<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class TestExistingLogin extends Command
{
    protected $signature = 'test:existing-login';
    protected $description = 'Test login with existing user';

    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        parent::__construct();
        $this->supabase = $supabase;
    }

    public function handle()
    {
        $this->info('🔑 Testing login with existing user...');
        
        // Test with existing user
        $email = 'galecrimson32@gmail.com'; // This user exists from our previous tests
        $password = $this->secret('Enter password for ' . $email);
        
        $loginResult = $this->supabase->signIn($email, $password);
        
        if ($loginResult['success']) {
            $this->info("✅ Login successful!");
            $this->info("User ID: " . $loginResult['data']['user']['id']);
            $this->info("Email: " . $loginResult['data']['user']['email']);
            $this->info("Token: " . substr($loginResult['data']['access_token'], 0, 20) . "...");
        } else {
            $this->error("❌ Login failed");
            $this->line("Status: " . $loginResult['status']);
            $this->line("Error: " . json_encode($loginResult['data'], JSON_PRETTY_PRINT));
        }
    }
}