<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Task;
use App\Models\Role;
use App\Models\Invitation;
use App\Models\SrsDocument;
use App\Models\SrsFunctionalRequirement;
use App\Models\SrsNonFunctionalRequirement;
use App\Models\Diagram;
use App\Models\Documentation;
use App\Models\Skill;
use App\Models\Certification;
use App\Models\Education;
use App\Models\WorkExperience;
use App\Models\TaskActivity;
use App\Models\NotificationPreference;

/**
 * Showcase Data Seeder
 * 
 * This seeder creates a comprehensive, realistic dataset to demonstrate all features
 * of the GrowDev project management system including:
 * - Multiple teams with various roles
 * - Complex projects with SRS documentation
 * - Tasks with different statuses and priorities
 * - User profiles with skills, certifications, and work history
 * - Project diagrams and documentation
 * - Task activities and collaboration features
 * 
 * Run with: php artisan migrate:fresh --seed
 * Or: php artisan db:seed --class=ShowcaseDataSeeder
 */
class ShowcaseDataSeeder extends Seeder
{
    private const PASSWORD = 'password'; // All demo users use this password
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding showcase data...');
        
        $this->seedUsers();
        $this->seedTeams();
        $this->seedTeamMembers();
        $this->seedProjects();
        $this->seedTasks();
        $this->seedSrsDocuments();
        $this->seedDiagrams();
        $this->seedDocumentations();
        $this->seedUserProfiles();
        $this->seedTaskActivities();
        $this->seedNotificationPreferences();
        
