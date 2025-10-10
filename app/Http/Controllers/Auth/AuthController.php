<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SupabaseServiceEnhanced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use App\Models\User;

class AuthController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
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
     * Handle user login - Supabase only
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            \Log::info('Attempting Supabase login', ['email' => $request->email]);
            
            // Authenticate with Supabase
            $supabaseResponse = $this->supabase->signIn($request->email, $request->password);

            if ($supabaseResponse['success'] && isset($supabaseResponse['data']['access_token'])) {
                $userData = $supabaseResponse['data'];
                \Log::info('Supabase login successful', ['user_id' => $userData['user']['id']]);
                
                // Store Supabase session
                session([
                    'supabase_token' => $userData['access_token'],
                    'supabase_user' => $userData['user']
                ]);
                
                // Create a Laravel user instance for session compatibility
                $user = new User([
                    'id' => $userData['user']['id'],
                    'email' => $userData['user']['email'],
                    'name' => $userData['user']['user_metadata']['name'] ?? $userData['user']['email'],
                    'supabase_id' => $userData['user']['id'],
                    'email_verified_at' => $userData['user']['email_confirmed_at'] ?? null,
                ]);

                // Set the user for Laravel's auth system
                Auth::setUser($user);
                $request->session()->regenerate();
                
                return redirect()->intended('/dashboard');
            } else {
                \Log::warning('Supabase login failed', $supabaseResponse);
                
                // Handle specific error cases
                if (isset($supabaseResponse['data']['error'])) {
                    $error = $supabaseResponse['data']['error'];
                    $errorDescription = $supabaseResponse['data']['error_description'] ?? '';
                    
                    if ($error === 'email_not_confirmed') {
                        return back()->withErrors([
                            'email' => 'Please check your email and click the confirmation link before signing in.',
                        ])->withInput($request->only('email'));
                    } elseif ($error === 'invalid_credentials') {
                        return back()->withErrors([
                            'email' => 'The provided credentials do not match our records.',
                        ])->withInput($request->only('email'));
                    } else {
                        return back()->withErrors([
                            'email' => $errorDescription ?: 'Login failed. Please try again.',
                        ])->withInput($request->only('email'));
                    }
                } else {
                    return back()->withErrors([
                        'email' => 'Login failed. Please check your credentials.',
                    ])->withInput($request->only('email'));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Login failed', ['error' => $e->getMessage(), 'email' => $request->email]);
            
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('email'));
        }
    }

    /**
     * Handle user registration - Supabase only, redirect to login
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        try {
            \Log::info('Starting Supabase registration', ['email' => $request->email]);

            // Register with Supabase
            $supabaseResponse = $this->supabase->signUp(
                $request->email,
                $request->password,
                ['name' => $request->name, 'full_name' => $request->name]
            );

            \Log::info('Supabase registration response', $supabaseResponse);

            if ($supabaseResponse['success'] && isset($supabaseResponse['data']['id'])) {
                \Log::info('Registration successful, redirecting to login', ['user_id' => $supabaseResponse['data']['id']]);
                
                // Check if email confirmation is required
                if (isset($supabaseResponse['data']['email_confirmed_at']) && $supabaseResponse['data']['email_confirmed_at']) {
                    return redirect('/login')->with('success', 'Registration successful! Please sign in with your credentials.');
                } else {
                    return redirect('/login')->with('warning', 'Registration successful! Please check your email and click the confirmation link before signing in.');
                }
            } else {
                $errorMessage = $supabaseResponse['data']['error_description'] ?? $supabaseResponse['error'] ?? 'Registration failed';
                throw new \Exception($errorMessage);
            }

        } catch (\Exception $e) {
            \Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return back()->withErrors([
                'email' => 'Registration failed: ' . $e->getMessage(),
            ])->withInput($request->only('name', 'email'));
        }
    }

    /**
     * Handle user logout - Supabase only
     */
    public function logout(Request $request)
    {
        try {
            // Sign out from Supabase if we have a token
            if (session('supabase_token')) {
                $this->supabase->signOut(session('supabase_token'));
            }
        } catch (\Exception $e) {
            \Log::warning('Supabase logout failed', ['error' => $e->getMessage()]);
        }

        // Clear all session data
        Auth::logout();
        session()->forget(['supabase_token', 'supabase_user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}