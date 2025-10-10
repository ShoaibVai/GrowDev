<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DisableEmailConfirmation extends Command
{
    protected $signature = 'supabase:disable-email-confirmation';
    protected $description = 'Attempt to disable email confirmation via API (may require manual dashboard action)';

    public function handle()
    {
        $this->warn('⚠️ Email confirmation settings usually need to be changed manually in the Supabase dashboard.');
        $this->info('');
        $this->info('📋 To disable email confirmation:');
        $this->info('');
        $this->info('1. Go to: https://app.supabase.com');
        $this->info('2. Select your project: ' . config('services.supabase.url'));
        $this->info('3. Navigate to: Authentication → Settings');
        $this->info('4. Find: "Enable email confirmations"');
        $this->info('5. Toggle it OFF');
        $this->info('6. Save settings');
        $this->info('');
        $this->info('✅ After disabling, users can login immediately after registration!');
        $this->info('');
        
        $continue = $this->confirm('Have you disabled email confirmation in the dashboard?');
        
        if ($continue) {
            $this->info('🧪 Testing registration and login flow...');
            
            // Test the flow
            $email = 'quicktest_' . time() . '@outlook.com';
            $password = 'TestPassword123!';
            
            $this->call('debug:registration', ['email' => $email, 'password' => $password]);
        }
    }
}