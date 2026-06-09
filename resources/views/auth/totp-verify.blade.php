<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This account has two-factor authentication enabled. Please enter the 6-digit code from your authenticator app.') }}
    </div>

    <form method="POST" action="{{ route('totp.verify') }}">
        @csrf

        <div>
            <x-input-label for="totp_code" :value="__('Authentication Code')" />
            <x-text-input id="totp_code" class="block mt-1 w-full text-center text-2xl tracking-widest"
                            type="text"
                            name="totp_code"
                            required
                            maxlength="6"
                            autocomplete="one-time-code"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            autofocus />
            <x-input-error :messages="$errors->get('totp_code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
