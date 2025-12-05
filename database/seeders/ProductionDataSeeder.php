<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Task;
use App\Models\Role;
use App\Models\Invitation;
use App\Models\SrsDocument;
use App\Models\SrsFunctionalRequirement;
use App\Models\SrsNonFunctionalRequirement;

/**
 * Production Data Seeder
 * 
 * This seeder preserves real user data from the development database.
 * Generated on: 2025-12-05
 * 
 * Run with: php artisan db:seed --class=ProductionDataSeeder
 * 
 * NOTE: This seeder uses pre-hashed passwords and TOTP secrets.
 * Users can log in with their existing credentials.
 */
class ProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedTeams();
        $this->seedTeamMembers();
        $this->seedInvitations();
        $this->seedProjects();
        $this->seedTasks();
        $this->seedSrsDocuments();
        $this->seedSrsFunctionalRequirements();
        $this->seedSrsNonFunctionalRequirements();
    }

    /**
     * Seed users with their exact passwords and TOTP secrets
     */
    private function seedUsers(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => '$2y$12$t4fp..Bja8PNYwVEvB7/1u/d4r8VFwfm8TxZX.bgdR95w.mJir2ZO',
                'totp_secret' => null,
                'email_verified_at' => now(),
                'created_at' => '2025-12-05 05:41:57',
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'ss',
                'email' => 'ss@gmail.com',
                'password' => '$2y$12$dhaxyLUYrglaLDAXCGu7butDyP5oh5Hfj8E/pfJHPX0v.DePoR3EG',
                'totp_secret' => 'XZ23TJFSRLM3EQUV',
                'email_verified_at' => now(),
                'created_at' => '2025-12-05 05:43:15',
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'dd',
                'email' => 'dd@gmail.com',
                'password' => '$2y$12$LrReJyASf/OBFqS4NeW6zOTKITIWpiOq2FY4B8kzTty/h1F/8D4ku',
                'totp_secret' => 'OZ5V5LCILPOQVYOH',
                'email_verified_at' => now(),
                'created_at' => '2025-12-05 05:43:23',
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['id' => $userData['id']], $userData);
        }

        $this->command->info('✓ Seeded ' . count($users) . ' users');
    }

    /**
     * Seed teams
     */
    private function seedTeams(): void
    {
        $teams = [
            [
                'id' => 1,
                'name' => "Test User's Team",
                'description' => 'A default team for initial seeding',
                'owner_id' => 1,
                'created_at' => '2025-12-05 05:41:57',
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'team1',
                'description' => null,
                'owner_id' => 2,
                'created_at' => '2025-12-05 05:44:16',
                'updated_at' => now(),
            ],
        ];

        foreach ($teams as $teamData) {
            Team::updateOrCreate(['id' => $teamData['id']], $teamData);
        }

        $this->command->info('✓ Seeded ' . count($teams) . ' teams');
    }

    /**
     * Seed team members (pivot table)
     */
    private function seedTeamMembers(): void
    {
        $teamMembers = [
            ['team_id' => 1, 'user_id' => 1],
            ['team_id' => 2, 'user_id' => 2],
            ['team_id' => 2, 'user_id' => 3],
        ];

        foreach ($teamMembers as $member) {
            DB::table('team_user')->updateOrInsert(
                ['team_id' => $member['team_id'], 'user_id' => $member['user_id']],
                $member
            );
        }

        $this->command->info('✓ Seeded ' . count($teamMembers) . ' team memberships');
    }

    /**
     * Seed invitations (only accepted ones are important for reference)
     */
    private function seedInvitations(): void
    {
        // We only seed the accepted invitation as the declined ones are just history
        $invitations = [
            [
                'id' => 3,
                'team_id' => 2,
                'email' => 'dd@gmail.com',
                'token' => 'HLMQtm4eqOHZcoWUUM7MQO4tYJEeScBt5JFHQ37C',
                'status' => 'accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($invitations as $invData) {
            Invitation::updateOrCreate(['id' => $invData['id']], $invData);
        }

        $this->command->info('✓ Seeded ' . count($invitations) . ' invitations');
    }

    /**
     * Seed projects
     */
    private function seedProjects(): void
    {
        $projects = [
            [
                'id' => 1,
                'name' => 'project1',
                'description' => 'ss',
                'team_id' => 2,
                'user_id' => 2, // Created by user "ss"
                'created_at' => '2025-12-05 05:47:37',
                'updated_at' => now(),
            ],
        ];

        foreach ($projects as $projectData) {
            Project::updateOrCreate(['id' => $projectData['id']], $projectData);
        }

        $this->command->info('✓ Seeded ' . count($projects) . ' projects');
    }

    /**
     * Seed tasks
     */
    private function seedTasks(): void
    {
        $tasks = [
            [
                'id' => 1,
                'title' => 'ss',
                'description' => null,
                'status' => 'To Do',
                'priority' => 'Medium',
                'project_id' => 1,
                'assigned_to' => 3,
                'due_date' => '2025-12-23',
                'created_at' => '2025-12-05 05:48:42',
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'dd',
                'description' => null,
                'status' => 'To Do',
                'priority' => 'Medium',
                'project_id' => 1,
                'assigned_to' => 3,
                'due_date' => '2025-12-31',
                'created_at' => '2025-12-05 05:48:57',
                'updated_at' => now(),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::updateOrCreate(['id' => $taskData['id']], $taskData);
        }

        $this->command->info('✓ Seeded ' . count($tasks) . ' tasks');
    }

    /**
     * Seed SRS documents
     */
    private function seedSrsDocuments(): void
    {
        $documents = [
            [
                'id' => 1,
                'project_id' => 1,
                'user_id' => 2, // Created by user "ss"
                'title' => 'ss',
                'version' => '1.0',
                'purpose' => 'ss',
                'references' => 'ss',
                'product_perspective' => 'ss',
                'product_features' => 'ss',
                'user_classes' => 'ss',
                'operating_environment' => 'ss',
                'constraints' => 's',
                'assumptions' => 'ss',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($documents as $docData) {
            SrsDocument::updateOrCreate(['id' => $docData['id']], $docData);
        }

        $this->command->info('✓ Seeded ' . count($documents) . ' SRS documents');
    }

    /**
     * Seed SRS functional requirements
     */
    private function seedSrsFunctionalRequirements(): void
    {
        $requirements = [
            [
                'id' => 1,
                'srs_document_id' => 1,
                'requirement_id' => 'FR-4.1',
                'section_number' => '4.1',
                'title' => 'title1',
                'description' => 'ss',
                'priority' => 'medium',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($requirements as $reqData) {
            SrsFunctionalRequirement::updateOrCreate(['id' => $reqData['id']], $reqData);
        }

        $this->command->info('✓ Seeded ' . count($requirements) . ' functional requirements');
    }

    /**
     * Seed SRS non-functional requirements
     */
    private function seedSrsNonFunctionalRequirements(): void
    {
        $requirements = [
            [
                'id' => 1,
                'srs_document_id' => 1,
                'requirement_id' => 'NFR-5.1',
                'section_number' => '5.1',
                'category' => 'performance',
                'title' => 'title2',
                'description' => 'ss',
                'priority' => 'medium',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($requirements as $reqData) {
            SrsNonFunctionalRequirement::updateOrCreate(['id' => $reqData['id']], $reqData);
        }

        $this->command->info('✓ Seeded ' . count($requirements) . ' non-functional requirements');
    }
}
