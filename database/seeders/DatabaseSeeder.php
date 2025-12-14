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
     * Uses ShowcaseDataSeeder to create comprehensive demo data including:
     * - Multiple teams with diverse users and roles
     * - Real-world projects with detailed descriptions
     * - Tasks with various statuses and priorities
     * - Complete SRS documentation with requirements
     * - User profiles with skills and experience
     * - Project diagrams and technical documentation
     * 
     * Run: php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        // Seed documentation templates first (required for SRS creation)
        $this->call(DocumentationTemplateSeeder::class);

        // Seed system roles for AI task generation
        $this->call(SystemRolesSeeder::class);

        // Seed Admin User
        $this->call(AdminUserSeeder::class);

        // Seed comprehensive showcase data
        $this->call(ShowcaseDataSeeder::class);
    }
}
