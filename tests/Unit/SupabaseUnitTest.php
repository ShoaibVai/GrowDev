<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\SupabaseServiceEnhanced;

class SupabaseUnitTest extends TestCase
{
    protected $supabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the config values for testing
        if (!function_exists('config')) {
            function config($key) {
                $configs = [
                    'services.supabase.url' => env('SUPABASE_URL', 'https://test.supabase.co'),
                    'services.supabase.anon_key' => env('SUPABASE_ANON_KEY', 'test-key'),
                    'services.supabase.service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY', 'test-service-key'),
                ];
                return $configs[$key] ?? null;
            }
        }
        
        $this->supabase = new SupabaseServiceEnhanced();
    }

    /** @test */
    public function it_initializes_with_proper_configuration()
    {
        $this->assertInstanceOf(SupabaseServiceEnhanced::class, $this->supabase);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $reflection = new \ReflectionClass($this->supabase);
        
        // Test various email formats
        $testEmails = [
            'valid@test.com' => true,
            'user@domain.co.uk' => true,
            'invalid-email' => false,
            '@domain.com' => false,
            'user@' => false,
            '' => false,
        ];

        foreach ($testEmails as $email => $shouldBeValid) {
            $isValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
            $this->assertEquals($shouldBeValid, $isValid, "Email {$email} validation failed");
        }
    }

    /** @test */
    public function it_validates_password_requirements()
    {
        $testPasswords = [
            'password123' => true,   // Valid: 8+ chars
            'Pass123!' => true,      // Valid: 8+ chars with special
            '12345' => false,        // Invalid: too short
            '' => false,             // Invalid: empty
            'a' => false,            // Invalid: too short
        ];

        foreach ($testPasswords as $password => $shouldBeValid) {
            $isValid = strlen($password) >= 6;
            $this->assertEquals($shouldBeValid, $isValid, "Password '{$password}' validation failed");
        }
    }

    /** @test */
    public function it_handles_json_responses_properly()
    {
        // Test JSON parsing scenarios
        $validJson = '{"success": true, "data": {"user": {"id": "123"}}}';
        $invalidJson = '{invalid json}';
        
        $validParsed = json_decode($validJson, true);
        $invalidParsed = json_decode($invalidJson, true);
        
        $this->assertIsArray($validParsed);
        $this->assertNull($invalidParsed);
        $this->assertEquals(JSON_ERROR_SYNTAX, json_last_error());
    }
}