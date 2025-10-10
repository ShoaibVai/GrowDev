<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class TestAuthFlow extends Command
{
    protected $signature = 'test:auth-flow';
    protected $description = 'Test the complete authentication flow';

    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        parent::__construct();
        $this->supabase = $supabase;
    }

    public function handle()
    {
        $this->info('🧪 Testing complete authentication flow...');
        
        // Test 1: Registration
        $this->info('📝 Testing registration...');
        $email = 'test_' . time() . '@outlook.com';
        $password = 'TestPassword123!';
        
        $registrationResult = $this->supabase->signUp($email, $password, ['name' => 'Test User']);
        
        if ($registrationResult['success']) {
            $this->info("✅ Registration successful for: {$email}");
            
            // Wait a moment for the user to be available
            sleep(2);
            
            // Test 2: Login
            $this->info('🔑 Testing login...');
            $loginResult = $this->supabase->signIn($email, $password);
            
            if ($loginResult['success']) {
                $this->info("✅ Login successful");
                $this->info("Token: " . substr($loginResult['data']['access_token'], 0, 20) . "...");
                
                // Test 3: Get user details
                $userResult = $this->supabase->getUser($loginResult['data']['access_token']);
                if ($userResult['success']) {
                    $this->info("✅ User details retrieved");
                    $this->info("User ID: " . $userResult['data']['id']);
                    $this->info("Email: " . $userResult['data']['email']);
                }
                
                // Test 4: Logout
                $this->info('🚪 Testing logout...');
                $logoutResult = $this->supabase->signOut($loginResult['data']['access_token']);
                if ($logoutResult['success']) {
                    $this->info("✅ Logout successful");
                }
            } else {
                $this->error("❌ Login failed: " . ($loginResult['error'] ?? 'Unknown error'));
            }
        } else {
            $this->error("❌ Registration failed: " . ($registrationResult['error'] ?? 'Unknown error'));
        }
        
        $this->info('🎉 Authentication flow test completed!');
    }
}