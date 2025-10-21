<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset form (after TOTP authentication).
     */
    public function create(): View|RedirectResponse
    {
        // Check if user has been authenticated via TOTP
        if (!session()->has('password_reset_verified_user')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please authenticate with your email and authenticator code first.']);
        }

        // Check if session hasn't expired (5 minutes timeout)
        $verifiedAt = session('password_reset_verified_at');
        if ($verifiedAt && now()->diffInMinutes($verifiedAt) > 5) {
            session()->forget(['password_reset_verified_user', 'password_reset_verified_at']);
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Your session has expired. Please authenticate again.']);
        }

        $userId = session('password_reset_verified_user');
        $user = User::find($userId);

        if (!$user) {
            session()->forget(['password_reset_verified_user', 'password_reset_verified_at']);
            return redirect()->route('password.request')
                ->withErrors(['email' => 'User not found. Please try again.']);
        }

        return view('auth.reset-password', [
            'user' => $user,
        ]);
    }

    /**
     * Handle the password reset (after TOTP authentication).
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Verify session
        if (!session()->has('password_reset_verified_user')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please authenticate with your email and authenticator code first.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $userId = session('password_reset_verified_user');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'User not found. Please try again.']);
        }

        // Update the user's password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Clear the session
        session()->forget(['password_reset_verified_user', 'password_reset_verified_at']);

        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', 'Your password has been reset successfully! Please log in with your new password.');
    }
}
