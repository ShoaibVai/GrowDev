<x-guest-layout>
    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Reset Your Password</h2>
        <p class="mt-2 text-sm text-gray-600">Authenticate with your email and authenticator app</p>
    </div>

    <!-- Instructions -->
    <div class="mb-4 bg-indigo-50 border border-indigo-200 text-indigo-800 px-4 py-3 rounded relative text-sm" role="alert">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-indigo-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <div>
                <strong class="font-bold">No Email Required:</strong>
                <span class="block mt-1">Simply enter your email and the current 6-digit code from your authenticator app to reset your password. No email will be sent.</span>
            </div>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- TOTP Code -->
        <div class="mt-4">
            <x-input-label for="totp_code" :value="__('Authenticator Code')" />
            <div class="mb-2 text-xs text-gray-600 bg-yellow-50 p-3 rounded border border-yellow-300">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <strong class="block mb-1">Open your authenticator app:</strong>
                        <ol class="list-decimal list-inside space-y-1 text-xs">
                            <li>Find the entry for "{{ config('app.name') }}"</li>
                            <li>Enter the current 6-digit code</li>
                            <li>Code refreshes every 30 seconds</li>
                        </ol>
                    </div>
                </div>
            </div>
            <x-text-input 
                id="totp_code" 
                class="block mt-1 w-full font-mono text-2xl text-center tracking-widest" 
                type="text" 
                name="totp_code" 
                :value="old('totp_code')" 
                required 
                placeholder="000000"
                maxlength="6"
                pattern="[0-9]{6}"
                autocomplete="off" />
            <p class="mt-1 text-xs text-gray-500">Enter the 6-digit code from your authenticator app</p>
            <x-input-error :messages="$errors->get('totp_code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                Back to Login
            </a>
            <x-primary-button>
                {{ __('Verify & Continue') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Help Section -->
    <div class="mt-6 p-4 bg-gray-50 border border-gray-300 rounded text-sm">
        <h4 class="font-semibold text-gray-900 mb-2">Lost your authenticator app?</h4>
        <p class="text-gray-600 text-xs">
            If you no longer have access to your authenticator app, please contact support for account recovery assistance. 
            You'll need to verify your identity through alternative methods.
        </p>
    </div>

    <!-- Security Info -->
    <div class="mt-4 text-xs text-gray-500 text-center space-y-1">
        <p>🔒 Instant authentication - no waiting for emails</p>
        <p>🛡️ TOTP verification required for password reset</p>
        <p>📱 Your authenticator generates codes offline</p>
    </div>
</x-guest-layout>
