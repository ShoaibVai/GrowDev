<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate TOTP secret
        $google2fa = new Google2FA();
        $totpSecret = $google2fa->generateSecretKey();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'totp_secret' => $totpSecret,
        ]);

        event(new Registered($user));

        // Generate QR code URL for authenticator apps
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $totpSecret
        );

        // Redirect to a page showing the TOTP secret and QR code
        return redirect()->route('register.totp-setup')
            ->with('totp_secret', $totpSecret)
            ->with('qr_code_url', $qrCodeUrl)
            ->with('user_email', $user->email);
    }

    /**
     * Display the TOTP setup page after registration.
     */
    public function totpSetup(): View|RedirectResponse
    {
        // Ensure the user came from registration
        if (!session()->has('totp_secret')) {
            return redirect()->route('login');
        }

        return view('auth.totp-setup');
    }
}
