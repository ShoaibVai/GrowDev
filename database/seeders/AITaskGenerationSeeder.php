<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Role;
use App\Models\SrsDocument;
use App\Models\SrsFunctionalRequirement;
use App\Models\SrsNonFunctionalRequirement;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AITaskGenerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates comprehensive test data for AI task generation feature.
     */
    public function run(): void
    {
        $this->command->info('Starting AI Task Generation Seeder...');

        // First, seed system roles
        $this->call(SystemRolesSeeder::class);

        // Create users
        $users = $this->createUsers();
        $this->command->info('Created ' . count($users) . ' users.');

        // Create teams with role assignments
        $teams = $this->createTeams($users);
        $this->command->info('Created ' . count($teams) . ' teams.');

        // Create projects with SRS documents
        $projects = $this->createProjects($users, $teams);
        $this->command->info('Created ' . count($projects) . ' projects with SRS documents.');

        // Create sample tasks demonstrating role-based assignments
        $this->createSampleTasks($projects);
        $this->command->info('Created sample tasks with role-based assignments.');

        $this->command->info('AI Task Generation Seeder completed successfully!');
    }

    /**
     * Create 30+ individual user accounts.
     */
    protected function createUsers(): array
    {
        $usersData = [
            // Product Owners
            ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@example.com', 'role' => 'Product Owner'],
            ['name' => 'Michael Chen', 'email' => 'michael.chen@example.com', 'role' => 'Product Owner'],
            ['name' => 'Emily Davis', 'email' => 'emily.davis@example.com', 'role' => 'Product Owner'],
            
            // Scrum Masters
            ['name' => 'David Wilson', 'email' => 'david.wilson@example.com', 'role' => 'Scrum Master'],
            ['name' => 'Jessica Martinez', 'email' => 'jessica.martinez@example.com', 'role' => 'Scrum Master'],
            
            // UX/UI Designers
            ['name' => 'Amanda Lee', 'email' => 'amanda.lee@example.com', 'role' => 'UX/UI Designer'],
            ['name' => 'Ryan Thompson', 'email' => 'ryan.thompson@example.com', 'role' => 'UX/UI Designer'],
            ['name' => 'Sophia Garcia', 'email' => 'sophia.garcia@example.com', 'role' => 'UX/UI Designer'],
            
            // Frontend Developers
            ['name' => 'James Brown', 'email' => 'james.brown@example.com', 'role' => 'Frontend Developer'],
            ['name' => 'Olivia Taylor', 'email' => 'olivia.taylor@example.com', 'role' => 'Frontend Developer'],
            ['name' => 'Ethan Anderson', 'email' => 'ethan.anderson@example.com', 'role' => 'Frontend Developer'],
            ['name' => 'Emma White', 'email' => 'emma.white@example.com', 'role' => 'Frontend Developer'],
            
            // Backend Developers
            ['name' => 'Daniel Harris', 'email' => 'daniel.harris@example.com', 'role' => 'Backend Developer'],
            ['name' => 'Ava Robinson', 'email' => 'ava.robinson@example.com', 'role' => 'Backend Developer'],
            ['name' => 'Matthew Clark', 'email' => 'matthew.clark@example.com', 'role' => 'Backend Developer'],
            ['name' => 'Isabella Lewis', 'email' => 'isabella.lewis@example.com', 'role' => 'Backend Developer'],
            
            // Full Stack Developers
            ['name' => 'Alexander Walker', 'email' => 'alex.walker@example.com', 'role' => 'Full Stack Developer'],
            ['name' => 'Mia Hall', 'email' => 'mia.hall@example.com', 'role' => 'Full Stack Developer'],
            ['name' => 'William Young', 'email' => 'william.young@example.com', 'role' => 'Full Stack Developer'],
            ['name' => 'Charlotte King', 'email' => 'charlotte.king@example.com', 'role' => 'Full Stack Developer'],
            
            // QA Engineers
            ['name' => 'Benjamin Wright', 'email' => 'ben.wright@example.com', 'role' => 'QA Engineer'],
            ['name' => 'Amelia Scott', 'email' => 'amelia.scott@example.com', 'role' => 'QA Engineer'],
            ['name' => 'Lucas Green', 'email' => 'lucas.green@example.com', 'role' => 'QA Engineer'],
            
            // DevOps Engineers
            ['name' => 'Harper Adams', 'email' => 'harper.adams@example.com', 'role' => 'DevOps Engineer'],
            ['name' => 'Henry Nelson', 'email' => 'henry.nelson@example.com', 'role' => 'DevOps Engineer'],
            
            // Technical Writers
            ['name' => 'Evelyn Hill', 'email' => 'evelyn.hill@example.com', 'role' => 'Technical Writer'],
            ['name' => 'Sebastian Moore', 'email' => 'sebastian.moore@example.com', 'role' => 'Technical Writer'],
            
            // Security Specialists
            ['name' => 'Aria Jackson', 'email' => 'aria.jackson@example.com', 'role' => 'Security Specialist'],
            ['name' => 'Jack Martin', 'email' => 'jack.martin@example.com', 'role' => 'Security Specialist'],
            
            // Additional team members
            ['name' => 'Grace Lee', 'email' => 'grace.lee@example.com', 'role' => 'Full Stack Developer'],
            ['name' => 'Owen Miller', 'email' => 'owen.miller@example.com', 'role' => 'Backend Developer'],
            ['name' => 'Chloe Davis', 'email' => 'chloe.davis@example.com', 'role' => 'Frontend Developer'],
            ['name' => 'Noah Wilson', 'email' => 'noah.wilson@example.com', 'role' => 'QA Engineer'],
        ];

        $users = [];
        foreach ($usersData as $data) {
            $users[$data['email']] = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'professional_summary' => 'Experienced ' . $data['role'] . ' with a passion for building great software.',
                ]
            );
            $users[$data['email']]->preferredRole = $data['role'];
        }

        return $users;
    }

    /**
     * Create teams with realistic role assignments.
     */
    protected function createTeams(array $users): array
    {
        $systemRoles = Role::where('is_system_role', true)->get()->keyBy('name');
        
        $teamsData = [
            [
                'name' => 'E-Commerce Platform Team',
                'description' => 'Team building the next-generation e-commerce platform',
                'owner' => 'sarah.johnson@example.com',
                'members' => [
                    'david.wilson@example.com' => 'Scrum Master',
                    'amanda.lee@example.com' => 'UX/UI Designer',
                    'james.brown@example.com' => 'Frontend Developer',
                    'olivia.taylor@example.com' => 'Frontend Developer',
                    'daniel.harris@example.com' => 'Backend Developer',
                    'ava.robinson@example.com' => 'Backend Developer',
                    'benjamin.wright@example.com' => 'QA Engineer',
                    'harper.adams@example.com' => 'DevOps Engineer',
                    'aria.jackson@example.com' => 'Security Specialist',
                ],
            ],
            [
                'name' => 'Mobile App Team',
                'description' => 'Developing cross-platform mobile applications',
                'owner' => 'michael.chen@example.com',
                'members' => [
                    'jessica.martinez@example.com' => 'Scrum Master',
                    'ryan.thompson@example.com' => 'UX/UI Designer',
                    'ethan.anderson@example.com' => 'Frontend Developer',
                    'alexander.walker@example.com' => 'Full Stack Developer',
                    'mia.hall@example.com' => 'Full Stack Developer',
                    'amelia.scott@example.com' => 'QA Engineer',
                    'evelyn.hill@example.com' => 'Technical Writer',
                ],
            ],
            [
                'name' => 'Data Analytics Platform',
                'description' => 'Building enterprise data analytics and visualization platform',
                'owner' => 'emily.davis@example.com',
                'members' => [
                    'david.wilson@example.com' => 'Scrum Master',
                    'sophia.garcia@example.com' => 'UX/UI Designer',
                    'emma.white@example.com' => 'Frontend Developer',
                    'matthew.clark@example.com' => 'Backend Developer',
                    'isabella.lewis@example.com' => 'Backend Developer',
                    'william.young@example.com' => 'Full Stack Developer',
                    'lucas.green@example.com' => 'QA Engineer',
                    'henry.nelson@example.com' => 'DevOps Engineer',
                    'sebastian.moore@example.com' => 'Technical Writer',
                    'jack.martin@example.com' => 'Security Specialist',
                ],
            ],
            [
                'name' => 'API Gateway Team',
                'description' => 'Developing and maintaining API gateway infrastructure',
                'owner' => 'sarah.johnson@example.com',
                'members' => [
                    'daniel.harris@example.com' => 'Backend Developer',
                    'owen.miller@example.com' => 'Backend Developer',
                    'charlotte.king@example.com' => 'Full Stack Developer',
                    'harper.adams@example.com' => 'DevOps Engineer',
                    'aria.jackson@example.com' => 'Security Specialist',
                    'noah.wilson@example.com' => 'QA Engineer',
                ],
            ],
            [
                'name' => 'Customer Portal Team',
                'description' => 'Building customer-facing self-service portal',
                'owner' => 'michael.chen@example.com',
                'members' => [
                    'amanda.lee@example.com' => 'UX/UI Designer',
                    'chloe.davis@example.com' => 'Frontend Developer',
                    'james.brown@example.com' => 'Frontend Developer',
                    'grace.lee@example.com' => 'Full Stack Developer',
                    'benjamin.wright@example.com' => 'QA Engineer',
                    'evelyn.hill@example.com' => 'Technical Writer',
                ],
            ],
        ];

        $teams = [];
        foreach ($teamsData as $data) {
            $owner = $users[$data['owner']];
            
            $team = Team::updateOrCreate(
                ['name' => $data['name']],
                [
                    'owner_id' => $owner->id,
                    'description' => $data['description'],
                ]
            );

            // Add owner as member with Product Owner role
            $ownerRole = $systemRoles->get('Product Owner');
            $team->members()->syncWithoutDetaching([
                $owner->id => [
                    'role' => 'Product Owner',
                    'role_id' => $ownerRole?->id,
                ]
            ]);

            // Add team members with their roles
            foreach ($data['members'] as $email => $roleName) {
                if (isset($users[$email])) {
                    $role = $systemRoles->get($roleName);
                    $team->members()->syncWithoutDetaching([
                        $users[$email]->id => [
                            'role' => $roleName,
                            'role_id' => $role?->id,
                        ]
                    ]);
                }
            }

            $teams[$data['name']] = $team;
        }

        return $teams;
    }

    /**
     * Create projects with detailed SRS documents.
     */
    protected function createProjects(array $users, array $teams): array
    {
        $projectsData = [
            [
                'name' => 'ShopEase E-Commerce Platform',
                'description' => 'A modern, scalable e-commerce platform with advanced features including AI-powered recommendations, real-time inventory management, and seamless payment integration.',
                'owner' => 'sarah.johnson@example.com',
                'team' => 'E-Commerce Platform Team',
                'srs' => [
                    'title' => 'ShopEase Platform SRS',
                    'description' => 'Software Requirements Specification for ShopEase E-Commerce Platform',
                    'purpose' => 'This document describes the functional and non-functional requirements for the ShopEase e-commerce platform.',
                    'functional_requirements' => [
                        ['title' => 'User Registration & Authentication', 'description' => 'Users can create accounts using email or social login (Google, Facebook). System supports 2FA and password recovery.', 'priority' => 'critical'],
                        ['title' => 'Product Catalog Management', 'description' => 'Admin users can add, edit, and manage products with categories, variants, pricing, and inventory levels.', 'priority' => 'critical'],
                        ['title' => 'Shopping Cart System', 'description' => 'Users can add products to cart, modify quantities, apply coupons, and save cart for later.', 'priority' => 'high'],
                        ['title' => 'Checkout & Payment Processing', 'description' => 'Secure checkout flow with multiple payment options (credit card, PayPal, Apple Pay) and order confirmation.', 'priority' => 'critical'],
                        ['title' => 'Order Management', 'description' => 'Users can view order history, track shipments, and manage returns. Admin can process and fulfill orders.', 'priority' => 'high'],
                        ['title' => 'Search & Filtering', 'description' => 'Advanced search with filters by category, price range, ratings, and availability. Supports autocomplete.', 'priority' => 'high'],
                        ['title' => 'Product Reviews & Ratings', 'description' => 'Customers can leave reviews and ratings. Reviews support images and verified purchase badges.', 'priority' => 'medium'],
                        ['title' => 'Wishlist Feature', 'description' => 'Users can save products to wishlist for future purchase and share wishlists.', 'priority' => 'low'],
                        ['title' => 'AI Product Recommendations', 'description' => 'Machine learning-based recommendations based on browsing history, purchases, and similar users.', 'priority' => 'medium'],
                        ['title' => 'Inventory Alerts', 'description' => 'Automated alerts for low stock, out-of-stock notifications, and restock predictions.', 'priority' => 'medium'],
                    ],
                    'non_functional_requirements' => [
                        ['title' => 'Page Load Performance', 'description' => 'All pages must load within 2 seconds on standard broadband connection.', 'category' => 'performance', 'priority' => 'high'],
                        ['title' => 'System Availability', 'description' => 'Platform must maintain 99.9% uptime with automatic failover.', 'category' => 'availability', 'priority' => 'critical'],
                        ['title' => 'Data Encryption', 'description' => 'All sensitive data must be encrypted at rest and in transit using AES-256 and TLS 1.3.', 'category' => 'security', 'priority' => 'critical'],
                        ['title' => 'PCI DSS Compliance', 'description' => 'Payment processing must comply with PCI DSS Level 1 requirements.', 'category' => 'compliance', 'priority' => 'critical'],
                        ['title' => 'Horizontal Scalability', 'description' => 'System must support automatic scaling to handle 10x normal traffic during peak events.', 'category' => 'scalability', 'priority' => 'high'],
                    ],
                ],
            ],
            [
                'name' => 'HealthTrack Mobile App',
                'description' => 'A comprehensive health and fitness tracking mobile application with workout plans, nutrition logging, and health metrics integration.',
                'owner' => 'michael.chen@example.com',
                'team' => 'Mobile App Team',
                'srs' => [
                    'title' => 'HealthTrack Mobile App SRS',
                    'description' => 'Requirements specification for HealthTrack health and fitness application',
                    'purpose' => 'Define requirements for a mobile health tracking application.',
                    'functional_requirements' => [
                        ['title' => 'User Profile & Health Data', 'description' => 'Users can create profiles with health information, goals, and preferences.', 'priority' => 'critical'],
                        ['title' => 'Activity Tracking', 'description' => 'Track steps, distance, calories burned, and active minutes using device sensors.', 'priority' => 'critical'],
                        ['title' => 'Workout Plans', 'description' => 'Pre-built and customizable workout plans with video demonstrations and progress tracking.', 'priority' => 'high'],
                        ['title' => 'Nutrition Logging', 'description' => 'Log meals with calorie counting, macro tracking, and barcode scanning.', 'priority' => 'high'],
                        ['title' => 'Health Metrics Integration', 'description' => 'Sync with wearables (Apple Watch, Fitbit) and health apps (Apple Health, Google Fit).', 'priority' => 'high'],
                        ['title' => 'Goal Setting & Achievements', 'description' => 'Set fitness goals with milestones and earn badges for achievements.', 'priority' => 'medium'],
                        ['title' => 'Social Features', 'description' => 'Connect with friends, share achievements, and participate in challenges.', 'priority' => 'medium'],
                        ['title' => 'Push Notifications', 'description' => 'Reminders for workouts, hydration, and goal progress updates.', 'priority' => 'medium'],
                    ],
                    'non_functional_requirements' => [
                        ['title' => 'Offline Functionality', 'description' => 'Core features must work offline with automatic sync when connected.', 'category' => 'reliability', 'priority' => 'high'],
                        ['title' => 'Battery Optimization', 'description' => 'Background tracking must minimize battery drain (<5% per day).', 'category' => 'performance', 'priority' => 'high'],
                        ['title' => 'HIPAA Compliance', 'description' => 'Health data handling must comply with HIPAA regulations.', 'category' => 'compliance', 'priority' => 'critical'],
                        ['title' => 'Cross-Platform Consistency', 'description' => 'UI and features must be consistent across iOS and Android.', 'category' => 'compatibility', 'priority' => 'high'],
                    ],
                ],
            ],
            [
                'name' => 'DataViz Analytics Dashboard',
                'description' => 'Enterprise-grade data analytics and visualization platform with real-time dashboards, custom reporting, and data pipeline management.',
                'owner' => 'emily.davis@example.com',
                'team' => 'Data Analytics Platform',
                'srs' => [
                    'title' => 'DataViz Analytics SRS',
                    'description' => 'Software Requirements for DataViz Enterprise Analytics Platform',
                    'purpose' => 'Define requirements for enterprise data analytics solution.',
                    'functional_requirements' => [
                        ['title' => 'Data Source Connectors', 'description' => 'Connect to databases (PostgreSQL, MySQL, MongoDB), cloud storage, and APIs.', 'priority' => 'critical'],
                        ['title' => 'Dashboard Builder', 'description' => 'Drag-and-drop interface to create custom dashboards with various chart types.', 'priority' => 'critical'],
                        ['title' => 'Real-time Data Streaming', 'description' => 'Support for real-time data updates with WebSocket connections.', 'priority' => 'high'],
                        ['title' => 'Custom Reports', 'description' => 'Generate scheduled and ad-hoc reports in PDF, Excel, and CSV formats.', 'priority' => 'high'],
                        ['title' => 'Data Transformation', 'description' => 'Built-in ETL capabilities for data cleaning, aggregation, and transformation.', 'priority' => 'high'],
                        ['title' => 'User Access Control', 'description' => 'Role-based permissions for dashboards, data sources, and reports.', 'priority' => 'critical'],
                        ['title' => 'Alert System', 'description' => 'Configure alerts based on metric thresholds with email and Slack notifications.', 'priority' => 'medium'],
                        ['title' => 'Embedded Analytics', 'description' => 'Embed dashboards in external applications with white-label options.', 'priority' => 'medium'],
                        ['title' => 'Natural Language Queries', 'description' => 'Ask questions in plain English to generate visualizations automatically.', 'priority' => 'low'],
                    ],
                    'non_functional_requirements' => [
                        ['title' => 'Query Performance', 'description' => 'Dashboard queries must return results within 5 seconds for datasets up to 10M rows.', 'category' => 'performance', 'priority' => 'critical'],
                        ['title' => 'Data Security', 'description' => 'Implement row-level security and data masking for sensitive information.', 'category' => 'security', 'priority' => 'critical'],
                        ['title' => 'Multi-tenancy', 'description' => 'Support isolated environments for different client organizations.', 'category' => 'scalability', 'priority' => 'high'],
                        ['title' => 'Audit Logging', 'description' => 'Complete audit trail of data access and modifications.', 'category' => 'compliance', 'priority' => 'high'],
                        ['title' => 'Browser Compatibility', 'description' => 'Support latest versions of Chrome, Firefox, Safari, and Edge.', 'category' => 'compatibility', 'priority' => 'high'],
                    ],
                ],
            ],
            [
                'name' => 'API Gateway Service',
                'description' => 'Centralized API gateway for microservices architecture with rate limiting, authentication, and request routing.',
                'owner' => 'sarah.johnson@example.com',
                'team' => 'API Gateway Team',
                'srs' => [
                    'title' => 'API Gateway SRS',
                    'description' => 'Requirements for API Gateway Service',
                    'purpose' => 'Define requirements for centralized API management.',
                    'functional_requirements' => [
                        ['title' => 'Request Routing', 'description' => 'Route incoming requests to appropriate microservices based on path and headers.', 'priority' => 'critical'],
                        ['title' => 'Authentication & Authorization', 'description' => 'Support JWT, OAuth 2.0, and API key authentication methods.', 'priority' => 'critical'],
                        ['title' => 'Rate Limiting', 'description' => 'Configurable rate limits per API key, user, or IP address.', 'priority' => 'high'],
                        ['title' => 'Request/Response Transformation', 'description' => 'Modify headers, body, and query parameters for backend compatibility.', 'priority' => 'medium'],
                        ['title' => 'API Versioning', 'description' => 'Support multiple API versions with deprecation management.', 'priority' => 'high'],
                        ['title' => 'Developer Portal', 'description' => 'Self-service portal for API documentation, key management, and usage analytics.', 'priority' => 'medium'],
                    ],
                    'non_functional_requirements' => [
                        ['title' => 'Latency Requirements', 'description' => 'Gateway overhead must not exceed 10ms at p99.', 'category' => 'performance', 'priority' => 'critical'],
                        ['title' => 'High Availability', 'description' => 'Zero-downtime deployments and automatic failover.', 'category' => 'availability', 'priority' => 'critical'],
                        ['title' => 'Throughput', 'description' => 'Handle 100,000 requests per second per instance.', 'category' => 'scalability', 'priority' => 'high'],
                    ],
                ],
            ],
            [
                'name' => 'Customer Self-Service Portal',
                'description' => 'Web-based portal for customers to manage accounts, view billing, submit support tickets, and access knowledge base.',
                'owner' => 'michael.chen@example.com',
                'team' => 'Customer Portal Team',
                'srs' => [
                    'title' => 'Customer Portal SRS',
                    'description' => 'Requirements for Customer Self-Service Portal',
                    'purpose' => 'Define requirements for customer-facing portal.',
                    'functional_requirements' => [
                        ['title' => 'Account Management', 'description' => 'Customers can update profile, change password, and manage notification preferences.', 'priority' => 'critical'],
                        ['title' => 'Billing & Invoices', 'description' => 'View invoices, payment history, and download statements.', 'priority' => 'high'],
                        ['title' => 'Support Tickets', 'description' => 'Create, track, and respond to support tickets with file attachments.', 'priority' => 'high'],
                        ['title' => 'Knowledge Base', 'description' => 'Searchable FAQ and documentation with categories and related articles.', 'priority' => 'medium'],
                        ['title' => 'Live Chat', 'description' => 'Real-time chat with support agents with chatbot fallback.', 'priority' => 'medium'],
                        ['title' => 'Service Status', 'description' => 'Display current system status and incident history.', 'priority' => 'low'],
                    ],
                    'non_functional_requirements' => [
                        ['title' => 'Accessibility', 'description' => 'WCAG 2.1 AA compliance for all features.', 'category' => 'usability', 'priority' => 'high'],
                        ['title' => 'Mobile Responsiveness', 'description' => 'Full functionality on mobile devices without app installation.', 'category' => 'usability', 'priority' => 'high'],
                        ['title' => 'Session Security', 'description' => 'Secure session management with automatic timeout.', 'category' => 'security', 'priority' => 'high'],
                    ],
                ],
            ],
        ];

        $projects = [];
        foreach ($projectsData as $data) {
            $owner = $users[$data['owner']];
            $team = $teams[$data['team']] ?? null;

            $project = Project::updateOrCreate(
                ['name' => $data['name'], 'user_id' => $owner->id],
                [
                    'description' => $data['description'],
                    'status' => 'active',
                    'type' => 'team',
                    'team_id' => $team?->id,
                    'start_date' => now()->subDays(rand(30, 90)),
                    'source' => 'auto',
                ]
            );

            // Create SRS document
            $srsData = $data['srs'];
            $srs = SrsDocument::updateOrCreate(
                ['title' => $srsData['title'], 'user_id' => $owner->id],
                [
                    'project_id' => $project->id,
                    'description' => $srsData['description'],
                    'purpose' => $srsData['purpose'],
                    'version' => '1.0',
                    'status' => 'approved',
                ]
            );

            // Create functional requirements
            $order = 1;
            foreach ($srsData['functional_requirements'] as $index => $req) {
                SrsFunctionalRequirement::updateOrCreate(
                    [
                        'srs_document_id' => $srs->id,
                        'section_number' => 'FR-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    ],
                    [
                        'requirement_id' => 'FR-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'title' => $req['title'],
                        'description' => $req['description'],
                        'priority' => $req['priority'],
                        'status' => 'approved',
                        'implementation_status' => 'listed',
                        'order' => $order++,
                    ]
                );
            }

            // Create non-functional requirements
            $order = 1;
            foreach ($srsData['non_functional_requirements'] as $index => $req) {
                SrsNonFunctionalRequirement::updateOrCreate(
                    [
                        'srs_document_id' => $srs->id,
                        'section_number' => 'NFR-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    ],
                    [
                        'requirement_id' => 'NFR-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'title' => $req['title'],
                        'description' => $req['description'],
                        'category' => $req['category'],
                        'priority' => $req['priority'],
                        'status' => 'approved',
                        'implementation_status' => 'listed',
                        'order' => $order++,
                    ]
                );
            }

            $projects[$data['name']] = $project;
        }

        return $projects;
    }

    /**
     * Create sample tasks demonstrating role-based assignments.
     */
    protected function createSampleTasks(array $projects): void
    {
        $systemRoles = Role::where('is_system_role', true)->get()->keyBy('name');
        
        // Sample tasks for first project to demonstrate AI-generated tasks
        $project = $projects['ShopEase E-Commerce Platform'] ?? null;
        if (!$project) return;

        $srs = $project->srsDocuments()->first();
        if (!$srs) return;

        $team = $project->team;
        if (!$team) return;

        // Get team members by role
        $membersByRole = [];
        foreach ($team->members()->withPivot(['role', 'role_id'])->get() as $member) {
            $roleName = $member->pivot->role ?? 'Team Member';
            if (!isset($membersByRole[$roleName])) {
                $membersByRole[$roleName] = [];
            }
            $membersByRole[$roleName][] = $member;
        }

        $sampleTasks = [
            [
                'title' => 'Design user registration flow wireframes',
                'description' => 'Create detailed wireframes for user registration including email signup, social login options, and 2FA setup screens.',
                'priority' => 'High',
                'role' => 'UX/UI Designer',
                'requirement_section' => 'FR-001',
                'estimated_hours' => 8,
            ],
            [
                'title' => 'Implement user registration API endpoints',
                'description' => 'Create REST API endpoints for user registration, email verification, and social OAuth callbacks.',
                'priority' => 'Critical',
                'role' => 'Backend Developer',
                'requirement_section' => 'FR-001',
                'estimated_hours' => 16,
            ],
            [
                'title' => 'Build registration form components',
                'description' => 'Implement React components for registration form with validation, error handling, and loading states.',
                'priority' => 'High',
                'role' => 'Frontend Developer',
                'requirement_section' => 'FR-001',
                'estimated_hours' => 12,
            ],
            [
                'title' => 'Set up CI/CD pipeline for deployment',
                'description' => 'Configure GitHub Actions for automated testing, building, and deployment to staging environment.',
                'priority' => 'High',
                'role' => 'DevOps Engineer',
                'requirement_section' => null,
                'estimated_hours' => 8,
            ],
            [
                'title' => 'Write test cases for authentication module',
                'description' => 'Create comprehensive test suite covering registration, login, password reset, and 2FA flows.',
                'priority' => 'High',
                'role' => 'QA Engineer',
                'requirement_section' => 'FR-001',
                'estimated_hours' => 10,
            ],
            [
                'title' => 'Security audit of authentication system',
                'description' => 'Perform security review of authentication implementation, check for vulnerabilities and compliance with best practices.',
                'priority' => 'Critical',
                'role' => 'Security Specialist',
                'requirement_section' => 'NFR-003',
                'estimated_hours' => 6,
            ],
            [
                'title' => 'Document API authentication endpoints',
                'description' => 'Write API documentation for authentication endpoints including request/response examples and error codes.',
                'priority' => 'Medium',
                'role' => 'Technical Writer',
                'requirement_section' => 'FR-001',
                'estimated_hours' => 4,
            ],
        ];

        foreach ($sampleTasks as $taskData) {
            // Find assignee based on role
            $assignee = null;
            if (isset($membersByRole[$taskData['role']])) {
                $assignee = $membersByRole[$taskData['role']][array_rand($membersByRole[$taskData['role']])];
            }

            // Find requirement
            $requirementType = null;
            $requirementId = null;
            if ($taskData['requirement_section']) {
                if (str_starts_with($taskData['requirement_section'], 'FR-')) {
                    $req = $srs->functionalRequirements()->where('section_number', $taskData['requirement_section'])->first();
                    if ($req) {
                        $requirementType = SrsFunctionalRequirement::class;
                        $requirementId = $req->id;
                    }
                } else {
                    $req = $srs->nonFunctionalRequirements()->where('section_number', $taskData['requirement_section'])->first();
                    if ($req) {
                        $requirementType = SrsNonFunctionalRequirement::class;
                        $requirementId = $req->id;
                    }
                }
            }

            Task::updateOrCreate(
                ['project_id' => $project->id, 'title' => $taskData['title']],
                [
                    'description' => $taskData['description'],
                    'priority' => $taskData['priority'],
                    'status' => ['To Do', 'In Progress', 'Review'][array_rand(['To Do', 'In Progress', 'Review'])],
                    'assigned_to' => $assignee?->id,
                    'created_by' => $project->user_id,
                    'estimated_hours' => $taskData['estimated_hours'],
                    'required_role_id' => $systemRoles->get($taskData['role'])?->id,
                    'requirement_type' => $requirementType,
                    'requirement_id' => $requirementId,
                    'is_ai_generated' => true,
                    'ai_generated_description' => $taskData['description'],
                ]
            );
        }
    }
}
