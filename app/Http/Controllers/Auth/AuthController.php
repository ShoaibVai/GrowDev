<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use App\Models\User;

class AuthController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Show the login page
     */
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Show the register page
     */
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Authenticate with Supabase
            $response = $this->supabase->auth()->signInWithPassword(
                $request->email,
                $request->password
            );

            if (!$response || !isset($response['user'])) {
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ]);
            }

            // Get or create user in local database
            $user = User::firstOrCreate(
                ['email' => $response['user']['email']],
                [
                    'name' => $response['user']['user_metadata']['name'] ?? 'User',
                    'supabase_id' => $response['user']['id'],
                ]
            );

            // Log in the user
            Auth::login($user, $request->boolean('remember'));

            // Store Supabase session
            session([
                'supabase_access_token' => $response['access_token'],
                'supabase_refresh_token' => $response['refresh_token'],
            ]);

            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Authentication failed. Please try again.',
            ])->withInput($request->only('email'));
        }
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        try {
            // Register with Supabase
            $response = $this->supabase->auth()->signUp(
                $request->email,
                $request->password,
                [
                    'data' => [
                        'name' => $request->name,
                    ]
                ]
            );

            if (!$response || !isset($response['user'])) {
                return back()->withErrors([
                    'email' => 'Registration failed. Please try again.',
                ]);
            }

            // Create user in local database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'supabase_id' => $response['user']['id'],
                'password' => Hash::make($request->password),
            ]);

            // Log in the user
            Auth::login($user);

            // Store Supabase session if access token is available
            if (isset($response['access_token'])) {
                session([
                    'supabase_access_token' => $response['access_token'],
                    'supabase_refresh_token' => $response['refresh_token'] ?? null,
                ]);
            }

            $request->session()->regenerate();

            return redirect('/dashboard')->with('success', 'Registration successful! Welcome to GrowDev.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Registration failed: ' . $e->getMessage(),
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        try {
            // Sign out from Supabase if we have an access token
            $accessToken = session('supabase_access_token');
            if ($accessToken) {
                $this->supabase->auth()->signOut($accessToken);
            }
        } catch (\Exception $e) {
            // Continue with logout even if Supabase signout fails
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}