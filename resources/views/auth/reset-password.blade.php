<x-guest-layout>
    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Set New Password</h2>
        <p class="mt-2 text-sm text-gray-600">Enter your new password below</p>
    </div>

    <!-- Success Notice -->
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm" role="alert">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <strong class="font-bold">Authentication Successful!</strong>
                <span class="block mt-1">Your identity has been verified. You can now set a new password for your account.</span>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="mb-4 p-3 bg-gray-50 border border-gray-300 rounded">
        <p class="text-sm text-gray-700">
            <strong>Account:</strong> {{ $user->name }} ({{ $user->email }})
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Password Requirements -->
        <div class="mt-3 text-xs text-gray-600 bg-indigo-50 p-3 rounded border border-indigo-200">
            <strong class="block mb-1">Password Requirements:</strong>
            <ul class="list-disc list-inside space-y-1">
                <li>Minimum 8 characters</li>
                <li>At least one uppercase letter</li>
                <li>At least one lowercase letter</li>
                <li>At least one number</li>
                <li>At least one special character</li>
            </ul>
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Security Info -->
    <div class="mt-6 text-xs text-gray-500 text-center space-y-1">
        <p>🔒 Your session is secure and will expire in 5 minutes</p>
        <p>🛡️ You'll need to log in with your new password</p>
    </div>
</x-guest-layout>
