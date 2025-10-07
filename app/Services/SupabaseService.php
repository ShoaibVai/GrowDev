<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected $baseUrl;
    protected $apiKey;
    protected $serviceKey;

    public function __construct()
    {
        $this->baseUrl = config('services.supabase.url');
        $this->apiKey = config('services.supabase.anon_key');
        $this->serviceKey = config('services.supabase.service_role_key');
    }

    /**
     * Get HTTP client with authentication headers
     */
    private function getClient($useServiceKey = false)
    {
        $key = $useServiceKey ? $this->serviceKey : $this->apiKey;
        
        return Http::withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ]);
    }

    /**
     * Get user's projects
     */
    public function getUserProjects($userId)
    {
        try {
            $response = $this->getClient()->get($this->baseUrl . '/rest/v1/projects', [
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
            $response = $this->getClient()->post($this->baseUrl . '/rest/v1/projects', $projectData);

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
            $response = $this->getClient()->get($this->baseUrl . '/rest/v1/projects', [
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
    public function updateProject($projectId, $updates)
    {
        try {
            $response = $this->getClient()->patch(
                $this->baseUrl . '/rest/v1/projects?id=eq.' . $projectId,
                $updates
            );

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
            $response = $this->getClient(true)->delete(
                $this->baseUrl . '/rest/v1/projects?id=eq.' . $projectId
            );

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
            $response = $this->getClient()->get($this->baseUrl . '/rest/v1/messages', [
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
     * Send a message
     */
    public function sendMessage($messageData)
    {
        try {
            $response = $this->getClient()->post($this->baseUrl . '/rest/v1/messages', $messageData);

            if ($response->successful()) {
                return $response->json()[0] ?? null;
            }

            Log::error('Supabase sendMessage error', ['response' => $response->body()]);
            throw new \Exception('Failed to send message');
        } catch (\Exception $e) {
            Log::error('Supabase sendMessage exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Add project member
     */
    public function addProjectMember($projectId, $userId, $role)
    {
        try {
            $memberData = [
                'project_id' => $projectId,
                'user_id' => $userId,
                'role' => $role,
                'joined_at' => now()->toISOString()
            ];

            $response = $this->getClient()->post($this->baseUrl . '/rest/v1/project_members', $memberData);

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

    /**
     * Check if user is project member
     */
    public function isProjectMember($projectId, $userId)
    {
        try {
            $response = $this->getClient()->get($this->baseUrl . '/rest/v1/project_members', [
                'project_id' => "eq.$projectId",
                'user_id' => "eq.$userId"
            ]);

            if ($response->successful()) {
                return !empty($response->json());
            }

            return false;
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
            $response = $this->getClient(true)->get($this->baseUrl . '/rest/v1/profiles', [
                'email' => "eq.$email"
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
}