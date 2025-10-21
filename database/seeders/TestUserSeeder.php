<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone_number' => '+1-555-0123',
                'location' => 'San Francisco, CA',
                'professional_summary' => 'Full-stack developer with 5+ years of experience in web development.',
                'website' => 'https://example.com',
                'linkedin_url' => 'https://linkedin.com/in/testuser',
                'github_url' => 'https://github.com/testuser',
            ]
        );

        // Add TOTP secret if not present
        if (!$user->totp_secret) {
            $user->totp_secret = (new Google2FA())->generateSecretKey();
            $user->save();
        }

        // Clear existing relationships for fresh test data
        $user->workExperiences()->delete();
        $user->educations()->delete();
        $user->skills()->delete();
        $user->certifications()->delete();

        // Add some sample work experience
        $user->workExperiences()->create([
            'job_title' => 'Senior Software Engineer',
            'company_name' => 'Tech Corp',
            'description' => 'Led development of microservices architecture.',
            'start_date' => now()->subYears(2),
            'end_date' => null,
            'currently_working' => true,
            'order' => 0,
        ]);

        $user->workExperiences()->create([
            'job_title' => 'Junior Developer',
            'company_name' => 'StartUp Inc',
            'description' => 'Full-stack web development using Laravel and Vue.js.',
            'start_date' => now()->subYears(4),
            'end_date' => now()->subYears(2),
            'currently_working' => false,
            'order' => 1,
        ]);

        // Add education
        $user->educations()->create([
            'school_name' => 'University of Technology',
            'degree' => 'Bachelor',
            'field_of_study' => 'Computer Science',
            'description' => 'GPA: 3.8/4.0',
            'start_date' => now()->subYears(7),
            'end_date' => now()->subYears(3),
            'order' => 0,
        ]);

        // Add skills
        $skills = [
            ['skill_name' => 'PHP', 'proficiency' => 'expert'],
            ['skill_name' => 'Laravel', 'proficiency' => 'expert'],
            ['skill_name' => 'JavaScript', 'proficiency' => 'advanced'],
            ['skill_name' => 'Vue.js', 'proficiency' => 'advanced'],
            ['skill_name' => 'MySQL', 'proficiency' => 'advanced'],
            ['skill_name' => 'Docker', 'proficiency' => 'intermediate'],
        ];

        foreach ($skills as $index => $skill) {
            $user->skills()->create([
                'skill_name' => $skill['skill_name'],
                'proficiency' => $skill['proficiency'],
                'order' => $index,
            ]);
        }

        // Add certifications
        $user->certifications()->create([
            'certification_name' => 'Laravel Certified Developer',
            'issuer' => 'Laravel',
            'description' => 'Certified Laravel Developer',
            'issue_date' => now()->subYear(),
            'expiry_date' => null,
            'credential_url' => 'https://laravel.com/certs',
            'order' => 0,
        ]);

        $this->command->info('Test user created successfully!');
        $this->command->info('Email: test@example.com');
        $this->command->info('Password: password');
    }
}
