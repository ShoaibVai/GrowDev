<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supabase Test Interface</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8" x-data="supabaseTest()">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">🧪 Supabase Test Interface</h1>
            
            <!-- Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="font-semibold text-gray-700">Connection</h3>
                    <div class="mt-2">
                        <span x-show="connectionStatus === 'testing'" class="text-yellow-600">Testing...</span>
                        <span x-show="connectionStatus === 'success'" class="text-green-600">✅ Connected</span>
                        <span x-show="connectionStatus === 'error'" class="text-red-600">❌ Failed</span>
                        <span x-show="connectionStatus === null" class="text-gray-500">Not tested</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="font-semibold text-gray-700">Users</h3>
                    <div class="mt-2">
                        <span x-text="userCount >= 0 ? userCount + ' users' : 'Unknown'" class="text-blue-600"></span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="font-semibold text-gray-700">Schema</h3>
                    <div class="mt-2">
                        <span x-text="tablesCount >= 0 ? tablesCount + '/4 tables' : 'Unknown'" class="text-purple-600"></span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="font-semibold text-gray-700">Last Test</h3>
                    <div class="mt-2">
                        <span x-show="lastTestResult === 'success'" class="text-green-600">✅ Success</span>
                        <span x-show="lastTestResult === 'error'" class="text-red-600">❌ Failed</span>
                        <span x-show="lastTestResult === null" class="text-gray-500">None</span>
                    </div>
                </div>
            </div>

            <!-- Test Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Quick Tests</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button @click="testConnection()" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Test Connection
                    </button>
                    <button @click="listUsers()" 
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        List Users
                    </button>
                    <button @click="checkSchema()" 
                            class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg">
                        Check Schema
                    </button>
                    <button @click="clearResults()" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Clear Results
                    </button>
                </div>
            </div>

            <!-- Authentication Test Forms -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Signup Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">🔐 Test Signup</h2>
                    <form @submit.prevent="testSignup()">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" x-model="signupForm.email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" x-model="signupForm.password" required minlength="6"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name (optional)</label>
                            <input type="text" x-model="signupForm.name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" :disabled="loading"
                                class="w-full bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg">
                            <span x-show="!loading">Create Account</span>
                            <span x-show="loading">Creating...</span>
                        </button>
                    </form>
                </div>

                <!-- Signin Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">🔑 Test Signin</h2>
                    <form @submit.prevent="testSignin()">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" x-model="signinForm.email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" x-model="signinForm.password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <button type="submit" :disabled="loading"
                                class="w-full bg-green-500 hover:bg-green-600 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg">
                            <span x-show="!loading">Sign In</span>
                            <span x-show="loading">Signing in...</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Results Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">📋 Test Results</h2>
                <div x-show="results.length === 0" class="text-gray-500 italic">
                    No tests run yet. Use the buttons above to test functionality.
                </div>
                <div x-show="results.length > 0" class="space-y-4">
                    <template x-for="result in results.slice().reverse()" :key="result.id">
                        <div class="border rounded-lg p-4" 
                             :class="result.success ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold" 
                                    :class="result.success ? 'text-green-800' : 'text-red-800'"
                                    x-text="result.title"></h3>
                                <span class="text-sm text-gray-500" x-text="result.timestamp"></span>
                            </div>
                            <p class="text-sm mb-2" 
                               :class="result.success ? 'text-green-700' : 'text-red-700'"
                               x-text="result.message"></p>
                            <details x-show="result.data" class="text-xs">
                                <summary class="cursor-pointer text-gray-600 hover:text-gray-800">View Details</summary>
                                <pre class="mt-2 p-2 bg-gray-100 rounded overflow-x-auto" x-text="JSON.stringify(result.data, null, 2)"></pre>
                            </details>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        function supabaseTest() {
            return {
                loading: false,
                connectionStatus: null,
                userCount: -1,
                tablesCount: -1,
                lastTestResult: null,
                results: [],
                signupForm: {
                    email: 'test@yourcompany.com',
                    password: 'testpass123',
                    name: 'Test User'
                },
                signinForm: {
                    email: '',
                    password: ''
                },

                init() {
                    // Set up CSRF token for all requests
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    window.axios = window.axios || {};
                    window.axios.defaults = window.axios.defaults || {};
                    window.axios.defaults.headers = window.axios.defaults.headers || {};
                    window.axios.defaults.headers.common = window.axios.defaults.headers.common || {};
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
                },

                addResult(title, success, message, data = null) {
                    this.results.push({
                        id: Date.now(),
                        title,
                        success,
                        message,
                        data,
                        timestamp: new Date().toLocaleTimeString()
                    });
                    this.lastTestResult = success ? 'success' : 'error';
                },

                async makeRequest(url, data = null) {
                    try {
                        const headers = {
                            'Content-Type': 'application/json'
                        };

                        // Only add CSRF token if it exists
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (csrfToken) {
                            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                        }

                        const response = data 
                            ? await fetch(url, {
                                method: 'POST',
                                headers,
                                body: JSON.stringify(data)
                            })
                            : await fetch(url);
                        
                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return await response.json();
                        } else {
                            // If not JSON, try to get text and create error response
                            const text = await response.text();
                            console.error('Non-JSON response:', text.substring(0, 200));
                            return { 
                                success: false, 
                                message: `Server returned non-JSON response (${response.status}): ${response.statusText}`,
                                status: response.status
                            };
                        }
                    } catch (error) {
                        console.error('Request failed:', error);
                        return { success: false, message: `Request failed: ${error.message}` };
                    }
                },

                async testConnection() {
                    this.connectionStatus = 'testing';
                    const result = await this.makeRequest('/supabase-test/connection');
                    this.connectionStatus = result.success ? 'success' : 'error';
                    this.addResult('Connection Test', result.success, result.message, result);
                },

                async listUsers() {
                    const result = await this.makeRequest('/supabase-test/users');
                    this.userCount = result.count || 0;
                    this.addResult('List Users', result.success, result.message, result);
                },

                async checkSchema() {
                    const result = await this.makeRequest('/supabase-test/schema');
                    this.tablesCount = result.tables_count || 0;
                    this.addResult('Schema Check', result.success, 
                        `Found ${result.tables_count} tables`, result);
                },

                async testSignup() {
                    this.loading = true;
                    try {
                        const result = await this.makeRequest('/supabase-test/signup', this.signupForm);
                        this.addResult('User Signup', result.success, result.message, result);
                        
                        if (result.success) {
                            // Clear form and copy email to signin
                            this.signinForm.email = this.signupForm.email;
                            this.signinForm.password = this.signupForm.password;
                            this.signupForm = { email: '', password: '', name: '' };
                        }
                    } finally {
                        this.loading = false;
                    }
                },

                async testSignin() {
                    this.loading = true;
                    try {
                        const result = await this.makeRequest('/supabase-test/signin', this.signinForm);
                        this.addResult('User Signin', result.success, result.message, result);
                    } finally {
                        this.loading = false;
                    }
                },

                clearResults() {
                    this.results = [];
                    this.lastTestResult = null;
                }
            }
        }
    </script>
</body>
</html>