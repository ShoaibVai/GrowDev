<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseServiceEnhanced
{
    protected $url;
    protected $anonKey;
    protected $serviceRoleKey;

    public function __construct()
    {
        $this->url = config('services.supabase.url');
        $this->anonKey = config('services.supabase.anon_key');
        $this->serviceRoleKey = config('services.supabase.service_role_key');
    }

    /**
     * Test connection to Supabase
     */
    public function testConnection()
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/');
            
            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'response' => $response->json()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Sign up with email and password
     */
    public function signUp($email, $password, $metadata = [])
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/auth/v1/signup', [
                'email' => $email,
                'password' => $password,
                'data' => $metadata
            ]);

            Log::info('Supabase signup response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase signup error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Sign in with email and password
     */
    public function signIn($email, $password)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/auth/v1/token?grant_type=password', [
                'email' => $email,
                'password' => $password,
            ]);

            Log::info('Supabase signin response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase signin error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Sign out user
     */
    public function signOut($accessToken)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/auth/v1/logout');

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase signout error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get current user
     */
    public function getUser($accessToken)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($this->url . '/auth/v1/user');

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase getUser error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Refresh token
     */
    public function refreshToken($refreshToken)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/auth/v1/token?grant_type=refresh_token', [
                'refresh_token' => $refreshToken,
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase refresh token error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get user projects
     */
    public function getUserProjects($userId)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/projects', [
                'or' => "(owner_id.eq.$userId,project_members.user_id.eq.$userId)",
                'select' => '*,project_members(*,profiles(*))'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Supabase getUserProjects error', ['response' => $response->body()]);
            return [];
        } catch (\Exception $e) {
            Log::error('Supabase getUserProjects exception', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Create a new project
     */
    public function createProject($projectData)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->post($this->url . '/rest/v1/projects', $projectData);

            if ($response->successful()) {
                return $response->json()[0] ?? null;
            }

            Log::error('Supabase createProject error', ['response' => $response->body()]);
            throw new \Exception('Failed to create project');
        } catch (\Exception $e) {
            Log::error('Supabase createProject exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get a single project
     */
    public function getProject($projectId)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/projects', [
                'id' => "eq.$projectId",
                'select' => '*,project_members(*,profiles(*))'
            ]);

            if ($response->successful()) {
                $projects = $response->json();
                return !empty($projects) ? $projects[0] : null;
            }

            Log::error('Supabase getProject error', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Supabase getProject exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Update a project
     */
    public function updateProject($projectId, $updateData)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->patch($this->url . '/rest/v1/projects?id=eq.' . $projectId, $updateData);

            if ($response->successful()) {
                return $response->json()[0] ?? null;
            }

            Log::error('Supabase updateProject error', ['response' => $response->body()]);
            throw new \Exception('Failed to update project');
        } catch (\Exception $e) {
            Log::error('Supabase updateProject exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete a project
     */
    public function deleteProject($projectId)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->delete($this->url . '/rest/v1/projects?id=eq.' . $projectId);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Supabase deleteProject exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get project messages
     */
    public function getProjectMessages($projectId)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/messages', [
                'project_id' => "eq.$projectId",
                'select' => '*,profiles(*)',
                'order' => 'created_at.asc'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Supabase getProjectMessages error', ['response' => $response->body()]);
            return [];
        } catch (\Exception $e) {
            Log::error('Supabase getProjectMessages exception', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Check if user is project member
     */
    public function isProjectMember($projectId, $userId)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/projects', [
                'id' => "eq.$projectId",
                'or' => "(owner_id.eq.$userId,project_members.user_id.eq.$userId)",
                'select' => 'id'
            ]);

            return $response->successful() && !empty($response->json());
        } catch (\Exception $e) {
            Log::error('Supabase isProjectMember exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->serviceRoleKey,
                'Authorization' => 'Bearer ' . $this->serviceRoleKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/profiles', [
                'email' => "eq.$email",
                'select' => '*'
            ]);

            if ($response->successful()) {
                $users = $response->json();
                return !empty($users) ? $users[0] : null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Supabase getUserByEmail exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Add project member
     */
    public function addProjectMember($projectId, $userId, $role)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->post($this->url . '/rest/v1/project_members', [
                'project_id' => $projectId,
                'user_id' => $userId,
                'role' => $role
            ]);

            if ($response->successful()) {
                return $response->json()[0] ?? null;
            }

            Log::error('Supabase addProjectMember error', ['response' => $response->body()]);
            throw new \Exception('Failed to add project member');
        } catch (\Exception $e) {
            Log::error('Supabase addProjectMember exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}