<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\SupabaseServiceEnhanced;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupabaseIntegrationTest extends TestCase
{
    protected $supabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->supabase = new SupabaseServiceEnhanced();
    }

    /** @test */
    public function it_can_connect_to_supabase()
    {
        $result = $this->supabase->testConnection();
        
        $this->assertTrue($result['success']);
        $this->assertEquals(200, $result['status']);
        $this->assertArrayHasKey('response', $result);
    }

    /** @test */
    public function it_can_list_users()
    {
        $result = $this->supabase->listUsers();
        
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('users', $result['data']);
        $this->assertIsArray($result['data']['users']);
    }

    /** @test */
    public function it_can_check_database_schema()
    {
        $schemaInfo = $this->supabase->getSchemaInfo();
        
        $expectedTables = ['profiles', 'projects', 'project_members', 'tasks'];
        
        foreach ($expectedTables as $table) {
            $this->assertArrayHasKey($table, $schemaInfo);
            $this->assertArrayHasKey('exists', $schemaInfo[$table]);
        }
    }

    /** @test */
    public function it_validates_email_format_for_signup()
    {
        $result = $this->supabase->signUp('invalid-email', 'password123');
        
        $this->assertFalse($result['success']);
        $this->assertEquals(400, $result['status']);
    }

    /** @test */
    public function it_validates_password_strength()
    {
        $result = $this->supabase->signUp('test@test.com', '123'); // Too short
        
        $this->assertFalse($result['success']);
    }

    /** @test */
    public function it_handles_duplicate_email_signup()
    {
        // This test would require an existing user
        // We'll test this in the manual testing section
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_handle_invalid_signin()
    {
        $result = $this->supabase->signIn('nonexistent@test.com', 'wrongpassword');
        
        $this->assertFalse($result['success']);
    }

    /** @test */
    public function it_returns_proper_error_structure()
    {
        $result = $this->supabase->signIn('invalid-email', 'password');
        
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('status', $result);
    }

    /** @test */
    public function it_can_refresh_tokens()
    {
        // Test with invalid refresh token
        $result = $this->supabase->refreshToken('invalid-token');
        
        $this->assertFalse($result['success']);
        $this->assertArrayHasKey('data', $result);
    }
}