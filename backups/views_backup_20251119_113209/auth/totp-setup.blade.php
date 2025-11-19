<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Setup Two-Factor Authentication</h2>
        <p class="mb-4">Congratulations! Your account has been created successfully.</p>
        <p class="mb-4">Please save your authentication key below. You'll need this key to reset your password in the future.</p>
    </div>

    <!-- TOTP Secret Key Display -->
    <div class="mb-6 p-6 bg-blue-50 border-2 border-blue-400 rounded-lg">
        <div class="flex items-center mb-3">
            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-blue-900">Your Authentication Key</h3>
        </div>
        
        <div class="bg-white p-4 rounded border border-blue-200 mb-3">
            <div class="flex items-center justify-between">
                <code id="totp-secret" class="text-2xl font-mono font-bold text-gray-900 tracking-wider">
                    {{ session('totp_secret') }}
                </code>
                <button 
                    onclick="copySecret()" 
                    class="ml-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                    title="Copy to clipboard"
                >
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy
                </button>
            </div>
        </div>

        <div class="text-sm text-blue-800">
            <p class="font-semibold mb-2">⚠️ Important:</p>
            <ul class="list-disc list-inside space-y-1 ml-2">
                <li>Save this key in a secure location (password manager, secure notes, etc.)</li>
                <li>You will need this key to reset your password</li>
                <li>This key will not be shown again after you leave this page</li>
                <li>Without this key, password recovery will not be possible</li>
            </ul>
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="mb-6 p-6 bg-green-50 border-2 border-green-400 rounded-lg">
        <div class="flex items-center mb-3">
            <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-green-900">Add to Authenticator App (Optional)</h3>
        </div>

        <p class="text-sm text-green-800 mb-4">
            You can also add this key to an authenticator app (like Google Authenticator, Authy, or Microsoft Authenticator) for easy access:
        </p>

        <div class="bg-white p-6 rounded border border-green-200 text-center">
            <div class="mb-4">
                <img 
                    src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(session('qr_code_url')) }}" 
                    alt="QR Code" 
                    class="mx-auto border-4 border-gray-300 rounded"
                />
            </div>
            <p class="text-sm text-gray-600">
                Scan this QR code with your authenticator app
            </p>
        </div>

        <div class="mt-4 text-sm text-green-800">
            <p class="font-semibold mb-2">How to use with authenticator apps:</p>
            <ol class="list-decimal list-inside space-y-1 ml-2">
                <li>Open your authenticator app (Google Authenticator, Authy, etc.)</li>
                <li>Scan the QR code above OR manually enter the key</li>
                <li>The app will generate 6-digit codes that refresh every 30 seconds</li>
                <li>Use these codes when resetting your password</li>
            </ol>
        </div>
    </div>

    <!-- Account Information -->
    <div class="mb-6 p-4 bg-gray-50 border border-gray-300 rounded">
        <p class="text-sm text-gray-700">
            <strong>Account Email:</strong> {{ session('user_email') }}
        </p>
    </div>

    <!-- Continue Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('login') }}" class="w-full inline-flex justify-center items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            I've Saved My Key - Continue to Login
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>

    <!-- Additional Warning -->
    <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-500">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    <strong>Final Warning:</strong> Make absolutely sure you have saved your authentication key before continuing. 
                    You will not be able to recover your account if you forget your password and lose this key.
                </p>
            </div>
        </div>
    </div>

    <script>
        function copySecret() {
            const secret = document.getElementById('totp-secret').textContent.trim();
            navigator.clipboard.writeText(secret).then(() => {
                // Show success feedback
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = `
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Copied!
                `;
                button.classList.add('bg-green-600');
                button.classList.remove('bg-blue-600');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-blue-600');
                }, 2000);
            }).catch(err => {
                alert('Failed to copy. Please select and copy manually.');
            });
        }
    </script>
</x-guest-layout>
