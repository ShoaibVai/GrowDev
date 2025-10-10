<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class CheckAuthSettings extends Command
{
    protected $signature = 'supabase:check-auth';
    protected $description = 'Check Supabase authentication settings';

    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        parent::__construct();
        $this->supabase = $supabase;
    }

    public function handle()
    {
        $this->info('🔍 Checking Supabase authentication settings...');
        
        // Test registration with a new user
        $email = 'authtest_' . time() . '@outlook.com';
        $password = 'TestPassword123!';
        
        $this->info("Testing registration with: {$email}");
        
        $result = $this->supabase->signUp($email, $password, ['name' => 'Auth Test']);
        
        if ($result['success']) {
            $this->info('✅ Registration successful');
            
            $user = $result['data'];
            
            $this->info('User details:');
            $this->line("- Email: {$user['email']}");
            $this->line("- ID: {$user['id']}");
            $this->line("- Email confirmed: " . (isset($user['email_confirmed_at']) && $user['email_confirmed_at'] ? 'Yes' : 'No'));
            $this->line("- Confirmation sent: " . ($user['confirmation_sent_at'] ?? 'N/A'));
            
            if (!isset($user['email_confirmed_at']) || !$user['email_confirmed_at']) {
                $this->warn('⚠️ Email confirmation is required');
                $this->info('💡 To disable email confirmation:');
                $this->line('1. Go to your Supabase dashboard');
                $this->line('2. Navigate to Authentication > Settings');
                $this->line('3. Disable "Enable email confirmations"');
                $this->line('4. Or add your domain to "Site URL" for auto-confirmation');
                
                $this->info('');
                $this->info('🧪 Testing login without confirmation...');
                
                $loginResult = $this->supabase->signIn($email, $password);
                
                if ($loginResult['success']) {
                    $this->info('✅ Login successful even without confirmation');
                } else {
                    $this->error('❌ Login failed - confirmation required');
                    $this->line('Error: ' . json_encode($loginResult['data'], JSON_PRETTY_PRINT));
                }
            } else {
                $this->info('✅ Email confirmation is disabled - users can login immediately');
            }
        } else {
            $this->error('❌ Registration failed');
            $this->line(json_encode($result, JSON_PRETTY_PRINT));
        }
    }
}