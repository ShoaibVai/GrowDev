<?php

/**
 * Manual Test Script for Supabase Integration
 * Run this with: php artisan tinker < tests/manual_supabase_test.php
 */

use App\Services\SupabaseServiceEnhanced;
use Illuminate\Support\Facades\Log;

echo "\n🧪 SUPABASE INTEGRATION TEST SUITE\n";
echo "==================================\n\n";

// Initialize service
$supabase = new SupabaseServiceEnhanced();
$testResults = [];

// Test 1: Connection Test
echo "📡 Test 1: Connection Test\n";
echo "-------------------------\n";
try {
    $result = $supabase->testConnection();
    $testResults['connection'] = $result['success'];
    
    if ($result['success']) {
        echo "✅ PASS: Connection successful (Status: {$result['status']})\n";
    } else {
        echo "❌ FAIL: Connection failed - {$result['error']}\n";
    }
} catch (Exception $e) {
    echo "❌ FAIL: Connection exception - {$e->getMessage()}\n";
    $testResults['connection'] = false;
}
echo "\n";

// Test 2: User List Test
echo "👥 Test 2: User List Test\n";
echo "------------------------\n";
try {
    $result = $supabase->listUsers();
    $testResults['user_list'] = $result['success'];
    
    if ($result['success']) {
        $userCount = count($result['data']['users'] ?? []);
        echo "✅ PASS: User list retrieved successfully ({$userCount} users)\n";
        
        if ($userCount > 0) {
            echo "   First user: " . ($result['data']['users'][0]['email'] ?? 'No email') . "\n";
        }
    } else {
        echo "❌ FAIL: User list failed - " . ($result['error'] ?? 'Unknown error') . "\n";
    }
} catch (Exception $e) {
    echo "❌ FAIL: User list exception - {$e->getMessage()}\n";
    $testResults['user_list'] = false;
}
echo "\n";

// Test 3: Schema Check Test
echo "🗄️  Test 3: Schema Check Test\n";
echo "----------------------------\n";
try {
    $schemaInfo = $supabase->getSchemaInfo();
    $expectedTables = ['profiles', 'projects', 'project_members', 'tasks'];
    $existingTables = [];
    
    foreach ($expectedTables as $table) {
        if (isset($schemaInfo[$table]) && ($schemaInfo[$table]['exists'] ?? false)) {
            $existingTables[] = $table;
            echo "✅ Table '{$table}' exists\n";
        } else {
            echo "⚠️  Table '{$table}' missing\n";
        }
    }
    
    $testResults['schema'] = count($existingTables) > 0;
    echo "   Summary: " . count($existingTables) . "/" . count($expectedTables) . " tables exist\n";
} catch (Exception $e) {
    echo "❌ FAIL: Schema check exception - {$e->getMessage()}\n";
    $testResults['schema'] = false;
}
echo "\n";

// Test 4: Invalid Email Test
echo "📧 Test 4: Invalid Email Test\n";
echo "----------------------------\n";
try {
    $result = $supabase->signUp('invalid-email', 'password123');
    $testResults['invalid_email'] = !$result['success']; // Should fail
    
    if (!$result['success']) {
        echo "✅ PASS: Invalid email correctly rejected\n";
        echo "   Error: " . ($result['data']['msg'] ?? 'No message') . "\n";
    } else {
        echo "❌ FAIL: Invalid email was accepted (should have been rejected)\n";
    }
} catch (Exception $e) {
    echo "❌ FAIL: Invalid email test exception - {$e->getMessage()}\n";
    $testResults['invalid_email'] = false;
}
echo "\n";

// Test 5: Short Password Test
echo "🔒 Test 5: Short Password Test\n";
echo "-----------------------------\n";
try {
    $result = $supabase->signUp('test@test.com', '123'); // Too short
    $testResults['short_password'] = !$result['success']; // Should fail
    
    if (!$result['success']) {
        echo "✅ PASS: Short password correctly rejected\n";
        echo "   Error: " . ($result['data']['msg'] ?? 'No message') . "\n";
    } else {
        echo "❌ FAIL: Short password was accepted (should have been rejected)\n";
    }
} catch (Exception $e) {
    echo "❌ FAIL: Short password test exception - {$e->getMessage()}\n";
    $testResults['short_password'] = false;
}
echo "\n";

