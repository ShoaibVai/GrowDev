-- =============================================================================
-- SUPABASE DATABASE SCHEMA SETUP
-- =============================================================================
-- Run this script in the Supabase SQL Editor
-- Dashboard > Project > SQL Editor > New Query
-- =============================================================================

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =============================================================================
-- PROFILES TABLE
-- =============================================================================

-- Create profiles table
CREATE TABLE IF NOT EXISTS public.profiles (
    id UUID REFERENCES auth.users(id) ON DELETE CASCADE PRIMARY KEY,
    name TEXT,
    email TEXT UNIQUE NOT NULL,
    avatar_url TEXT,
    role TEXT DEFAULT 'user' CHECK (role IN ('admin', 'manager', 'user')),
    department TEXT,
    bio TEXT,
    phone TEXT,
    location TEXT,
    timezone TEXT DEFAULT 'UTC',
    preferences JSONB DEFAULT '{}'::jsonb,
    is_active BOOLEAN DEFAULT true,
    last_seen_at TIMESTAMPTZ,
    created_at TIMESTAMPTZ DEFAULT now(),
    updated_at TIMESTAMPTZ DEFAULT now()
);

-- Enable RLS on profiles
ALTER TABLE public.profiles ENABLE ROW LEVEL SECURITY;

-- Create RLS policies for profiles
DROP POLICY IF EXISTS "Users can view own profile" ON public.profiles;
CREATE POLICY "Users can view own profile" ON public.profiles FOR SELECT USING (auth.uid() = id);

DROP POLICY IF EXISTS "Users can update own profile" ON public.profiles;
CREATE POLICY "Users can update own profile" ON public.profiles FOR UPDATE USING (auth.uid() = id);

DROP POLICY IF EXISTS "Admins can view all profiles" ON public.profiles;
CREATE POLICY "Admins can view all profiles" ON public.profiles FOR SELECT USING (
    EXISTS (
        SELECT 1 FROM public.profiles 
        WHERE id = auth.uid() AND role = 'admin'
    )
);

-- =============================================================================
-- PROJECTS TABLE
-- =============================================================================

-- Create projects table
CREATE TABLE IF NOT EXISTS public.projects (
    id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT,
    owner_id UUID REFERENCES public.profiles(id) ON DELETE CASCADE NOT NULL,
    status TEXT DEFAULT 'active' CHECK (status IN ('active', 'completed', 'archived', 'cancelled')),
    priority TEXT DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high', 'critical')),
    start_date DATE,
    end_date DATE,
    budget DECIMAL(12,2),
    progress INTEGER DEFAULT 0 CHECK (progress >= 0 AND progress <= 100),
    tags TEXT[],
    metadata JSONB DEFAULT '{}'::jsonb,
    created_at TIMESTAMPTZ DEFAULT now(),
    updated_at TIMESTAMPTZ DEFAULT now()
);

-- Enable RLS on projects
ALTER TABLE public.projects ENABLE ROW LEVEL SECURITY;

-- Create project RLS policies
DROP POLICY IF EXISTS "Users can view projects they own or are members of" ON public.projects;
CREATE POLICY "Users can view projects they own or are members of" ON public.projects FOR SELECT USING (
    owner_id = auth.uid() OR
    EXISTS (
        SELECT 1 FROM public.project_members 
        WHERE project_id = projects.id AND user_id = auth.uid()
    )
);

DROP POLICY IF EXISTS "Users can create projects" ON public.projects;
CREATE POLICY "Users can create projects" ON public.projects FOR INSERT WITH CHECK (owner_id = auth.uid());

DROP POLICY IF EXISTS "Project owners can update their projects" ON public.projects;
CREATE POLICY "Project owners can update their projects" ON public.projects FOR UPDATE USING (owner_id = auth.uid());

DROP POLICY IF EXISTS "Project owners can delete their projects" ON public.projects;
CREATE POLICY "Project owners can delete their projects" ON public.projects FOR DELETE USING (owner_id = auth.uid());

-- =============================================================================
-- PROJECT MEMBERS TABLE
-- =============================================================================

-- Create project_members table
CREATE TABLE IF NOT EXISTS public.project_members (
    id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
    project_id UUID REFERENCES public.projects(id) ON DELETE CASCADE NOT NULL,
    user_id UUID REFERENCES public.profiles(id) ON DELETE CASCADE NOT NULL,
    role TEXT DEFAULT 'member' CHECK (role IN ('owner', 'admin', 'member', 'viewer')),
    permissions JSONB DEFAULT '{}'::jsonb,
    joined_at TIMESTAMPTZ DEFAULT now(),
    UNIQUE(project_id, user_id)
);

-- Enable RLS on project_members
ALTER TABLE public.project_members ENABLE ROW LEVEL SECURITY;

-- Create project_members RLS policies
DROP POLICY IF EXISTS "Users can view project memberships" ON public.project_members;
CREATE POLICY "Users can view project memberships" ON public.project_members FOR SELECT USING (
    user_id = auth.uid() OR
    EXISTS (
        SELECT 1 FROM public.projects 
        WHERE id = project_id AND owner_id = auth.uid()
    )
);

