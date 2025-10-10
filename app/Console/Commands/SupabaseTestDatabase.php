<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseServiceEnhanced;

class SupabaseTestDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'supabase:test-database 
                            {--create-sample : Create sample data after schema}';

    /**
     * The console command description.
     */
    protected $description = 'Test database operations and create sample data if schema exists';

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
        $this->info('🗄️  SUPABASE DATABASE TEST');
        $this->info('========================');
        $this->newLine();

        // Check schema first
        $this->checkSchema();

        // If user wants to create sample data and tables exist
        if ($this->option('create-sample')) {
            $this->createSampleData();
        }

        return 0;
    }

    private function checkSchema()
    {
        $this->info('📋 Checking Database Schema...');
        
        $schemaInfo = $this->supabase->getSchemaInfo();
        $expectedTables = ['profiles', 'projects', 'project_members', 'tasks'];
        $existingTables = [];
        
        foreach ($expectedTables as $table) {
            if (isset($schemaInfo[$table]) && ($schemaInfo[$table]['exists'] ?? false)) {
                $existingTables[] = $table;
                $this->info("✅ Table '{$table}' exists");
            } else {
                $this->warn("⚠️  Table '{$table}' missing");
            }
        }
        
        $this->newLine();
        
        if (empty($existingTables)) {
            $this->error('❌ No tables found! Database schema needs to be created.');
            $this->newLine();
            $this->info('🔧 To create the database schema:');
            $this->line('1. Open Supabase Dashboard');
            $this->line('2. Go to SQL Editor');
            $this->line('3. Copy content from: database/supabase-schema.sql');
            $this->line('4. Run the SQL script');
            $this->line('5. Re-run this test');
        } else {
            $existingCount = count($existingTables);
            $totalCount = count($expectedTables);
            $this->info("✅ Found {$existingCount}/{$totalCount} tables");
        }
        
        $this->newLine();
    }

    private function createSampleData()
    {
        $this->info('🌱 Creating Sample Data...');
        
        // This would require the schema to exist first
        // For now, just show what would be created
        
        $sampleData = [
            'users' => [
                ['email' => 'admin@example.com', 'role' => 'admin'],
                ['email' => 'manager@example.com', 'role' => 'manager'],
                ['email' => 'user@example.com', 'role' => 'user'],
            ],
            'projects' => [
                ['name' => 'Sample Project 1', 'description' => 'First sample project'],
                ['name' => 'Sample Project 2', 'description' => 'Second sample project'],
            ],
            'tasks' => [
                ['title' => 'Setup Authentication', 'status' => 'done'],
                ['title' => 'Create Database Schema', 'status' => 'done'],
                ['title' => 'Build Frontend', 'status' => 'in_progress'],
            ]
        ];
        
        $this->line('📊 Sample data that would be created:');
        foreach ($sampleData as $type => $items) {
            $this->line("   {$type}: " . count($items) . " items");
        }
        
        $this->newLine();
        $this->warn('⚠️  Sample data creation requires schema to exist first.');
        $this->info('Run the SQL script in Supabase dashboard to enable this feature.');
    }
}