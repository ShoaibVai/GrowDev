<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * 
     * Uses ProductionDataSeeder to restore real user data including:
     * - Users with their original passwords and TOTP secrets
     * - Teams and team memberships
     * - Projects, tasks, and SRS documents
     * 
     * Run: php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        // Seed documentation templates first (required for SRS creation)
        $this->call(DocumentationTemplateSeeder::class);

        // Seed system roles for AI task generation
        $this->call(SystemRolesSeeder::class);

        // Seed production data (users, teams, projects, tasks, etc.)
        $this->call(ProductionDataSeeder::class);
    }
}