-- =============================================================================
-- TASKS TABLE
-- =============================================================================

-- Create tasks table
CREATE TABLE IF NOT EXISTS public.tasks (
    id UUID DEFAULT uuid_generate_v4() PRIMARY KEY,
    project_id UUID REFERENCES public.projects(id) ON DELETE CASCADE NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    status TEXT DEFAULT 'todo' CHECK (status IN ('todo', 'in_progress', 'review', 'done', 'cancelled')),
    priority TEXT DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high', 'critical')),
    assigned_to UUID REFERENCES public.profiles(id) ON DELETE SET NULL,
    created_by UUID REFERENCES public.profiles(id) ON DELETE CASCADE NOT NULL,
    due_date TIMESTAMPTZ,
    estimated_hours DECIMAL(5,2),
    actual_hours DECIMAL(5,2),
    tags TEXT[],
    metadata JSONB DEFAULT '{}'::jsonb,
    completed_at TIMESTAMPTZ,
    created_at TIMESTAMPTZ DEFAULT now(),
    updated_at TIMESTAMPTZ DEFAULT now()
);

-- Enable RLS on tasks
ALTER TABLE public.tasks ENABLE ROW LEVEL SECURITY;

-- Create tasks RLS policies
DROP POLICY IF EXISTS "Users can view tasks in their projects" ON public.tasks;
CREATE POLICY "Users can view tasks in their projects" ON public.tasks FOR SELECT USING (
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
);

-- =============================================================================
-- FUNCTIONS AND TRIGGERS
-- =============================================================================

-- Create trigger function for profile creation
CREATE OR REPLACE FUNCTION public.handle_new_user()
RETURNS trigger AS $$
BEGIN
    INSERT INTO public.profiles (id, email, name)
    VALUES (NEW.id, NEW.email, COALESCE(NEW.raw_user_meta_data->>'name', split_part(NEW.email, '@', 1)));
    RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Create trigger for automatic profile creation
DROP TRIGGER IF EXISTS on_auth_user_created ON auth.users;
CREATE TRIGGER on_auth_user_created
    AFTER INSERT ON auth.users
    FOR EACH ROW EXECUTE FUNCTION public.handle_new_user();

-- Create updated_at trigger function
CREATE OR REPLACE FUNCTION public.handle_updated_at()
RETURNS trigger AS $$
BEGIN
    NEW.updated_at = now();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create updated_at triggers
DROP TRIGGER IF EXISTS set_updated_at ON public.profiles;
CREATE TRIGGER set_updated_at
    BEFORE UPDATE ON public.profiles
    FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();
    
DROP TRIGGER IF EXISTS set_updated_at ON public.projects;
CREATE TRIGGER set_updated_at
    BEFORE UPDATE ON public.projects
    FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();
    
DROP TRIGGER IF EXISTS set_updated_at ON public.tasks;
CREATE TRIGGER set_updated_at
    BEFORE UPDATE ON public.tasks
    FOR EACH ROW EXECUTE FUNCTION public.handle_updated_at();

-- =============================================================================
-- INDEXES FOR PERFORMANCE
-- =============================================================================

-- Create indexes for performance
CREATE INDEX IF NOT EXISTS idx_profiles_email ON public.profiles(email);
CREATE INDEX IF NOT EXISTS idx_profiles_role ON public.profiles(role);
CREATE INDEX IF NOT EXISTS idx_projects_owner ON public.projects(owner_id);
CREATE INDEX IF NOT EXISTS idx_projects_status ON public.projects(status);
CREATE INDEX IF NOT EXISTS idx_project_members_project ON public.project_members(project_id);
CREATE INDEX IF NOT EXISTS idx_project_members_user ON public.project_members(user_id);
CREATE INDEX IF NOT EXISTS idx_tasks_project ON public.tasks(project_id);
CREATE INDEX IF NOT EXISTS idx_tasks_assigned ON public.tasks(assigned_to);
CREATE INDEX IF NOT EXISTS idx_tasks_status ON public.tasks(status);

-- =============================================================================
-- ENABLE REAL-TIME (OPTIONAL)
-- =============================================================================

-- Enable real-time subscriptions (uncomment if needed)
-- ALTER PUBLICATION supabase_realtime ADD TABLE public.profiles;
-- ALTER PUBLICATION supabase_realtime ADD TABLE public.projects;
-- ALTER PUBLICATION supabase_realtime ADD TABLE public.project_members;
-- ALTER PUBLICATION supabase_realtime ADD TABLE public.tasks;

-- =============================================================================
-- COMPLETED
-- =============================================================================
-- Schema setup complete!
-- Your database now has:
-- ✅ Profiles table with user management
-- ✅ Projects table with CRUD operations
-- ✅ Project members table for collaboration
-- ✅ Tasks table for project management
-- ✅ Row Level Security (RLS) policies
-- ✅ Automatic profile creation on user signup
-- ✅ Timestamp triggers for updated_at
-- ✅ Performance indexes
-- =============================================================================