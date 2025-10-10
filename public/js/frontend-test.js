// Supabase Frontend Integration Test
// Copy and paste this into browser console at http://localhost:8000/supabase-test

console.log('🧪 FRONTEND SUPABASE INTEGRATION TEST');
console.log('=====================================');

// Test 1: Check if Supabase client is available
console.log('\n📦 Test 1: Supabase Client Availability');
try {
    // Check if the client exists in the global scope or can be imported
    if (typeof window.supabase !== 'undefined') {
        console.log('✅ PASS: Supabase client available globally');
    } else {
        console.log('⚠️  INFO: Supabase client not global (normal for module imports)');
    }
} catch (error) {
    console.log('❌ FAIL: Supabase client check failed:', error.message);
}

// Test 2: Test API endpoints via fetch
console.log('\n📡 Test 2: API Endpoint Tests');

async function testEndpoint(url, method = 'GET', data = null) {
    try {
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        };
        
        if (data) {
            options.body = JSON.stringify(data);
        }
        
        const response = await fetch(url, options);
        const result = await response.json();
        
        return {
            success: response.ok,
            status: response.status,
            data: result
        };
    } catch (error) {
        return {
            success: false,
            error: error.message
        };
    }
}

// Test connection endpoint
testEndpoint('/supabase-test/connection')
    .then(result => {
        if (result.success) {
            console.log('✅ PASS: Connection endpoint working');
        } else {
            console.log('❌ FAIL: Connection endpoint failed:', result.error);
        }
    });

// Test users endpoint
testEndpoint('/supabase-test/users')
    .then(result => {
        if (result.success) {
            console.log('✅ PASS: Users endpoint working');
            console.log('   User count:', result.data.count || 0);
        } else {
            console.log('❌ FAIL: Users endpoint failed:', result.error);
        }
    });

// Test schema endpoint
testEndpoint('/supabase-test/schema')
    .then(result => {
        if (result.success) {
            console.log('✅ PASS: Schema endpoint working');
            console.log('   Tables found:', result.data.tables_count || 0);
        } else {
            console.log('❌ FAIL: Schema endpoint failed:', result.error);
        }
    });

// Test 3: Form Validation
console.log('\n📝 Test 3: Form Validation');

// Test invalid email signup
testEndpoint('/supabase-test/signup', 'POST', {
    email: 'invalid-email',
    password: 'password123',
    name: 'Test User'
})
.then(result => {
    if (!result.success) {
        console.log('✅ PASS: Invalid email correctly rejected');
    } else {
        console.log('❌ FAIL: Invalid email was accepted');
    }
});

// Test short password
testEndpoint('/supabase-test/signup', 'POST', {
    email: 'test@test.com',
    password: '123',
    name: 'Test User'
})
.then(result => {
    if (!result.success) {
        console.log('✅ PASS: Short password correctly rejected');
    } else {
        console.log('❌ FAIL: Short password was accepted');
    }
});

// Test 4: CSRF Protection
console.log('\n🔒 Test 4: CSRF Protection');
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    console.log('✅ PASS: CSRF token found in meta tag');
} else {
    console.log('❌ FAIL: CSRF token missing');
}

// Test 5: Alpine.js Functionality
console.log('\n🏔️  Test 5: Alpine.js Integration');
setTimeout(() => {
    const alpineElements = document.querySelectorAll('[x-data]');
    if (alpineElements.length > 0) {
        console.log('✅ PASS: Alpine.js elements found:', alpineElements.length);
        
        // Check if Alpine is actually loaded
        if (typeof window.Alpine !== 'undefined') {
            console.log('✅ PASS: Alpine.js is loaded');
        } else {
            console.log('⚠️  INFO: Alpine.js not in global scope (may be loaded differently)');
        }
    } else {
        console.log('❌ FAIL: No Alpine.js elements found');
    }
}, 1000);

// Test 6: Tailwind CSS
console.log('\n🎨 Test 6: Tailwind CSS');
const tailwindElement = document.querySelector('.bg-gray-100');
if (tailwindElement) {
    const styles = window.getComputedStyle(tailwindElement);
    if (styles.backgroundColor) {
        console.log('✅ PASS: Tailwind CSS is working');
    } else {
        console.log('❌ FAIL: Tailwind CSS not applied');
    }
} else {
    console.log('⚠️  INFO: No Tailwind test element found');
}

console.log('\n🎉 Frontend tests completed!');
console.log('Check the network tab for API call details.');
console.log('Try the interactive form to test real user creation.');

// Helper function to test a complete signup flow
window.testSignupFlow = async function(email, password, name = 'Test User') {
    console.log(`\n🔄 Testing signup flow for: ${email}`);
    
    const result = await testEndpoint('/supabase-test/signup', 'POST', {
        email,
        password,
        name
    });
    
    if (result.success) {
        console.log('✅ Signup successful!');
        console.log('Response:', result.data);
        
        // Test signin with same credentials
        const signinResult = await testEndpoint('/supabase-test/signin', 'POST', {
            email,
            password
        });
        
        if (signinResult.success) {
            console.log('✅ Signin also successful!');
        } else {
            console.log('⚠️  Signup worked but signin failed (may need email confirmation)');
        }
    } else {
        console.log('❌ Signup failed:', result.data?.msg || result.error);
    }
    
    return result;
};

console.log('\n💡 Usage Examples:');
console.log('testSignupFlow("your.email@gmail.com", "yourpassword123", "Your Name")');
console.log('testSignupFlow("test@yourdomain.com", "testpass123")');