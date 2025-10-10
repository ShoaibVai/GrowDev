<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EmailConfirmationGuide extends Command
{
    protected $signature = 'guide:email-confirmation';
    protected $description = 'Complete guide to fix email confirmation URLs';

    public function handle()
    {
        $this->info('📧 EMAIL CONFIRMATION URL FIX GUIDE');
        $this->info('=====================================');
        $this->info('');
        
        $this->warn('🔍 PROBLEM IDENTIFIED:');
        $this->line('- Confirmation URLs point to localhost:3000 (wrong)');
        $this->line('- Should point to localhost:8000 (your Laravel app)');
        $this->info('');
        
        $this->info('🛠️ SOLUTION - Update Supabase Dashboard:');
        $this->info('');
        $this->info('1. Go to: https://app.supabase.com');
        $this->info('2. Select your project: bwrxvijpmhnuevdrtxcy');
        $this->info('3. Navigate to: Authentication → Settings');
        $this->info('4. Find: "Site URL" section');
        $this->info('5. Change from: http://localhost:3000');
        $this->info('6. Change to: http://localhost:8000');
        $this->info('7. Save settings');
        $this->info('');
        
        $this->info('🔗 ADDITIONAL REDIRECT URLs TO ADD:');
        $this->line('Add these to "Redirect URLs" section:');
        $this->line('- http://localhost:8000/auth/confirm');
        $this->line('- http://localhost:8000/auth/callback');  
        $this->line('- http://localhost:8000/login');
        $this->line('- http://localhost:8000/dashboard');
        $this->info('');
        
        $this->info('✅ WHAT I\'VE ALREADY FIXED IN YOUR CODE:');
        $this->line('- ✅ Created EmailConfirmationController');
        $this->line('- ✅ Added confirmation routes:');
        $this->line('    • GET /auth/confirm - Handles email confirmation');
        $this->line('    • GET /auth/callback - Alternative callback');
        $this->line('    • GET /email/verify - Confirmation notice page');
        $this->line('    • POST /email/resend - Resend confirmation');
        $this->line('- ✅ Created ConfirmEmail.vue page');
        $this->line('- ✅ Added proper error handling');
        $this->info('');
        
        $this->info('🧪 TESTING FLOW:');
        $this->line('After updating Supabase dashboard:');
        $this->line('1. Register a new user at /register');
        $this->line('2. Check email for confirmation link');
        $this->line('3. Click confirmation link (should go to localhost:8000)');
        $this->line('4. Get redirected to /login with success message');
        $this->line('5. Login successfully');
        $this->info('');
        
        $this->info('⚡ QUICK TEST:');
        if ($this->confirm('Want me to test the registration flow now?')) {
            $this->call('debug:registration', ['email' => 'urlfix_' . time() . '@outlook.com']);
        }
        
        $this->info('');
        $this->info('🎯 SUMMARY:');
        $this->line('Your Laravel app is ready to handle email confirmations.');
        $this->line('Just update the Site URL in Supabase dashboard!');
    }
}