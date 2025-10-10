<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSupabaseEndpoint extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:supabase-endpoint';

    /**
     * The console command description.
     */
    protected $description = 'Test Supabase endpoints directly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Supabase Endpoints Directly');
        $this->newLine();

        // Test connection endpoint
        $this->testEndpoint('GET', '/supabase-test/connection', null, 'Connection Test');

        // Test invalid signup
        $this->testEndpoint('POST', '/supabase-test/signup', [
            'email' => 'invalid-email',
            'password' => 'test123',
            'name' => 'Test User'
        ], 'Invalid Email Test');

        // Test valid format but invalid domain
        $this->testEndpoint('POST', '/supabase-test/signup', [
            'email' => 'test@test.com',
            'password' => 'password123',
            'name' => 'Test User'
        ], 'Valid Format Test');

        return 0;
    }

    private function testEndpoint($method, $url, $data, $testName)
    {
        $this->line("📋 {$testName}");
        $this->line(str_repeat('-', strlen($testName) + 4));

        try {
            $fullUrl = 'http://localhost:8000' . $url;
            
            if ($method === 'GET') {
                $response = file_get_contents($fullUrl);
            } else {
                $postData = json_encode($data);
                $context = stream_context_create([
                    'http' => [
                        'method' => $method,
                        'header' => "Content-Type: application/json\r\n" .
                                   "Content-Length: " . strlen($postData) . "\r\n",
                        'content' => $postData
                    ]
                ]);
                $response = file_get_contents($fullUrl, false, $context);
            }

            if ($response === false) {
                $this->error("❌ Request failed");
                return;
            }

            $decoded = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->info("✅ Valid JSON response");
                $this->line("   Success: " . ($decoded['success'] ? 'true' : 'false'));
                $this->line("   Message: " . ($decoded['message'] ?? 'No message'));
            } else {
                $this->error("❌ Invalid JSON response");
                $this->line("   Response: " . substr($response, 0, 200) . '...');
            }

        } catch (\Exception $e) {
            $this->error("❌ Exception: " . $e->getMessage());
        }

        $this->newLine();
    }
}