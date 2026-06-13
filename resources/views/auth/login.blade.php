<x-guest-layout>
    <!-- Log in Page Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold" style="font-family:var(--font-mono);color:var(--color-text);">Log in</h2>
        <p class="mt-2 text-sm" style="color:var(--color-text-muted);">Welcome back! Please log in to your account</p>
    </div>

    <!-- Success Message (from registration) -->
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded relative" style="background-color:color-mix(in srgb, var(--color-success) 15%, transparent);border:1px solid var(--color-success);color:var(--color-success);" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" style="border-color:var(--color-border);accent-color:var(--color-accent);" name="remember">
                <span class="ms-2 text-sm" style="color:var(--color-text-muted);">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm hover:underline focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" href="{{ route('password.request') }}" style="color:var(--color-accent);">
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm hover:underline focus-visible:ring-2 focus-visible:ring-[var(--color-accent)] focus-visible:outline-none" href="{{ route('register') }}" style="color:var(--color-text-muted);">
                {{ __("Don't have an account?") }}
            </a>

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
