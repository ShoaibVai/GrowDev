<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class SupabaseStatus extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'supabase:status 
                            {--detailed : Show detailed information}
                            {--health : Check service health}';

    /**
     * The console command description.
     */
    protected $description = 'Check Supabase connection status and database health';

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
        $this->info('🔍 Checking Supabase status...');
        $this->newLine();

        // Basic connection test
        $this->checkConnection();

        // Database schema check
        $this->checkSchema();

        // Service health check
        if ($this->option('health')) {
            $this->checkHealth();
        }

        // Detailed information
        if ($this->option('detailed')) {
            $this->showDetailedInfo();
        }

        return 0;
    }

    private function checkConnection()
    {
        $this->info('📡 Testing connection...');
        
        $result = $this->supabase->testConnection();
        
        if ($result['success']) {
            $this->info('✅ Connection successful');
            $this->line('   Status: ' . $result['status']);
        } else {
            $this->error('❌ Connection failed');
            $this->line('   Error: ' . $result['error']);
        }
        
        $this->newLine();
    }

    private function checkSchema()
    {
        $this->info('🗄️  Checking database schema...');
        
        $schemaInfo = $this->supabase->getSchemaInfo();
        
        $expectedTables = ['profiles', 'projects', 'project_members', 'tasks'];
        $existingTables = [];
        $missingTables = [];

        foreach ($expectedTables as $table) {
            if (isset($schemaInfo[$table]) && $schemaInfo[$table]['exists']) {
                $existingTables[] = $table;
                $this->info("✅ Table '{$table}' exists");
            } else {
                $missingTables[] = $table;
                $this->warn("⚠️  Table '{$table}' missing");
            }
        }

        $this->newLine();
        $this->line('📊 Schema Summary:');
        $this->line('   Existing tables: ' . count($existingTables) . '/' . count($expectedTables));
        
        if (!empty($missingTables)) {
            $this->line('   Missing tables: ' . implode(', ', $missingTables));
            $this->newLine();
            $this->warn('💡 Run "php artisan supabase:setup" to create missing tables');
        }
        
        $this->newLine();
    }

    private function checkHealth()
    {
        $this->info('🏥 Checking service health...');

        // Test authentication endpoints
        $this->line('🔐 Authentication service...');
        $usersResult = $this->supabase->listUsers();
        
        if ($usersResult['success']) {
            $userCount = count($usersResult['data']['users'] ?? []);
            $this->info("✅ Auth service healthy ({$userCount} users)");
        } else {
            $this->error('❌ Auth service issue: ' . $usersResult['error']);
        }

        // Test database queries
        $this->line('🗄️  Database service...');
        $schemaInfo = $this->supabase->getSchemaInfo();
        $workingTables = array_filter($schemaInfo, function($info) {
            return $info['exists'] ?? false;
        });

        if (!empty($workingTables)) {
            $this->info('✅ Database service healthy (' . count($workingTables) . ' tables accessible)');
        } else {
            $this->warn('⚠️  Database service may have issues');
        }

        $this->newLine();
    }

    private function showDetailedInfo()
    {
        $this->info('📋 Detailed Information:');
        $this->newLine();

        // Environment info
        $this->line('🌍 Environment:');
        $this->line('   Supabase URL: ' . config('services.supabase.url'));
        $this->line('   Anon Key: ' . substr(config('services.supabase.anon_key'), 0, 20) . '...');
        $this->line('   Service Key: ' . (config('services.supabase.service_role_key') ? 'Configured' : 'Not configured'));
        $this->newLine();

        // Get user statistics
        $usersResult = $this->supabase->listUsers();
        if ($usersResult['success']) {
            $users = $usersResult['data']['users'] ?? [];
            
            $this->line('👥 User Statistics:');
            $this->line('   Total users: ' . count($users));
            
            $confirmedUsers = array_filter($users, function($user) {
                return isset($user['email_confirmed_at']);
            });
            $this->line('   Confirmed users: ' . count($confirmedUsers));
            
            $recentUsers = array_filter($users, function($user) {
                return isset($user['created_at']) && 
                       strtotime($user['created_at']) > strtotime('-24 hours');
            });
            $this->line('   Recent signups (24h): ' . count($recentUsers));
        }

        $this->newLine();

        // Schema details
        $schemaInfo = $this->supabase->getSchemaInfo();
        $this->line('🗄️  Schema Details:');
        foreach ($schemaInfo as $table => $info) {
            $status = $info['exists'] ? '✅' : '❌';
            $this->line("   {$status} {$table}");
        }

        $this->newLine();
    }
}