<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset authentication view.
     * Users authenticate with email + TOTP code (no email sent).
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Authenticate user with email and TOTP code, then redirect to password reset form.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'totp_code' => ['required', 'string', 'size:6'],
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account found with this email address.',
            ]);
        }

        // Check if user has TOTP secret
        if (!$user->totp_secret) {
            throw ValidationException::withMessages([
                'email' => 'This account does not have two-factor authentication set up. Please contact support.',
            ]);
        }

        // Verify TOTP code
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->totp_secret, $request->totp_code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'totp_code' => 'The verification code is invalid or has expired. Please try again.',
            ]);
        }

        // TOTP verified successfully - redirect to password reset form
        // Store user ID in session to allow password change
        session([
            'password_reset_verified_user' => $user->id,
            'password_reset_verified_at' => now(),
        ]);

        return redirect()->route('password.reset.form')->with('status', 'Authentication successful! You can now reset your password.');
    }
}
