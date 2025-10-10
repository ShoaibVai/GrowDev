<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class DebugRegistration extends Command
{
    protected $signature = 'debug:registration {email?} {password?}';
    protected $description = 'Debug registration issues';

    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        parent::__construct();
        $this->supabase = $supabase;
    }

    public function handle()
    {
        $this->info('🔍 Debugging registration process...');
        
        $email = $this->argument('email') ?: 'test_debug@outlook.com';
        $password = $this->argument('password') ?: 'TestPassword123!';
        $name = 'Debug User';
        
        $this->info("Testing with email: {$email}");
        $this->info("Password length: " . strlen($password));
        
        // Test Supabase connection first
        $this->info('📡 Testing Supabase connection...');
        $connectionTest = $this->supabase->testConnection();
        
        if ($connectionTest['success']) {
            $this->info('✅ Supabase connection successful');
        } else {
            $this->error('❌ Supabase connection failed');
            $this->line(json_encode($connectionTest, JSON_PRETTY_PRINT));
            return;
        }
        
        // Test registration
        $this->info('👤 Testing registration...');
        $result = $this->supabase->signUp($email, $password, [
            'name' => $name,
            'full_name' => $name
        ]);
        
        $this->info('Registration result:');
        $this->line(json_encode($result, JSON_PRETTY_PRINT));
        
        if ($result['success']) {
            $this->info('✅ Registration successful!');
            
            // Check if user is confirmed
            if (isset($result['data']['user']['email_confirmed_at'])) {
                $this->info('✅ Email automatically confirmed');
            } else {
                $this->warn('⚠️ Email confirmation required');
            }
        } else {
            $this->error('❌ Registration failed');
            
            // Try to get more detailed error info
            if (isset($result['data']['error_description'])) {
                $this->error('Error: ' . $result['data']['error_description']);
            }
            
            if (isset($result['data']['error'])) {
                $this->error('Error type: ' . $result['data']['error']);
            }
        }
    }
}