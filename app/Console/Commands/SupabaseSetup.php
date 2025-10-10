<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class SupabaseSetup extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'supabase:setup 
                            {--force : Force recreation of existing schema}
                            {--seed : Seed initial data after setup}';

    /**
     * The console command description.
     */
    protected $description = 'Set up complete Supabase database schema with tables, RLS policies, and triggers';

    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        parent::__construct();
        $this->supabase = $supabase;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up Supabase database schema...');
        $this->newLine();

        // Test connection first
        $this->info('📡 Testing Supabase connection...');
        $connection = $this->supabase->testConnection();
        
        if (!$connection['success']) {
            $this->error('❌ Failed to connect to Supabase: ' . $connection['error']);
            return 1;
        }
        
        $this->info('✅ Supabase connection successful');
        $this->newLine();

        // Check existing schema
        if (!$this->option('force')) {
            $this->info('🔍 Checking existing schema...');
            $schemaInfo = $this->supabase->getSchemaInfo();
            
            $existingTables = array_filter($schemaInfo, function($info) {
                return $info['exists'] ?? false;
            });

            if (!empty($existingTables)) {
                $this->warn('⚠️  Existing tables found: ' . implode(', ', array_keys($existingTables)));
                
                if (!$this->confirm('Do you want to continue? This may overwrite existing data.')) {
                    $this->info('Operation cancelled.');
                    return 0;
                }
            }
        }

        // Create schema
        $this->info('🏗️  Creating database schema...');
        $this->withProgressBar(range(1, 5), function ($i) {
            sleep(1); // Simulate work
        });
        $this->newLine(2);

        $results = $this->supabase->createSchema();
        
        $successCount = 0;
        $errorCount = 0;

        foreach ($results as $result) {
            if ($result['success']) {
                $successCount++;
                $this->line('✅ ' . $result['sql']);
            } else {
                $errorCount++;
                $this->line('❌ ' . $result['sql']);
                if ($result['error']) {
                    $this->line('   Error: ' . $result['error']);
                }
            }
        }

        $this->newLine();
        $this->info("📊 Schema creation completed: {$successCount} successful, {$errorCount} failed");

        // Seed data if requested
        if ($this->option('seed')) {
            $this->newLine();
            $this->info('🌱 Seeding initial data...');
            $seedResult = $this->supabase->seedData();
            
            if ($seedResult['success']) {
                $this->info('✅ ' . $seedResult['message']);
            } else {
                $this->error('❌ Seeding failed: ' . ($seedResult['error'] ?? 'Unknown error'));
            }
        }

        $this->newLine();
        $this->info('🎉 Supabase setup completed!');
        $this->newLine();

        // Display next steps
        $this->displayNextSteps();

        return 0;
    }

    private function displayNextSteps()
    {
        $this->info('📋 Next Steps:');
        $this->line('1. Configure your site URL in Supabase dashboard');
        $this->line('2. Set up authentication redirects');
        $this->line('3. Test user registration and login');
        $this->line('4. Configure email templates');
        $this->newLine();
        
        $this->info('🔧 Available Commands:');
        $this->line('• php artisan supabase:test - Test all functionality');
        $this->line('• php artisan supabase:users - List all users');
        $this->line('• php artisan supabase:migrate - Run migrations');
        $this->line('• php artisan supabase:status - Check database status');
    }
}