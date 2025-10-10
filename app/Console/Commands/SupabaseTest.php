<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class SupabaseTest extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'supabase:test 
                            {--auth : Test authentication functionality}
                            {--db : Test database operations}
                            {--all : Test all functionality}';

    /**
     * The console command description.
     */
    protected $description = 'Test Supabase connection, authentication, and database operations';

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
        $this->info('🧪 Testing Supabase functionality...');
        $this->newLine();

        $testAuth = $this->option('auth') || $this->option('all');
        $testDb = $this->option('db') || $this->option('all');
        
        if (!$testAuth && !$testDb) {
            $testAuth = $testDb = true; // Test all if no specific option
        }

        // Test connection
        $this->testConnection();

        if ($testDb) {
            $this->testDatabase();
        }

        if ($testAuth) {
            $this->testAuthentication();
        }

        $this->newLine();
        $this->info('🎉 Testing completed!');

        return 0;
    }

    private function testConnection()
    {
        $this->info('📡 Testing Supabase connection...');
        
        $result = $this->supabase->testConnection();
        
        if ($result['success']) {
            $this->info('✅ Connection successful');
            $this->line('   Status: ' . $result['status']);
        } else {
            $this->error('❌ Connection failed: ' . $result['error']);
        }
        
        $this->newLine();
    }

    private function testDatabase()
    {
        $this->info('🗄️  Testing database operations...');

        // Test schema info
        $schemaInfo = $this->supabase->getSchemaInfo();
        
        $tables = ['profiles', 'projects', 'project_members', 'tasks'];
        
        foreach ($tables as $table) {
            if (isset($schemaInfo[$table]) && $schemaInfo[$table]['exists']) {
                $this->info("✅ Table '{$table}' exists");
            } else {
                $this->warn("⚠️  Table '{$table}' not found");
            }
        }
        
        $this->newLine();
    }

    private function testAuthentication()
    {
        $this->info('🔐 Testing authentication...');

        // Test user listing (admin function)
        $this->info('👥 Testing user listing...');
        $usersResult = $this->supabase->listUsers();
        
        if ($usersResult['success']) {
            $users = $usersResult['data']['users'] ?? [];
            $this->info('✅ Users listed successfully');
            $this->line('   Total users: ' . count($users));
            
            if (!empty($users)) {
                $this->line('   Sample user: ' . ($users[0]['email'] ?? 'No email'));
            }
        } else {
            $this->error('❌ Failed to list users: ' . ($usersResult['error'] ?? 'Unknown error'));
        }

        $this->newLine();

        // Test creating a test user (optional)
        if ($this->confirm('Do you want to test user creation? (This will create a test user)')) {
            $this->testUserCreation();
        }
    }

    private function testUserCreation()
    {
        $testEmail = 'test_' . time() . '@example.com';
        $testPassword = 'TestPassword123!';

        $this->info("👤 Creating test user: {$testEmail}");

        $result = $this->supabase->signUp($testEmail, $testPassword, [
            'name' => 'Test User',
            'test_account' => true
        ]);

        $this->line('Debug - Result: ' . json_encode($result, JSON_PRETTY_PRINT));

        if ($result['success']) {
            $this->info('✅ Test user created successfully');
            
            $userData = $result['data'];
            if (isset($userData['user']['id'])) {
                $userId = $userData['user']['id'];
                $this->line("   User ID: {$userId}");
                
                // Test profile creation
                $profileResult = $this->supabase->createProfile($userId, [
                    'name' => 'Test User',
                    'email' => $testEmail,
                    'role' => 'user'
                ]);
                
                if ($profileResult['success']) {
                    $this->info('✅ User profile created successfully');
                } else {
                    $this->warn('⚠️  Profile creation failed: ' . ($profileResult['error'] ?? 'Unknown error'));
                }

                // Clean up test user
                if ($this->confirm('Delete the test user?')) {
                    $deleteResult = $this->supabase->deleteUser($userId);
                    if ($deleteResult['success']) {
                        $this->info('✅ Test user deleted successfully');
                    } else {
                        $this->warn('⚠️  Failed to delete test user: ' . ($deleteResult['error'] ?? 'Unknown error'));
                    }
                }
            }
        } else {
            $this->error('❌ Test user creation failed: ' . ($result['error'] ?? 'Unknown error'));
        }
    }
}