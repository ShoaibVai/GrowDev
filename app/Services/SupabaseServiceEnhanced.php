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
     * List all users (admin function)
     */
    public function listUsers()
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->serviceRoleKey,
                'Authorization' => 'Bearer ' . $this->serviceRoleKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/auth/v1/admin/users');

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase list users error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete user (admin function)
     */
    public function deleteUser($userId)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->serviceRoleKey,
                'Authorization' => 'Bearer ' . $this->serviceRoleKey,
                'Content-Type' => 'application/json',
            ])->delete($this->url . '/auth/v1/admin/users/' . $userId);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase delete user error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create user profile in profiles table
     */
    public function createProfile($userId, $profileData)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->serviceRoleKey,
                'Authorization' => 'Bearer ' . $this->serviceRoleKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ])->post($this->url . '/rest/v1/profiles', array_merge([
                'id' => $userId,
            ], $profileData));

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase create profile error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get user profile
     */
    public function getProfile($userId)
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->anonKey,
                'Authorization' => 'Bearer ' . $this->anonKey,
                'Content-Type' => 'application/json',
            ])->get($this->url . '/rest/v1/profiles?id=eq.' . $userId);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase get profile error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get HTTP client with authentication headers
     */
    private function getClient($useServiceKey = false)
    {
        $key = $useServiceKey ? $this->serviceRoleKey : $this->anonKey;
        
        return Http::withoutVerifying()->withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation'
        ]);
    }

    /**
     * Execute raw SQL queries (admin function)
     */
    public function executeQuery($query, $params = [])
    {
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->serviceRoleKey,
                'Authorization' => 'Bearer ' . $this->serviceRoleKey,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/rest/v1/rpc/execute_sql', [
                'query' => $query,
                'params' => $params
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Supabase execute query error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create database schema
     */
    public function createSchema()
    {
        $schemas = [
            // Enable RLS
            'ALTER TABLE auth.users ENABLE ROW LEVEL SECURITY;',
            
            // Create profiles table
            'CREATE TABLE IF NOT EXISTS public.profiles (
                id UUID REFERENCES auth.users(id) ON DELETE CASCADE PRIMARY KEY,
                name TEXT,
                email TEXT UNIQUE NOT NULL,
                avatar_url TEXT,
                role TEXT DEFAULT \'user\' CHECK (role IN (\'admin\', \'manager\', \'user\')),
                department TEXT,
                bio TEXT,
                phone TEXT,
                location TEXT,
                timezone TEXT DEFAULT \'UTC\',
                preferences JSONB DEFAULT \'{}\'::jsonb,
                is_active BOOLEAN DEFAULT true,
                last_seen_at TIMESTAMPTZ,
                created_at TIMESTAMPTZ DEFAULT now(),
                updated_at TIMESTAMPTZ DEFAULT now()
            );',
            
            // Enable RLS on profiles
            'ALTER TABLE public.profiles ENABLE ROW LEVEL SECURITY;',
            
            // Create RLS policies for profiles
            'DROP POLICY IF EXISTS "Users can view own profile" ON public.profiles;',
            'CREATE POLICY "Users can view own profile" ON public.profiles FOR SELECT USING (auth.uid() = id);',
            
            'DROP POLICY IF EXISTS "Users can update own profile" ON public.profiles;',
            'CREATE POLICY "Users can update own profile" ON public.profiles FOR UPDATE USING (auth.uid() = id);',
            
            'DROP POLICY IF EXISTS "Admins can view all profiles" ON public.profiles;',
            'CREATE POLICY "Admins can view all profiles" ON public.profiles FOR SELECT USING (
                EXISTS (
                    SELECT 1 FROM public.profiles 
                    WHERE id = auth.uid() AND role = \'admin\'
                )
            );',
            
            // Create trigger function for profile creation
            'CREATE OR REPLACE FUNCTION public.handle_new_user()
            RETURNS trigger AS $$
            BEGIN
                INSERT INTO public.profiles (id, email, name)
                VALUES (NEW.id, NEW.email, COALESCE(NEW.raw_user_meta_data->>\'name\', split_part(NEW.email, \'@\', 1)));
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql SECURITY DEFINER;',
            
            // Create trigger for automatic profile creation
            'DROP TRIGGER IF EXISTS on_auth_user_created ON auth.users;',
            'CREATE TRIGGER on_auth_user_created
                AFTER INSERT ON auth.users
                FOR EACH ROW EXECUTE FUNCTION public.handle_new_user();',
            
            // Create projects table
            'CREATE TABLE IF NOT EXISTS public.projects (
                id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
                name TEXT NOT NULL,
                description TEXT,
                owner_id UUID REFERENCES public.profiles(id) ON DELETE CASCADE NOT NULL,
                status TEXT DEFAULT \'active\' CHECK (status IN (\'active\', \'completed\', \'archived\', \'cancelled\')),
                priority TEXT DEFAULT \'medium\' CHECK (priority IN (\'low\', \'medium\', \'high\', \'critical\')),
                start_date DATE,
                end_date DATE,
                budget DECIMAL(12,2),
                progress INTEGER DEFAULT 0 CHECK (progress >= 0 AND progress <= 100),
                tags TEXT[],
                metadata JSONB DEFAULT \'{}\'::jsonb,
                created_at TIMESTAMPTZ DEFAULT now(),
                updated_at TIMESTAMPTZ DEFAULT now()
            );',
            
            // Enable RLS on projects
            'ALTER TABLE public.projects ENABLE ROW LEVEL SECURITY;',
            
            // Create project RLS policies
            'DROP POLICY IF EXISTS "Users can view projects they own or are members of" ON public.projects;',
            'CREATE POLICY "Users can view projects they own or are members of" ON public.projects FOR SELECT USING (
                owner_id = auth.uid() OR
                EXISTS (
                    SELECT 1 FROM public.project_members 
                    WHERE project_id = projects.id AND user_id = auth.uid()
                )
            );',
            
            'DROP POLICY IF EXISTS "Users can create projects" ON public.projects;',
            'CREATE POLICY "Users can create projects" ON public.projects FOR INSERT WITH CHECK (owner_id = auth.uid());',
            
            'DROP POLICY IF EXISTS "Project owners can update their projects" ON public.projects;',
            'CREATE POLICY "Project owners can update their projects" ON public.projects FOR UPDATE USING (owner_id = auth.uid());',
            
            'DROP POLICY IF EXISTS "Project owners can delete their projects" ON public.projects;',
            'CREATE POLICY "Project owners can delete their projects" ON public.projects FOR DELETE USING (owner_id = auth.uid());',
            
            // Create project_members table
            'CREATE TABLE IF NOT EXISTS public.project_members (
                id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
                project_id UUID REFERENCES public.projects(id) ON DELETE CASCADE NOT NULL,
                user_id UUID REFERENCES public.profiles(id) ON DELETE CASCADE NOT NULL,
                role TEXT DEFAULT \'member\' CHECK (role IN (\'owner\', \'admin\', \'member\', \'viewer\')),
                permissions JSONB DEFAULT \'{}\'::jsonb,
                joined_at TIMESTAMPTZ DEFAULT now(),
                UNIQUE(project_id, user_id)
            );',
            
            // Enable RLS on project_members
            'ALTER TABLE public.project_members ENABLE ROW LEVEL SECURITY;',
            
            // Create project_members RLS policies
            'DROP POLICY IF EXISTS "Users can view project memberships" ON public.project_members;',
            'CREATE POLICY "Users can view project memberships" ON public.project_members FOR SELECT USING (
                user_id = auth.uid() OR
                EXISTS (
                    SELECT 1 FROM public.projects 
                    WHERE id = project_id AND owner_id = auth.uid()
                )
            );',
            
            // Create tasks table
            'CREATE TABLE IF NOT EXISTS public.tasks (
                id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
                project_id UUID REFERENCES public.projects(id) ON DELETE CASCADE NOT NULL,
                title TEXT NOT NULL,
                description TEXT,
                status TEXT DEFAULT \'todo\' CHECK (status IN (\'todo\', \'in_progress\', \'review\', \'done\', \'cancelled\')),
                priority TEXT DEFAULT \'medium\' CHECK (priority IN (\'low\', \'medium\', \'high\', \'critical\')),
                assigned_to UUID REFERENCES public.profiles(id) ON DELETE SET NULL,
                created_by UUID REFERENCES public.profiles(id) ON DELETE CASCADE NOT NULL,
                due_date TIMESTAMPTZ,
                estimated_hours DECIMAL(5,2),
                actual_hours DECIMAL(5,2),
                tags TEXT[],
                metadata JSONB DEFAULT \'{}\'::jsonb,
                completed_at TIMESTAMPTZ,
                created_at TIMESTAMPTZ DEFAULT now(),
                updated_at TIMESTAMPTZ DEFAULT now()
            );',
            
            // Enable RLS on tasks
            'ALTER TABLE public.tasks ENABLE ROW LEVEL SECURITY;',
            
            // Create tasks RLS policies
            'DROP POLICY IF EXISTS "Users can view tasks in their projects" ON public.tasks;',
            'CREATE POLICY "Users can view tasks in their projects" ON public.tasks FOR SELECT USING (
                EXISTS (
                    SELECT 1 FROM public.projects 
                    WHERE id = project_id AND (
                        owner_id = auth.uid() OR
                        EXISTS (
                            SELECT 1 FROM public.project_members 
                            WHERE project_id = projects.id AND user_id = auth.uid()
                        )
                    )
                )
            );',
            
            // Create indexes for performance
            'CREATE INDEX IF NOT EXISTS idx_profiles_email ON public.profiles(email);',
            'CREATE INDEX IF NOT EXISTS idx_profiles_role ON public.profiles(role);',
            'CREATE INDEX IF NOT EXISTS idx_projects_owner ON public.projects(owner_id);',
            'CREATE INDEX IF NOT EXISTS idx_projects_status ON public.projects(status);',
            'CREATE INDEX IF NOT EXISTS idx_project_members_project ON public.project_members(project_id);',
            'CREATE INDEX IF NOT EXISTS idx_project_members_user ON public.project_members(user_id);',
            'CREATE INDEX IF NOT EXISTS idx_tasks_project ON public.tasks(project_id);',
            'CREATE INDEX IF NOT EXISTS idx_tasks_assigned ON public.tasks(assigned_to);',
            'CREATE INDEX IF NOT EXISTS idx_tasks_status ON public.tasks(status);',
            
            // Create updated_at trigger function
            'CREATE OR REPLACE FUNCTION public.handle_updated_at()
            RETURNS trigger AS $$
            BEGIN
                NEW.updated_at = now();
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;',
            
            // Create updated_at triggers
            'DROP TRIGGER IF EXISTS set_updated_at ON public.profiles;',
            'CREATE TRIGGER set_updated_at
                BEFORE UPDATE ON public.profiles
                FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();',
                
            'DROP TRIGGER IF EXISTS set_updated_at ON public.projects;',
            'CREATE TRIGGER set_updated_at
                BEFORE UPDATE ON public.projects
                FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();',
                
            'DROP TRIGGER IF EXISTS set_updated_at ON public.tasks;',
            'CREATE TRIGGER set_updated_at
                BEFORE UPDATE ON public.tasks
                FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();'
        ];

        $results = [];
        foreach ($schemas as $sql) {
            $result = $this->executeRawSQL($sql);
            $results[] = [
                'sql' => substr($sql, 0, 100) . '...',
                'success' => $result['success'],
                'error' => $result['error'] ?? null
            ];
            
            if (!$result['success']) {
                Log::error('Schema creation failed', [
                    'sql' => $sql,
                    'error' => $result['error']
                ]);
            }
        }

        return $results;
    }

    /**
     * Execute raw SQL (admin function)
     */
    public function executeRawSQL($sql)
    {
        try {
            // Use PostgreSQL REST API
            $response = Http::withoutVerifying()->withHeaders([
                'apikey' => $this->serviceRoleKey,
                'Authorization' => 'Bearer ' . $this->serviceRoleKey,
                'Content-Type' => 'application/json',
            ])->post($this->url . '/rest/v1/rpc/exec', [
                'sql' => $sql
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status(),
                'error' => !$response->successful() ? $response->body() : null
            ];
        } catch (\Exception $e) {
            Log::error('Supabase execute raw SQL error', [
                'sql' => $sql,
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get database schema information
     */
    public function getSchemaInfo()
    {
        $tables = ['profiles', 'projects', 'project_members', 'tasks'];
        $info = [];

        foreach ($tables as $table) {
            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'apikey' => $this->anonKey,
                    'Content-Type' => 'application/json',
                ])->get($this->url . '/rest/v1/' . $table . '?limit=0');

                $info[$table] = [
                    'exists' => $response->successful(),
                    'status' => $response->status(),
                    'headers' => $response->headers()
                ];
            } catch (\Exception $e) {
                $info[$table] = [
                    'exists' => false,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $info;
    }

    /**
     * Seed initial data
     */
    public function seedData()
    {
        // This would typically insert default roles, sample data, etc.
        Log::info('Seeding initial data...');
        
        return [
            'success' => true,
            'message' => 'Data seeding completed'
        ];
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