        $this->command->info('✅ Showcase data seeded successfully!');
    }

    /**
     * Seed diverse users with different roles
     */
    private function seedUsers(): void
    {
        $users = [
            [
                'id' => 2,
                'name' => 'Alice Johnson',
                'email' => 'alice@growdev.com',
                'password' => Hash::make(self::PASSWORD),
                'professional_summary' => 'Senior Full-Stack Developer with 8+ years of experience in web application development. Specializes in Laravel, React, and cloud architecture.',
                'location' => 'San Francisco, CA',
                'linkedin_url' => 'https://linkedin.com/in/alicejohnson',
                'github_url' => 'https://github.com/alicejohnson',
                'email_verified_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Bob Martinez',
                'email' => 'bob@growdev.com',
                'password' => Hash::make(self::PASSWORD),
                'professional_summary' => 'Product Manager with a passion for user-centered design and agile methodologies. 6 years of experience leading cross-functional teams.',
                'location' => 'Austin, TX',
                'linkedin_url' => 'https://linkedin.com/in/bobmartinez',
                'email_verified_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Carol Smith',
                'email' => 'carol@growdev.com',
                'password' => Hash::make(self::PASSWORD),
                'professional_summary' => 'UI/UX Designer focused on creating intuitive and accessible digital experiences. Proficient in Figma, Adobe XD, and user research.',
                'location' => 'New York, NY',
                'linkedin_url' => 'https://linkedin.com/in/carolsmith',
                'email_verified_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'David Chen',
                'email' => 'david@growdev.com',
                'password' => Hash::make(self::PASSWORD),
                'professional_summary' => 'Backend Engineer specializing in microservices architecture and database optimization. Strong expertise in API design and performance tuning.',
                'location' => 'Seattle, WA',
                'github_url' => 'https://github.com/davidchen',
                'email_verified_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Emma Wilson',
                'email' => 'emma@growdev.com',
                'password' => Hash::make(self::PASSWORD),
                'professional_summary' => 'Frontend Developer passionate about modern JavaScript frameworks and responsive design. Experienced in React, Vue.js, and TypeScript.',
                'location' => 'Portland, OR',
                'github_url' => 'https://github.com/emmawilson',
                'email_verified_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Frank Kumar',
                'email' => 'frank@growdev.com',
                'password' => Hash::make(self::PASSWORD),
                'professional_summary' => 'QA Engineer with expertise in automated testing and CI/CD pipelines. Committed to delivering high-quality software.',
                'location' => 'Chicago, IL',
                'email_verified_at' => now(),
            ],
            [
                'id' => 8,
                'name' => 'Grace Lee',
                'email' => 'grace@growdev.com',
                'password' => Hash::make(self::PASSWORD),
                'professional_summary' => 'DevOps Engineer skilled in cloud infrastructure, containerization, and automation. AWS and Docker certified.',
                'location' => 'Denver, CO',
                'linkedin_url' => 'https://linkedin.com/in/gracelee',
                'email_verified_at' => now(),
            ],
            [
                'id' => 9,
                'name' => 'Henry Taylor',
                'email' => 'henry@growdev.com',
                'password' => Hash::make(self::PASSWORD),
                'professional_summary' => 'Junior Developer eager to learn and contribute. Recently completed a coding bootcamp with focus on full-stack development.',
                'location' => 'Boston, MA',
                'github_url' => 'https://github.com/henrytaylor',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('✓ Seeded ' . count($users) . ' users');
    }

    /**
     * Seed teams with different structures
     */
    private function seedTeams(): void
    {
        $teams = [
            [
                'id' => 1,
                'name' => 'Enterprise Solutions Team',
                'description' => 'Building scalable enterprise applications for Fortune 500 clients. Focus on high-performance, secure, and maintainable solutions.',
                'owner_id' => 2,
            ],
            [
                'id' => 2,
                'name' => 'E-Commerce Platform Team',
                'description' => 'Developing next-generation e-commerce solutions with AI-powered recommendations and seamless payment integrations.',
                'owner_id' => 3,
            ],
            [
                'id' => 3,
                'name' => 'Mobile Innovation Lab',
                'description' => 'Experimental team exploring cutting-edge mobile technologies and progressive web apps.',
                'owner_id' => 4,
            ],
        ];

        foreach ($teams as $teamData) {
            Team::create($teamData);
        }

        $this->command->info('✓ Seeded ' . count($teams) . ' teams');
    }

    /**
     * Seed team members with realistic distributions
     */
    private function seedTeamMembers(): void
    {
        $teamMembers = [
            // Enterprise Solutions Team (Team 1)
            ['team_id' => 1, 'user_id' => 2], // Alice (Owner)
            ['team_id' => 1, 'user_id' => 5], // David
            ['team_id' => 1, 'user_id' => 7], // Frank
            ['team_id' => 1, 'user_id' => 8], // Grace
            ['team_id' => 1, 'user_id' => 9], // Henry
            
            // E-Commerce Platform Team (Team 2)
            ['team_id' => 2, 'user_id' => 3], // Bob (Owner)
            ['team_id' => 2, 'user_id' => 4], // Carol
            ['team_id' => 2, 'user_id' => 6], // Emma
            ['team_id' => 2, 'user_id' => 7], // Frank (also in Team 2)
            
            // Mobile Innovation Lab (Team 3)
            ['team_id' => 3, 'user_id' => 4], // Carol (Owner)
            ['team_id' => 3, 'user_id' => 6], // Emma (also in Team 3)
            ['team_id' => 3, 'user_id' => 9], // Henry (also in Team 3)
        ];

        foreach ($teamMembers as $member) {
            DB::table('team_user')->insert($member);
        }

        $this->command->info('✓ Seeded ' . count($teamMembers) . ' team memberships');
    }

    /**
     * Seed projects with comprehensive details
     */
    private function seedProjects(): void
    {
        $projects = [
            [
                'id' => 1,
                'name' => 'Cloud Banking Platform',
                'description' => 'A secure, scalable cloud-based banking platform with real-time transaction processing, multi-factor authentication, and comprehensive fraud detection. Targeting regional banks looking to modernize their infrastructure.',
                'team_id' => 1,
                'user_id' => 2,
                'created_at' => now()->subDays(45),
            ],
            [
                'id' => 2,
                'name' => 'Healthcare Management System',
                'description' => 'HIPAA-compliant healthcare management system for patient records, appointment scheduling, telemedicine integration, and billing. Designed for small to medium healthcare practices.',
                'team_id' => 1,
                'user_id' => 5,
                'created_at' => now()->subDays(30),
            ],
            [
                'id' => 3,
                'name' => 'AI-Powered Shopping Assistant',
                'description' => 'E-commerce platform featuring AI-powered product recommendations, voice search, AR try-on, and seamless checkout. Focus on fashion and lifestyle products.',
                'team_id' => 2,
                'user_id' => 3,
                'created_at' => now()->subDays(60),
            ],
            [
                'id' => 4,
                'name' => 'Inventory Management Pro',
                'description' => 'Real-time inventory tracking system with barcode scanning, automated reordering, analytics dashboard, and multi-warehouse support. Built for retail chains.',
                'team_id' => 2,
                'user_id' => 6,
                'created_at' => now()->subDays(20),
            ],
            [
                'id' => 5,
                'name' => 'Fitness Tracking PWA',
                'description' => 'Progressive web app for fitness tracking with workout plans, nutrition logging, social challenges, and wearable device integration.',
                'team_id' => 3,
                'user_id' => 4,
                'created_at' => now()->subDays(15),
            ],
        ];

        foreach ($projects as $projectData) {
            Project::create($projectData);
        }

        $this->command->info('✓ Seeded ' . count($projects) . ' projects');
    }

    /**
     * Seed tasks with various statuses and priorities
     */
    private function seedTasks(): void
    {
        $tasks = [
            // Cloud Banking Platform Tasks (Project 1)
            [
                'project_id' => 1,
                'title' => 'Design Authentication System Architecture',
                'description' => 'Create comprehensive architecture for multi-factor authentication including biometric, SMS, and TOTP options. Must support OAuth 2.0 and SAML integration.',
                'status' => 'Done',
                'priority' => 'Critical',
                'assigned_to' => 2,
                'created_by' => 2,
                'due_date' => now()->subDays(30),
                'estimated_hours' => 40,
                'category' => 'Architecture',
            ],
            [
                'project_id' => 1,
                'title' => 'Implement Transaction Processing Engine',
                'description' => 'Build high-performance transaction processing engine with ACID compliance, rollback mechanisms, and real-time balance updates.',
                'status' => 'In Progress',
                'priority' => 'Critical',
                'assigned_to' => 5,
                'created_by' => 2,
                'due_date' => now()->addDays(14),
                'estimated_hours' => 80,
                'category' => 'Backend',
            ],
            [
                'project_id' => 1,
                'title' => 'Set Up CI/CD Pipeline',
                'description' => 'Configure automated testing, staging deployments, and production releases with rollback capabilities.',
                'status' => 'Done',
                'priority' => 'High',
                'assigned_to' => 8,
                'created_by' => 2,
                'due_date' => now()->subDays(10),
                'estimated_hours' => 24,
                'category' => 'DevOps',
            ],
            [
                'project_id' => 1,
                'title' => 'Implement Fraud Detection Rules',
                'description' => 'Develop rule-based fraud detection system analyzing transaction patterns, velocities, and anomalies.',
                'status' => 'Review',
                'priority' => 'High',
                'assigned_to' => 5,
                'created_by' => 2,
                'due_date' => now()->addDays(7),
                'estimated_hours' => 60,
                'category' => 'Security',
            ],
            [
                'project_id' => 1,
                'title' => 'Create Integration Tests for Payment Gateway',
                'description' => 'Comprehensive integration test suite for payment processing including success, failure, and timeout scenarios.',
                'status' => 'To Do',
                'priority' => 'High',
                'assigned_to' => 7,
                'created_by' => 2,
                'due_date' => now()->addDays(21),
                'estimated_hours' => 32,
                'category' => 'Testing',
            ],
            
            // Healthcare Management System Tasks (Project 2)
            [
                'project_id' => 2,
                'title' => 'HIPAA Compliance Audit',
                'description' => 'Complete security audit ensuring all HIPAA requirements are met including data encryption, access controls, and audit logging.',
                'status' => 'In Progress',
                'priority' => 'Critical',
                'assigned_to' => 8,
                'created_by' => 5,
                'due_date' => now()->addDays(10),
                'estimated_hours' => 50,
                'category' => 'Compliance',
            ],
            [
                'project_id' => 2,
                'title' => 'Build Patient Portal Interface',
                'description' => 'User-friendly patient portal for viewing medical records, scheduling appointments, and secure messaging with healthcare providers.',
                'status' => 'In Progress',
                'priority' => 'High',
                'assigned_to' => 9,
                'created_by' => 5,
                'due_date' => now()->addDays(28),
                'estimated_hours' => 72,
                'category' => 'Frontend',
            ],
            [
                'project_id' => 2,
                'title' => 'Integrate Telemedicine Platform',
                'description' => 'Integration with third-party telemedicine service including video calls, screen sharing, and session recording.',
                'status' => 'To Do',
                'priority' => 'Medium',
                'assigned_to' => 5,
                'created_by' => 5,
                'due_date' => now()->addDays(45),
                'estimated_hours' => 40,
                'category' => 'Integration',
            ],
            
            // AI-Powered Shopping Assistant Tasks (Project 3)
            [
                'project_id' => 3,
                'title' => 'Train Product Recommendation Model',
                'description' => 'Train machine learning model on historical purchase data to provide personalized product recommendations.',
                'status' => 'Done',
                'priority' => 'High',
                'assigned_to' => 3,
                'created_by' => 3,
                'due_date' => now()->subDays(15),
                'estimated_hours' => 100,
                'category' => 'AI/ML',
            ],
            [
                'project_id' => 3,
                'title' => 'Design Shopping Cart Experience',
                'description' => 'Create intuitive shopping cart interface with real-time price updates, promo code support, and saved items.',
                'status' => 'Done',
                'priority' => 'High',
                'assigned_to' => 4,
                'created_by' => 3,
                'due_date' => now()->subDays(5),
                'estimated_hours' => 40,
                'category' => 'UI/UX',
            ],
            [
                'project_id' => 3,
                'title' => 'Implement AR Try-On Feature',
                'description' => 'Augmented reality feature allowing customers to virtually try on clothing and accessories using device camera.',
                'status' => 'In Progress',
                'priority' => 'Medium',
                'assigned_to' => 6,
                'created_by' => 3,
                'due_date' => now()->addDays(30),
                'estimated_hours' => 120,
                'category' => 'Frontend',
            ],
            [
                'project_id' => 3,
                'title' => 'Set Up Payment Processing',
                'description' => 'Integrate multiple payment gateways (Stripe, PayPal, Apple Pay) with secure tokenization and PCI compliance.',
                'status' => 'Review',
                'priority' => 'Critical',
                'assigned_to' => 6,
                'created_by' => 3,
                'due_date' => now()->addDays(7),
                'estimated_hours' => 48,
                'category' => 'Backend',
            ],
            [
                'project_id' => 3,
                'title' => 'Optimize Search Performance',
                'description' => 'Implement Elasticsearch for fast product search with fuzzy matching, filters, and faceted navigation.',
                'status' => 'To Do',
                'priority' => 'High',
                'assigned_to' => null,
                'created_by' => 3,
                'due_date' => now()->addDays(35),
                'estimated_hours' => 60,
                'category' => 'Backend',
            ],
            
            // Inventory Management Pro Tasks (Project 4)
            [
                'project_id' => 4,
                'title' => 'Build Barcode Scanner Module',
                'description' => 'Mobile-responsive barcode scanner using device camera with support for multiple barcode formats.',
                'status' => 'In Progress',
                'priority' => 'High',
                'assigned_to' => 6,
                'created_by' => 6,
                'due_date' => now()->addDays(14),
                'estimated_hours' => 40,
                'category' => 'Frontend',
            ],
            [
                'project_id' => 4,
                'title' => 'Create Analytics Dashboard',
                'description' => 'Real-time analytics dashboard showing inventory levels, turnover rates, stock alerts, and sales trends.',
                'status' => 'To Do',
                'priority' => 'Medium',
                'assigned_to' => 4,
                'created_by' => 6,
                'due_date' => now()->addDays(25),
                'estimated_hours' => 56,
                'category' => 'Frontend',
            ],
            [
                'project_id' => 4,
                'title' => 'Implement Automated Reordering Logic',
                'description' => 'Smart reordering system that automatically creates purchase orders when stock falls below threshold levels.',
                'status' => 'To Do',
                'priority' => 'High',
                'assigned_to' => null,
                'created_by' => 6,
                'due_date' => now()->addDays(40),
                'estimated_hours' => 48,
                'category' => 'Backend',
            ],
            
            // Fitness Tracking PWA Tasks (Project 5)
            [
                'project_id' => 5,
                'title' => 'Design Workout Logging Interface',
                'description' => 'Intuitive interface for logging exercises, sets, reps, and weights with exercise library and templates.',
                'status' => 'Done',
                'priority' => 'High',
                'assigned_to' => 4,
                'created_by' => 4,
                'due_date' => now()->subDays(3),
                'estimated_hours' => 32,
                'category' => 'UI/UX',
            ],
            [
                'project_id' => 5,
                'title' => 'Integrate Wearable Devices',
                'description' => 'Integration with popular fitness trackers (Fitbit, Apple Watch, Garmin) to sync activity data.',
                'status' => 'In Progress',
                'priority' => 'Medium',
                'assigned_to' => 9,
                'created_by' => 4,
                'due_date' => now()->addDays(20),
                'estimated_hours' => 64,
                'category' => 'Integration',
            ],
            [
                'project_id' => 5,
                'title' => 'Build Social Challenge System',
                'description' => 'Social features allowing users to create and participate in fitness challenges with friends.',
                'status' => 'To Do',
                'priority' => 'Low',
                'assigned_to' => 9,
                'created_by' => 4,
                'due_date' => now()->addDays(50),
                'estimated_hours' => 40,
                'category' => 'Backend',
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }

        $this->command->info('✓ Seeded ' . count($tasks) . ' tasks');
    }

    /**
     * Seed SRS documents for projects
     */
    private function seedSrsDocuments(): void
    {
        $documents = [
            [
                'id' => 1,
                'project_id' => 1,
                'user_id' => 2,
                'title' => 'Cloud Banking Platform - Software Requirements Specification',
                'version' => '2.1',
                'purpose' => 'This SRS document defines the functional and non-functional requirements for a cloud-based banking platform that will serve regional banks with 100K-500K customers. The system must handle real-time transactions, ensure PCI-DSS compliance, and provide 99.99% uptime.',
                'references' => 'PCI-DSS Standards v3.2.1, ISO 27001, NIST Cybersecurity Framework, OAuth 2.0 RFC 6749, Banking API Specifications',
                'product_perspective' => 'The Cloud Banking Platform is a standalone system that integrates with existing core banking systems via secure APIs. It provides a modern, web-based interface for customers while maintaining compatibility with legacy banking infrastructure.',
                'product_features' => 'Account management, Real-time transaction processing, Multi-factor authentication, Bill pay, Fund transfers, Mobile check deposit, Fraud detection, Customer support chat, Statement generation, Audit logging',
                'user_classes' => 'Bank Customers (retail and business), Bank Administrators, Customer Support Representatives, Compliance Officers, System Administrators',
                'operating_environment' => 'Cloud-hosted on AWS infrastructure, Web browsers (Chrome, Firefox, Safari, Edge), iOS and Android mobile apps, Responsive design supporting tablets and phones',
                'constraints' => 'Must comply with PCI-DSS, GLBA, and regional banking regulations. Data residency requirements for certain jurisdictions. Legacy system integration constraints. 24/7 availability requirement.',
                'assumptions' => 'Users have reliable internet connectivity. Banks provide valid API credentials for integration. Core banking systems support REST API access. Users complete identity verification during onboarding.',
            ],
            [
                'id' => 2,
                'project_id' => 3,
                'user_id' => 3,
                'title' => 'AI Shopping Assistant - Requirements Specification',
                'version' => '1.5',
                'purpose' => 'Define requirements for an AI-powered e-commerce platform that provides personalized shopping experiences through machine learning recommendations, voice search, and augmented reality features.',
                'references' => 'Machine Learning Best Practices, E-commerce UX Guidelines, GDPR Compliance Documentation, Stripe API Documentation, Google Cloud Vision API',
                'product_perspective' => 'Standalone e-commerce platform with integrated AI/ML services. Can be white-labeled for fashion and lifestyle brands. Integrates with existing inventory and fulfillment systems.',
                'product_features' => 'AI product recommendations, Voice-activated search, AR virtual try-on, Personalized wish lists, Social sharing, One-click checkout, Order tracking, Customer reviews, Size recommendations',
                'user_classes' => 'Shoppers (mobile and desktop), Store Administrators, Marketing Team, Customer Service, Warehouse Staff',
                'operating_environment' => 'Progressive Web App compatible with all modern browsers, Native-like experience on mobile devices, Cloud-based infrastructure with CDN distribution',
                'constraints' => 'Must work offline with service workers. AR features require camera access. ML model size limited to 50MB for mobile. Response time under 2 seconds for recommendations.',
                'assumptions' => 'Users grant camera permissions for AR features. Product images are high quality and consistent. Users have modern devices with adequate processing power.',
            ],
        ];

        foreach ($documents as $docData) {
            SrsDocument::create($docData);
        }

        // Add functional requirements for Cloud Banking Platform
        $functionalRequirements = [
            [
                'srs_document_id' => 1,
                'requirement_id' => 'FR-AUTH-001',
                'section_number' => '4.1.1',
                'title' => 'Multi-Factor Authentication',
                'description' => 'The system shall support multiple authentication factors including password, SMS code, TOTP authenticator apps, and biometric authentication. Users must enable at least two factors.',
                'priority' => 'high',
            ],
            [
                'srs_document_id' => 1,
                'requirement_id' => 'FR-AUTH-002',
                'section_number' => '4.1.2',
                'title' => 'Session Management',
                'description' => 'The system shall automatically terminate user sessions after 15 minutes of inactivity. Users must re-authenticate for sensitive operations even within active sessions.',
                'priority' => 'high',
            ],
            [
                'srs_document_id' => 1,
                'requirement_id' => 'FR-TXN-001',
                'section_number' => '4.2.1',
                'title' => 'Real-Time Transaction Processing',
                'description' => 'The system shall process fund transfers in real-time with immediate balance updates. Transaction confirmation must be displayed within 2 seconds of submission.',
                'priority' => 'critical',
            ],
            [
                'srs_document_id' => 1,
                'requirement_id' => 'FR-TXN-002',
                'section_number' => '4.2.2',
                'title' => 'Transaction History',
                'description' => 'The system shall maintain complete transaction history for a minimum of 7 years. Users can search, filter, and export transactions in PDF and CSV formats.',
                'priority' => 'medium',
            ],
            [
                'srs_document_id' => 1,
                'requirement_id' => 'FR-FRAUD-001',
                'section_number' => '4.3.1',
                'title' => 'Fraud Detection Rules',
                'description' => 'The system shall flag transactions exceeding $5,000 or unusual transaction patterns for manual review. Real-time alerts sent to users for suspicious activity.',
                'priority' => 'critical',
            ],
        ];

        foreach ($functionalRequirements as $reqData) {
            SrsFunctionalRequirement::create($reqData);
        }

        // Add non-functional requirements
        $nonFunctionalRequirements = [
            [
                'srs_document_id' => 1,
                'requirement_id' => 'NFR-PERF-001',
                'section_number' => '5.1.1',
                'category' => 'performance',
                'title' => 'Response Time',
                'description' => 'The system shall respond to 95% of user requests within 1 second under normal load conditions. Transaction processing must complete within 2 seconds.',
                'priority' => 'high',
            ],
            [
                'srs_document_id' => 1,
                'requirement_id' => 'NFR-PERF-002',
                'section_number' => '5.1.2',
                'category' => 'performance',
                'title' => 'Concurrent Users',
                'description' => 'The system shall support 10,000 concurrent users without degradation in performance. Auto-scaling must activate when CPU exceeds 70%.',
                'priority' => 'high',
            ],
            [
                'srs_document_id' => 1,
                'requirement_id' => 'NFR-SEC-001',
                'section_number' => '5.2.1',
                'category' => 'security',
                'title' => 'Data Encryption',
                'description' => 'All data shall be encrypted in transit using TLS 1.3 and at rest using AES-256 encryption. Encryption keys must be rotated every 90 days.',
                'priority' => 'critical',
            ],
            [
                'srs_document_id' => 1,
                'requirement_id' => 'NFR-AVAIL-001',
                'section_number' => '5.3.1',
                'category' => 'availability',
                'title' => 'System Uptime',
                'description' => 'The system shall maintain 99.99% uptime (less than 53 minutes downtime annually). Planned maintenance must occur during off-peak hours with 48-hour notice.',
                'priority' => 'critical',
            ],
        ];

        foreach ($nonFunctionalRequirements as $reqData) {
            SrsNonFunctionalRequirement::create($reqData);
        }

        $this->command->info('✓ Seeded ' . count($documents) . ' SRS documents with requirements');
    }

    /**
     * Seed project diagrams
     * 
     * Note: Diagrams require documentation_id, so we first need to create documentation,
     * then attach diagrams to those documentation entries.
     */
    private function seedDiagrams(): void
    {
        // Diagrams are attached to documentation entries, not directly to projects.
        // We'll create them after documentation is seeded.
        
        // Get documentation IDs from the previously created docs
        $apiDoc = Documentation::where('title', 'Banking API Documentation')->first();
        $arDoc = Documentation::where('title', 'AR Try-On Feature Guide')->first();
        
        $diagrams = [];
        
        if ($apiDoc) {
            $diagrams[] = [
                'documentation_id' => $apiDoc->id,
                'created_by' => 2, // Alice
                'title' => 'System Architecture Diagram',
                'type' => 'flowchart',
                'description' => 'High-level architecture showing microservices, API gateway, databases, and external integrations.',
                'mermaid_syntax' => 'graph TB
    A[Web Client] --> B[API Gateway]
    C[Mobile App] --> B
    B --> D[Auth Service]
    B --> E[Transaction Service]
    B --> F[Account Service]
    E --> G[(Transaction DB)]
    F --> H[(Account DB)]
    D --> I[(User DB)]
    E --> J[Fraud Detection]
    E --> K[External Payment Gateway]',
            ];
            
            $diagrams[] = [
                'documentation_id' => $apiDoc->id,
                'created_by' => 5, // David
                'title' => 'Transaction Flow Diagram',
                'type' => 'flowchart',
                'description' => 'Detailed flow of transaction processing from initiation to completion.',
                'mermaid_syntax' => 'flowchart TD
    A[User Initiates Transfer] --> B{Validate Balance}
    B -->|Insufficient| C[Show Error]
    B -->|Sufficient| D{Check Daily Limit}
    D -->|Exceeded| C
    D -->|Within Limit| E{Fraud Check}
    E -->|Suspicious| F[Manual Review]
    E -->|Clear| G[Process Transaction]
    G --> H[Update Balances]
    H --> I[Send Confirmation]
    F --> J{Approved?}
    J -->|Yes| G
    J -->|No| C',
            ];
        }
        
        if ($arDoc) {
            $diagrams[] = [
                'documentation_id' => $arDoc->id,
                'created_by' => 3, // Bob
                'title' => 'User Journey Map',
                'type' => 'sequence',
                'description' => 'Customer journey from product discovery to purchase completion.',
                'mermaid_syntax' => 'sequenceDiagram
    participant User
    participant App
    participant AI
    participant Payment
    User->>App: Browse Products
    App->>AI: Request Recommendations
    AI-->>App: Personalized Suggestions
    App-->>User: Display Products
    User->>App: Add to Cart
    User->>App: Checkout
    App->>Payment: Process Payment
    Payment-->>App: Confirmation
    App-->>User: Order Confirmed',
            ];
        }

        foreach ($diagrams as $diagramData) {
            Diagram::create($diagramData);
        }

        $this->command->info('✓ Seeded ' . count($diagrams) . ' diagrams');
    }

    /**
     * Seed project documentation
     */
    private function seedDocumentations(): void
    {
        $docs = [
            [
                'project_id' => 1,
                'created_by' => 2, // Alice
                'template_id' => 1, // Assuming API Documentation template exists
                'title' => 'Banking API Documentation',
                'status' => 'approved',
                'content' => '# Cloud Banking Platform API

## Overview
REST API for the Cloud Banking Platform providing secure access to banking operations.

## Authentication
All API requests require Bearer token authentication.

```bash
Authorization: Bearer YOUR_ACCESS_TOKEN
```

## Endpoints

### GET /api/accounts
Retrieve all accounts for authenticated user.

**Response:**
```json
{
  "accounts": [
    {
      "id": "acc_123",
      "type": "checking",
      "balance": 5432.10,
      "currency": "USD"
    }
  ]
}
```

### POST /api/transfers
Create a new fund transfer.

**Request:**
```json
{
  "from_account": "acc_123",
  "to_account": "acc_456",
  "amount": 500.00,
  "description": "Payment"
}
```',
                'version' => 2,
            ],
            [
                'project_id' => 3,
                'created_by' => 3, // Bob
                'template_id' => 2, // Assuming User Guide template exists
                'title' => 'AR Try-On Feature Guide',
                'status' => 'approved',
                'content' => '# Augmented Reality Try-On Feature

## Getting Started
The AR Try-On feature allows customers to virtually try on clothing and accessories using their device camera.

## Requirements
- Modern smartphone or tablet with camera
- Browser with WebXR support (Chrome, Safari)
- Good lighting conditions

## How to Use

1. **Select a Product**: Browse to any product with "Try in AR" badge
2. **Grant Camera Access**: Allow camera permissions when prompted
3. **Position Yourself**: Stand in front of camera with good lighting
4. **Try It On**: The product will appear on you in real-time
5. **Capture Photos**: Take screenshots to save your looks
6. **Add to Cart**: Purchase directly from AR view

## Supported Products
- Eyewear (sunglasses, optical frames)
- Jewelry (earrings, necklaces)
- Watches and accessories
- Hats and headwear

## Troubleshooting
- Ensure adequate lighting
- Keep camera lens clean
- Stand 2-3 feet from camera
- Try landscape orientation for better results',
                'version' => 1,
            ],
        ];

        foreach ($docs as $docData) {
            Documentation::create($docData);
        }

        $this->command->info('✓ Seeded ' . count($docs) . ' documentation entries');
    }

    /**
     * Seed user profiles with skills, certifications, education, and work experience
     */
    private function seedUserProfiles(): void
    {
        // Skills
        $skills = [
            ['user_id' => 2, 'skill_name' => 'Laravel', 'proficiency' => 'expert'],
            ['user_id' => 2, 'skill_name' => 'React', 'proficiency' => 'advanced'],
            ['user_id' => 2, 'skill_name' => 'AWS', 'proficiency' => 'advanced'],
            ['user_id' => 2, 'skill_name' => 'PostgreSQL', 'proficiency' => 'expert'],
            ['user_id' => 2, 'skill_name' => 'Docker', 'proficiency' => 'intermediate'],
            
            ['user_id' => 5, 'skill_name' => 'PHP', 'proficiency' => 'expert'],
            ['user_id' => 5, 'skill_name' => 'Node.js', 'proficiency' => 'advanced'],
            ['user_id' => 5, 'skill_name' => 'MongoDB', 'proficiency' => 'advanced'],
            ['user_id' => 5, 'skill_name' => 'Redis', 'proficiency' => 'intermediate'],
            
            ['user_id' => 6, 'skill_name' => 'JavaScript', 'proficiency' => 'expert'],
            ['user_id' => 6, 'skill_name' => 'TypeScript', 'proficiency' => 'advanced'],
            ['user_id' => 6, 'skill_name' => 'Vue.js', 'proficiency' => 'expert'],
            ['user_id' => 6, 'skill_name' => 'CSS/SASS', 'proficiency' => 'advanced'],
            
            ['user_id' => 8, 'skill_name' => 'Kubernetes', 'proficiency' => 'advanced'],
            ['user_id' => 8, 'skill_name' => 'Terraform', 'proficiency' => 'advanced'],
            ['user_id' => 8, 'skill_name' => 'Jenkins', 'proficiency' => 'intermediate'],
            ['user_id' => 8, 'skill_name' => 'AWS', 'proficiency' => 'expert'],
        ];

        foreach ($skills as $skillData) {
            Skill::create($skillData);
        }

        // Certifications
        $certifications = [
            [
                'user_id' => 2,
                'certification_name' => 'AWS Certified Solutions Architect - Professional',
                'issuer' => 'Amazon Web Services',
                'issue_date' => now()->subYears(1),
                'expiry_date' => now()->addYears(2),
                'credential_url' => 'https://aws.amazon.com/verification/AWS-PSA-2024-1234',
            ],
            [
                'user_id' => 8,
                'certification_name' => 'Certified Kubernetes Administrator (CKA)',
                'issuer' => 'Cloud Native Computing Foundation',
                'issue_date' => now()->subMonths(8),
                'expiry_date' => now()->addYears(2)->subMonths(8),
                'credential_url' => 'https://training.linuxfoundation.org/certification/verify/CKA-2024-5678',
            ],
            [
                'user_id' => 8,
                'certification_name' => 'AWS Certified DevOps Engineer - Professional',
                'issuer' => 'Amazon Web Services',
                'issue_date' => now()->subMonths(6),
                'expiry_date' => now()->addYears(3)->subMonths(6),
                'credential_url' => 'https://aws.amazon.com/verification/AWS-DOP-2024-9012',
            ],
        ];

        foreach ($certifications as $certData) {
            Certification::create($certData);
        }

        // Education
        $education = [
            [
                'user_id' => 2,
                'school_name' => 'Stanford University',
                'degree' => 'Bachelor of Science',
                'field_of_study' => 'Computer Science',
                'start_date' => '2011-09-01',
                'end_date' => '2015-06-01',
                'description' => 'Focus on software engineering, algorithms, and distributed systems. Graduated with honors.',
            ],
            [
                'user_id' => 2,
                'school_name' => 'Carnegie Mellon University',
                'degree' => 'Master of Science',
                'field_of_study' => 'Software Engineering',
                'start_date' => '2015-09-01',
                'end_date' => '2017-05-01',
                'description' => 'Specialized in cloud architecture and scalable systems design. Thesis on microservices patterns.',
            ],
            [
                'user_id' => 4,
                'school_name' => 'Rhode Island School of Design',
                'degree' => 'Bachelor of Fine Arts',
                'field_of_study' => 'Graphic Design',
                'start_date' => '2013-09-01',
                'end_date' => '2017-05-01',
                'description' => 'Emphasis on digital media, user interface design, and design thinking.',
            ],
            [
                'user_id' => 9,
                'school_name' => 'General Assembly',
                'degree' => 'Coding Bootcamp Certificate',
                'field_of_study' => 'Full-Stack Web Development',
                'start_date' => '2024-01-01',
                'end_date' => '2024-04-01',
                'description' => 'Intensive 12-week program covering HTML, CSS, JavaScript, React, Node.js, and database design.',
            ],
        ];

        foreach ($education as $eduData) {
            Education::create($eduData);
        }

        // Work Experience
        $workExperience = [
            [
                'user_id' => 2,
                'company_name' => 'TechCorp Solutions',
                'job_title' => 'Senior Software Engineer',
                'start_date' => '2017-06-01',
                'end_date' => '2020-08-01',
                'description' => 'Led development of enterprise SaaS platform serving 50,000+ users. Architected microservices infrastructure reducing deployment time by 60%. Mentored junior developers and established code review practices.',
                'currently_working' => false,
            ],
            [
                'user_id' => 2,
                'company_name' => 'CloudBank Inc.',
                'job_title' => 'Lead Full-Stack Developer',
                'start_date' => '2020-09-01',
                'end_date' => null,
                'description' => 'Leading engineering team of 8 developers building next-generation banking platform. Responsible for technical architecture, code quality, and mentoring. Implemented CI/CD pipeline improving deployment frequency by 10x.',
                'currently_working' => true,
            ],
            [
                'user_id' => 5,
                'company_name' => 'DataStream Technologies',
                'job_title' => 'Backend Engineer',
                'start_date' => '2018-03-01',
                'end_date' => '2022-12-01',
                'description' => 'Built high-performance APIs processing 1M+ requests per day. Optimized database queries reducing response time by 75%. Designed event-driven architecture for real-time data processing.',
                'currently_working' => false,
            ],
            [
                'user_id' => 5,
                'company_name' => 'GrowDev',
                'job_title' => 'Senior Backend Engineer',
                'start_date' => '2023-01-01',
                'end_date' => null,
                'description' => 'Architecting scalable backend systems for healthcare and financial applications. Focus on security, performance, and reliability. Leading database optimization initiatives.',
                'currently_working' => true,
            ],
        ];

        foreach ($workExperience as $expData) {
            WorkExperience::create($expData);
        }

        $this->command->info('✓ Seeded user profiles with skills, certifications, education, and experience');
    }

    /**
     * Seed task activities to show collaboration
     */
    private function seedTaskActivities(): void
    {
        $activities = [
            [
                'task_id' => 2,
                'user_id' => 5,
                'action' => 'comment',
                'notes' => 'Started implementing the transaction processing engine. Setup the basic infrastructure with queue workers and database schema.',
            ],
            [
                'task_id' => 2,
                'user_id' => 2,
                'action' => 'comment',
                'notes' => 'Great progress! Make sure to implement proper transaction rollback mechanisms for failed operations.',
            ],
            [
                'task_id' => 4,
                'user_id' => 5,
                'action' => 'status_change',
                'old_status' => 'In Progress',
                'new_status' => 'Review',
                'notes' => 'Ready for review',
            ],
            [
                'task_id' => 7,
                'user_id' => 9,
                'action' => 'comment',
                'notes' => 'Working on the patient portal frontend. Implementing the appointment scheduling calendar component first.',
            ],
            [
                'task_id' => 11,
                'user_id' => 6,
                'action' => 'comment',
                'notes' => 'AR try-on feature is technically challenging but making progress. Currently working on face detection and mesh overlay.',
            ],
        ];

        foreach ($activities as $activityData) {
            TaskActivity::create($activityData);
        }

        $this->command->info('✓ Seeded ' . count($activities) . ' task activities');
    }

    /**
     * Seed notification preferences for users
     */
    private function seedNotificationPreferences(): void
    {
        $preferences = [
            [
                'user_id' => 2,
                'email_on_task_assigned' => true,
                'email_on_task_status_change' => true,
                'email_reminders' => true,
                'email_on_team_invitation' => true,
                'email_on_srs_update' => true,
                'digest_frequency' => 'daily',
                'digest_time' => '09:00',
                'timezone' => 'America/Los_Angeles',
            ],
            [
                'user_id' => 3,
                'email_on_task_assigned' => true,
                'email_on_task_status_change' => false,
                'email_reminders' => true,
                'email_on_team_invitation' => true,
                'email_on_srs_update' => false,
                'digest_frequency' => 'weekly',
                'digest_day' => 'mon',
                'digest_time' => '08:00',
                'timezone' => 'America/Chicago',
            ],
            [
                'user_id' => 6,
                'email_on_task_assigned' => true,
                'email_on_task_status_change' => true,
                'email_reminders' => false,
                'email_on_team_invitation' => true,
                'email_on_srs_update' => true,
                'digest_frequency' => 'daily',
                'digest_time' => '10:00',
                'timezone' => 'America/Denver',
            ],
        ];

        foreach ($preferences as $prefData) {
            NotificationPreference::create($prefData);
        }

        $this->command->info('✓ Seeded ' . count($preferences) . ' notification preferences');
    }
}
