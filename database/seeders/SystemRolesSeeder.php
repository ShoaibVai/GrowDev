<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class SystemRolesSeeder extends Seeder
{
    /**
     * System-defined roles for project teams.
     * These roles are used by the AI task generation system to assign tasks.
     */
    public const SYSTEM_ROLES = [
        [
            'name' => 'Product Owner',
            'description' => 'Responsible for product vision, backlog prioritization, and stakeholder communication',
            'category' => 'management',
            'seniority_level' => null,
        ],
        [
            'name' => 'Scrum Master',
            'description' => 'Facilitates agile processes, removes blockers, and ensures team productivity',
            'category' => 'management',
            'seniority_level' => null,
        ],
        [
            'name' => 'UX/UI Designer',
            'description' => 'Creates user interfaces, wireframes, and ensures excellent user experience',
            'category' => 'design',
            'seniority_level' => null,
        ],
        [
            'name' => 'Frontend Developer',
            'description' => 'Builds user-facing features using HTML, CSS, JavaScript, and modern frameworks',
            'category' => 'development',
            'seniority_level' => null,
        ],
        [
            'name' => 'Backend Developer',
            'description' => 'Develops server-side logic, APIs, databases, and system architecture',
            'category' => 'development',
            'seniority_level' => null,
        ],
        [
            'name' => 'Full Stack Developer',
            'description' => 'Works on both frontend and backend development with end-to-end capabilities',
            'category' => 'development',
            'seniority_level' => null,
        ],
        [
            'name' => 'QA Engineer',
            'description' => 'Ensures software quality through testing, automation, and quality processes',
            'category' => 'quality',
            'seniority_level' => null,
        ],
        [
            'name' => 'DevOps Engineer',
            'description' => 'Manages CI/CD pipelines, infrastructure, deployment, and system reliability',
            'category' => 'operations',
            'seniority_level' => null,
        ],
        [
            'name' => 'Technical Writer',
            'description' => 'Creates documentation, user guides, API docs, and technical content',
            'category' => 'documentation',
            'seniority_level' => null,
        ],
        [
            'name' => 'Security Specialist',
            'description' => 'Ensures application security, conducts audits, and implements security best practices',
            'category' => 'security',
            'seniority_level' => null,
        ],
    ];

    /**
     * Seniority levels that can be applied to any role.
     */
    public const SENIORITY_LEVELS = [
        'junior' => 'Junior',
        'mid' => 'Mid-Level',
        'senior' => 'Senior',
        'lead' => 'Lead',
        'principal' => 'Principal',
    ];

    /**
     * Role categories for grouping.
     */
    public const ROLE_CATEGORIES = [
        'management' => 'Management',
        'development' => 'Development',
        'design' => 'Design',
        'quality' => 'Quality Assurance',
        'operations' => 'Operations',
        'documentation' => 'Documentation',
        'security' => 'Security',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::SYSTEM_ROLES as $roleData) {
            Role::updateOrCreate(
                [
                    'name' => $roleData['name'],
                    'is_system_role' => true,
                    'team_id' => null,
                    'project_id' => null,
                ],
                [
                    'description' => $roleData['description'],
                    'category' => $roleData['category'],
                    'seniority_level' => $roleData['seniority_level'],
                    'is_system_role' => true,
                ]
            );
        }

        $this->command->info('System roles seeded successfully.');
    }

    /**
     * Get all system role names.
     */
    public static function getSystemRoleNames(): array
    {
        return array_column(self::SYSTEM_ROLES, 'name');
    }

    /**
     * Get roles by category.
     */
    public static function getRolesByCategory(string $category): array
    {
        return array_filter(self::SYSTEM_ROLES, fn($role) => $role['category'] === $category);
    }
}
