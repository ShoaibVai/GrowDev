<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class SupabaseUsers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'supabase:users 
                            {--list : List all users}
                            {--delete= : Delete user by ID}
                            {--create : Create a new user interactively}
                            {--stats : Show user statistics}';

    /**
     * The console command description.
     */
    protected $description = 'Manage Supabase users - list, create, delete, and show statistics';

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
        if ($this->option('list')) {
            return $this->listUsers();
        }

        if ($this->option('delete')) {
            return $this->deleteUser($this->option('delete'));
        }

        if ($this->option('create')) {
            return $this->createUser();
        }

        if ($this->option('stats')) {
            return $this->showStats();
        }

        // Default action - show menu
        $this->showMenu();
        return 0;
    }

    private function showMenu()
    {
        $this->info('👥 Supabase User Management');
        $this->newLine();

        $choice = $this->choice(
            'What would you like to do?',
            [
                'list' => 'List all users',
                'create' => 'Create new user',
                'stats' => 'Show statistics',
                'exit' => 'Exit'
            ],
            'list'
        );

        switch ($choice) {
            case 'list':
                $this->listUsers();
                break;
            case 'create':
                $this->createUser();
                break;
            case 'stats':
                $this->showStats();
                break;
            case 'exit':
                $this->info('Goodbye!');
                break;
        }
    }

    private function listUsers()
    {
        $this->info('📋 Fetching users from Supabase...');
        
        $result = $this->supabase->listUsers();
        
        if (!$result['success']) {
            $this->error('❌ Failed to fetch users: ' . $result['error']);
            return 1;
        }

        $users = $result['data']['users'] ?? [];
        
        if (empty($users)) {
            $this->info('📭 No users found');
            return 0;
        }

        $this->info('👥 Found ' . count($users) . ' users:');
        $this->newLine();

        $headers = ['ID', 'Email', 'Created', 'Last Sign In', 'Confirmed'];
        $rows = [];

        foreach ($users as $user) {
            $rows[] = [
                substr($user['id'], 0, 8) . '...',
                $user['email'] ?? 'N/A',
                isset($user['created_at']) ? date('Y-m-d H:i', strtotime($user['created_at'])) : 'N/A',
                isset($user['last_sign_in_at']) ? date('Y-m-d H:i', strtotime($user['last_sign_in_at'])) : 'Never',
                isset($user['email_confirmed_at']) ? '✅' : '❌'
            ];
        }

        $this->table($headers, $rows);

        // Ask if user wants to perform actions
        $this->newLine();
        if ($this->confirm('Would you like to delete a user?')) {
            $userId = $this->ask('Enter user ID (full ID)');
            if ($userId) {
                $this->deleteUser($userId);
            }
        }

        return 0;
    }

    private function deleteUser($userId)
    {
        $this->warn("🗑️  Deleting user: {$userId}");
        
        if (!$this->confirm('Are you sure? This action cannot be undone.')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $result = $this->supabase->deleteUser($userId);
        
        if ($result['success']) {
            $this->info('✅ User deleted successfully');
        } else {
            $this->error('❌ Failed to delete user: ' . $result['error']);
            return 1;
        }

        return 0;
    }

    private function createUser()
    {
        $this->info('👤 Creating new user...');
        $this->newLine();

        $email = $this->ask('Email address');
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Invalid email address');
            return 1;
        }

        $password = $this->secret('Password (min 6 characters)');
        if (!$password || strlen($password) < 6) {
            $this->error('❌ Password must be at least 6 characters');
            return 1;
        }

        $name = $this->ask('Full name (optional)');
        $role = $this->choice('Role', ['user', 'admin', 'manager'], 'user');

        $metadata = [];
        if ($name) {
            $metadata['name'] = $name;
        }
        $metadata['role'] = $role;

        $this->info('Creating user...');
        
        $result = $this->supabase->signUp($email, $password, $metadata);
        
        if ($result['success']) {
            $this->info('✅ User created successfully');
            
            $userData = $result['data'];
            if (isset($userData['user']['id'])) {
                $userId = $userData['user']['id'];
                $this->line("User ID: {$userId}");
                
                // Create profile
                $profileResult = $this->supabase->createProfile($userId, [
                    'name' => $name ?: explode('@', $email)[0],
                    'email' => $email,
                    'role' => $role
                ]);
                
                if ($profileResult['success']) {
                    $this->info('✅ User profile created');
                } else {
                    $this->warn('⚠️  Profile creation failed: ' . $profileResult['error']);
                }
            }
        } else {
            $this->error('❌ User creation failed: ' . $result['error']);
            return 1;
        }

        return 0;
    }

    private function showStats()
    {
        $this->info('📊 Fetching user statistics...');
        
        $result = $this->supabase->listUsers();
        
        if (!$result['success']) {
            $this->error('❌ Failed to fetch users: ' . $result['error']);
            return 1;
        }

        $users = $result['data']['users'] ?? [];
        
        $totalUsers = count($users);
        $confirmedUsers = count(array_filter($users, function($user) {
            return isset($user['email_confirmed_at']);
        }));
        $recentUsers = count(array_filter($users, function($user) {
            return isset($user['created_at']) && 
                   strtotime($user['created_at']) > strtotime('-7 days');
        }));
        
        $this->info('📈 User Statistics:');
        $this->newLine();
        
        $this->line("👥 Total Users: {$totalUsers}");
        $this->line("✅ Confirmed Users: {$confirmedUsers}");
        $this->line("📅 New Users (7 days): {$recentUsers}");
        $this->line("📧 Unconfirmed Users: " . ($totalUsers - $confirmedUsers));
        
        if ($totalUsers > 0) {
            $confirmationRate = round(($confirmedUsers / $totalUsers) * 100, 1);
            $this->line("📊 Confirmation Rate: {$confirmationRate}%");
        }

        return 0;
    }
}