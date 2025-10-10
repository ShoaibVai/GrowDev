<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SupabaseServiceEnhanced;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class EmailConfirmationController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Show email confirmation notice
     */
    public function show()
    {
        return Inertia::render('Auth/ConfirmEmail');
    }

    /**
     * Handle email confirmation callback from Supabase
     */
    public function confirm(Request $request)
    {
        $token = $request->get('token');
        $type = $request->get('type');
        
        \Log::info('Email confirmation attempt', [
            'token' => substr($token ?? '', 0, 20) . '...',
            'type' => $type,
            'all_params' => $request->all()
        ]);
        
        if (!$token) {
            return redirect('/login')->withErrors(['email' => 'Invalid confirmation link.']);
        }

        try {
            // Verify the token with Supabase
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => config('services.supabase.anon_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.supabase.url') . '/auth/v1/verify', [
                'token' => $token,
                'type' => $type ?? 'signup'
            ]);

            \Log::info('Supabase verification response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['access_token'])) {
                    return redirect('/login')->with('success', 'Email confirmed! You can now sign in.');
                } else {
                    return redirect('/login')->with('success', 'Email confirmed! Please sign in with your credentials.');
                }
            } else {
                \Log::error('Email confirmation failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return redirect('/login')->withErrors(['email' => 'Email confirmation failed. Please try again or contact support.']);
            }
        } catch (\Exception $e) {
            \Log::error('Email confirmation error', ['error' => $e->getMessage()]);
            return redirect('/login')->withErrors(['email' => 'Email confirmation failed. Please try again.']);
        }
    }

    /**
     * Handle auth callback (alternative confirmation endpoint)
     */
    public function callback(Request $request)
    {
        // Alternative endpoint for Supabase auth callbacks
        return $this->confirm($request);
    }

    /**
     * Resend confirmation email
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            // Supabase doesn't have a direct resend confirmation API
            // So we'll show a helpful message
            return back()->with('success', 'If an account with this email exists and needs confirmation, please check your email again or contact support.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to resend confirmation email.']);
        }
    }
}