// Test 6: Invalid Login Test
echo "🔑 Test 6: Invalid Login Test\n";
echo "-----------------------------\n";
try {
    $result = $supabase->signIn('nonexistent@test.com', 'wrongpassword');
    $testResults['invalid_login'] = !$result['success']; // Should fail
    
    if (!$result['success']) {
        echo "✅ PASS: Invalid login correctly rejected\n";
        echo "   Error: " . ($result['data']['msg'] ?? 'No message') . "\n";
    } else {
        echo "❌ FAIL: Invalid login was accepted (should have been rejected)\n";
    }
} catch (Exception $e) {
    echo "❌ FAIL: Invalid login test exception - {$e->getMessage()}\n";
    $testResults['invalid_login'] = false;
}
echo "\n";

// Test 7: Response Structure Test
echo "📋 Test 7: Response Structure Test\n";
echo "---------------------------------\n";
try {
    $result = $supabase->signIn('test@test.com', 'password123');
    
    $hasSuccess = array_key_exists('success', $result);
    $hasData = array_key_exists('data', $result);
    $hasStatus = array_key_exists('status', $result);
    
    $testResults['response_structure'] = $hasSuccess && $hasData && $hasStatus;
    
    if ($testResults['response_structure']) {
        echo "✅ PASS: Response has proper structure (success, data, status)\n";
    } else {
        echo "❌ FAIL: Response missing required fields\n";
        echo "   Has success: " . ($hasSuccess ? 'Yes' : 'No') . "\n";
        echo "   Has data: " . ($hasData ? 'Yes' : 'No') . "\n";
        echo "   Has status: " . ($hasStatus ? 'Yes' : 'No') . "\n";
    }
} catch (Exception $e) {
    echo "❌ FAIL: Response structure test exception - {$e->getMessage()}\n";
    $testResults['response_structure'] = false;
}
echo "\n";

// Test 8: Refresh Token Test
echo "🔄 Test 8: Refresh Token Test\n";
echo "-----------------------------\n";
try {
    $result = $supabase->refreshToken('invalid-refresh-token');
    $testResults['refresh_token'] = !$result['success']; // Should fail with invalid token
    
    if (!$result['success']) {
        echo "✅ PASS: Invalid refresh token correctly rejected\n";
    } else {
        echo "❌ FAIL: Invalid refresh token was accepted\n";
    }
} catch (Exception $e) {
    echo "❌ FAIL: Refresh token test exception - {$e->getMessage()}\n";
    $testResults['refresh_token'] = false;
}
echo "\n";

// Test Summary
echo "📊 TEST SUMMARY\n";
echo "===============\n";
$passed = 0;
$total = count($testResults);

foreach ($testResults as $test => $result) {
    $status = $result ? '✅ PASS' : '❌ FAIL';
    echo "{$status} {$test}\n";
    if ($result) $passed++;
}

echo "\n";
echo "Results: {$passed}/{$total} tests passed\n";

if ($passed == $total) {
    echo "🎉 ALL TESTS PASSED! Supabase integration is working correctly.\n";
} elseif ($passed > 0) {
    echo "⚠️  PARTIAL SUCCESS. Some functionality is working.\n";
} else {
    echo "❌ ALL TESTS FAILED. Check your Supabase configuration.\n";
}

echo "\n🔧 Next Steps:\n";
echo "1. If schema tests failed, run the SQL script in Supabase dashboard\n";
echo "2. Test real user creation with valid email via web interface\n";
echo "3. Configure site URL in Supabase dashboard for redirects\n";
echo "4. Test email confirmation flow\n";
echo "\n";