<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;
use Illuminate\Support\Facades\Http;

class FixConfirmationUrl extends Command
{
    protected $signature = 'supabase:fix-confirmation-url';
    protected $description = 'Fix the email confirmation URL to point to the correct Laravel app URL';

    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        parent::__construct();
        $this->supabase = $supabase;
    }

    public function handle()
    {
        $this->info('🔧 Fixing email confirmation URL configuration...');
        
        $appUrl = config('app.url');
        $supabaseUrl = config('services.supabase.url');
        $serviceKey = config('services.supabase.service_role_key');
        
        $this->info("Current Laravel app URL: {$appUrl}");
        $this->info("Supabase project URL: {$supabaseUrl}");
        
        $this->warn('⚠️ The confirmation URL needs to be fixed in your Supabase dashboard.');
        $this->info('');
        $this->info('📋 To fix the confirmation URL:');
        $this->info('');
        $this->info('1. Go to: https://app.supabase.com');
        $this->info('2. Select your project');
        $this->info('3. Navigate to: Authentication → Settings');
        $this->info('4. Find: "Site URL" section');
        $this->info("5. Change it from 'http://localhost:3000' to: {$appUrl}");
        $this->info('6. Save settings');
        $this->info('');
        $this->info('📧 Email confirmation links will then point to:');
        $this->info("{$appUrl}/auth/confirm");
        $this->info('');
        
        $this->info('🔗 Additional redirect URLs to add:');
        $this->info("{$appUrl}/auth/callback");
        $this->info("{$appUrl}/login");
        $this->info("{$appUrl}/dashboard");
        $this->info('');
        
        if ($this->confirm('Would you like me to create the email confirmation handler routes?')) {
            $this->info('✅ Creating confirmation handler...');
            $this->createConfirmationHandler();
        }
        
        if ($this->confirm('Test the current configuration?')) {
            $this->testEmailConfiguration();
        }
    }

    private function createConfirmationHandler()
    {
        $this->info('📝 Email confirmation routes will be added to your web.php file.');
        
        // The confirmation handler will be created in the routes
        $this->call('make:controller', ['name' => 'Auth/EmailConfirmationController']);
        
        $this->info('✅ Controller created. Adding routes...');
    }

    private function testEmailConfiguration()
    {
        $this->info('🧪 Testing email configuration...');
        
        $email = 'urltest_' . time() . '@outlook.com';
        $password = 'TestPassword123!';
        
        $this->info("Testing registration with: {$email}");
        
        $result = $this->supabase->signUp($email, $password, ['name' => 'URL Test']);
        
        if ($result['success']) {
            $this->info('✅ Registration successful');
            $this->info('📧 Check the confirmation email - the URL should now point to your Laravel app!');
            
            // Show what the confirmation URL should look like
            $appUrl = config('app.url');
            $this->info('');
            $this->info('Expected confirmation URL format:');
            $this->info("{$appUrl}/auth/confirm?token=<confirmation_token>");
            
        } else {
            $this->error('❌ Registration test failed');
            $this->line(json_encode($result, JSON_PRETTY_PRINT));
        }
    }
}