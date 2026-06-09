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

class TotpVerificationController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (!session()->has('totp_login_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.totp-verify');
    }

    public function store(Request $request): RedirectResponse
    {
        $userId = session('totp_login_user_id');
        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['totp_code' => 'Session expired. Please log in again.']);
        }

        $request->validate([
            'totp_code' => ['required', 'string', 'size:6'],
        ]);

        $user = User::find($userId);
        if (!$user || !$user->totp_secret) {
            session()->forget('totp_login_user_id');
            return redirect()->route('login')
                ->withErrors(['email' => 'Authentication failed. Please log in again.']);
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->totp_secret, $request->totp_code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'totp_code' => 'The verification code is invalid or has expired.',
            ]);
        }

        Auth::login($user, session()->get('totp_login_remember', false));
        session()->forget(['totp_login_user_id', 'totp_login_remember']);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
