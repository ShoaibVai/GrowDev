-- GrowDev Database Schema for Supabase
-- Run these SQL commands in your Supabase SQL editor

-- Enable RLS
ALTER DATABASE postgres SET "app.jwt_secret" TO 'your-jwt-secret';

-- Create profiles table (extends auth.users)
CREATE TABLE profiles (
    id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
    email TEXT UNIQUE NOT NULL,
    full_name TEXT,
    avatar_url TEXT,
    role TEXT DEFAULT 'developer' CHECK (role IN ('developer', 'ui_ux', 'tester', 'project_manager')),
    bio TEXT,
    skills TEXT[],
    github_username TEXT,
    linkedin_url TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    PRIMARY KEY (id)
);

-- Create projects table
CREATE TABLE projects (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    stage TEXT DEFAULT 'idea' CHECK (stage IN ('idea', 'planning', 'design', 'development', 'testing', 'deployment')),
    type TEXT DEFAULT 'team' CHECK (type IN ('solo', 'team')),
    owner_id UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
    tech_stack TEXT[] DEFAULT '{}',
    repository_url TEXT,
    demo_url TEXT,
    deadline TIMESTAMP WITH TIME ZONE,
    status TEXT DEFAULT 'active' CHECK (status IN ('active', 'paused', 'completed', 'cancelled')),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create project_members table
CREATE TABLE project_members (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    project_id UUID REFERENCES projects(id) ON DELETE CASCADE NOT NULL,
    user_id UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
    role TEXT NOT NULL CHECK (role IN ('Developer', 'UI/UX', 'Tester', 'Project Manager')),
    permissions TEXT[] DEFAULT '{"read"}',
    joined_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    UNIQUE(project_id, user_id)
);

-- Create messages table for project chat
CREATE TABLE messages (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    project_id UUID REFERENCES projects(id) ON DELETE CASCADE NOT NULL,
    user_id UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
    content TEXT NOT NULL,
    message_type TEXT DEFAULT 'text' CHECK (message_type IN ('text', 'file', 'system')),
    file_url TEXT,
    file_name TEXT,
    reply_to UUID REFERENCES messages(id) ON DELETE SET NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create tasks table
CREATE TABLE tasks (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    project_id UUID REFERENCES projects(id) ON DELETE CASCADE NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    assigned_to UUID REFERENCES profiles(id) ON DELETE SET NULL,
    created_by UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
    status TEXT DEFAULT 'todo' CHECK (status IN ('todo', 'in_progress', 'review', 'done')),
    priority TEXT DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high', 'urgent')),
    due_date TIMESTAMP WITH TIME ZONE,
    tags TEXT[] DEFAULT '{}',
    estimated_hours INTEGER,
    actual_hours INTEGER,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create documents table for templates and project docs
CREATE TABLE documents (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    project_id UUID REFERENCES projects(id) ON DELETE CASCADE,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    document_type TEXT DEFAULT 'general' CHECK (document_type IN ('srs', 'readme', 'devlog', 'template', 'general')),
    created_by UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
    is_template BOOLEAN DEFAULT FALSE,
    template_category TEXT,
    version INTEGER DEFAULT 1,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create notifications table
CREATE TABLE notifications (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
    title TEXT NOT NULL,
    message TEXT NOT NULL,
    type TEXT DEFAULT 'info' CHECK (type IN ('info', 'success', 'warning', 'error')),
    data JSONB DEFAULT '{}',
    read_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Create function to handle updated_at timestamps
CREATE OR REPLACE FUNCTION handle_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create triggers for updated_at
CREATE TRIGGER handle_profiles_updated_at
    BEFORE UPDATE ON profiles
    FOR EACH ROW
    EXECUTE FUNCTION handle_updated_at();

CREATE TRIGGER handle_projects_updated_at
    BEFORE UPDATE ON projects
    FOR EACH ROW
    EXECUTE FUNCTION handle_updated_at();

CREATE TRIGGER handle_messages_updated_at
    BEFORE UPDATE ON messages
    FOR EACH ROW
    EXECUTE FUNCTION handle_updated_at();

CREATE TRIGGER handle_tasks_updated_at
    BEFORE UPDATE ON tasks
    FOR EACH ROW
    EXECUTE FUNCTION handle_updated_at();

CREATE TRIGGER handle_documents_updated_at
    BEFORE UPDATE ON documents
    FOR EACH ROW
    EXECUTE FUNCTION handle_updated_at();

-- Enable Row Level Security
ALTER TABLE profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE projects ENABLE ROW LEVEL SECURITY;
ALTER TABLE project_members ENABLE ROW LEVEL SECURITY;
ALTER TABLE messages ENABLE ROW LEVEL SECURITY;
ALTER TABLE tasks ENABLE ROW LEVEL SECURITY;
ALTER TABLE documents ENABLE ROW LEVEL SECURITY;
ALTER TABLE notifications ENABLE ROW LEVEL SECURITY;

-- RLS Policies

-- Profiles policies
CREATE POLICY "Public profiles are viewable by everyone" ON profiles
    FOR SELECT USING (true);

CREATE POLICY "Users can insert their own profile" ON profiles
    FOR INSERT WITH CHECK (auth.uid() = id);

CREATE POLICY "Users can update their own profile" ON profiles
    FOR UPDATE USING (auth.uid() = id);

-- Projects policies
CREATE POLICY "Users can view projects they own or are members of" ON projects
    FOR SELECT USING (
        auth.uid() = owner_id OR
        EXISTS (
            SELECT 1 FROM project_members
            WHERE project_members.project_id = projects.id
            AND project_members.user_id = auth.uid()
        )
    );

CREATE POLICY "Users can insert their own projects" ON projects
    FOR INSERT WITH CHECK (auth.uid() = owner_id);

CREATE POLICY "Project owners can update their projects" ON projects
    FOR UPDATE USING (auth.uid() = owner_id);

CREATE POLICY "Project owners can delete their projects" ON projects
    FOR DELETE USING (auth.uid() = owner_id);

-- Project members policies
CREATE POLICY "Users can view project members of projects they have access to" ON project_members
    FOR SELECT USING (
        EXISTS (
            SELECT 1 FROM projects
            WHERE projects.id = project_members.project_id
            AND (
                projects.owner_id = auth.uid() OR
                EXISTS (
                    SELECT 1 FROM project_members pm
                    WHERE pm.project_id = projects.id
                    AND pm.user_id = auth.uid()
                )
            )
        )
    );

CREATE POLICY "Project owners can manage project members" ON project_members
    FOR ALL USING (
        EXISTS (
            SELECT 1 FROM projects
            WHERE projects.id = project_members.project_id
            AND projects.owner_id = auth.uid()
        )
    );

-- Messages policies
CREATE POLICY "Users can view messages in projects they have access to" ON messages
    FOR SELECT USING (
        EXISTS (
            SELECT 1 FROM projects
            WHERE projects.id = messages.project_id
            AND (
                projects.owner_id = auth.uid() OR
                EXISTS (
                    SELECT 1 FROM project_members
                    WHERE project_members.project_id = projects.id
                    AND project_members.user_id = auth.uid()
                )
            )
        )
    );

CREATE POLICY "Users can insert messages in projects they have access to" ON messages
    FOR INSERT WITH CHECK (
        auth.uid() = user_id AND
        EXISTS (
            SELECT 1 FROM projects
            WHERE projects.id = messages.project_id
            AND (
                projects.owner_id = auth.uid() OR
                EXISTS (
                    SELECT 1 FROM project_members
                    WHERE project_members.project_id = projects.id
                    AND project_members.user_id = auth.uid()
                )
            )
        )
    );

-- Tasks policies
CREATE POLICY "Users can view tasks in projects they have access to" ON tasks
    FOR SELECT USING (
        EXISTS (
            SELECT 1 FROM projects
            WHERE projects.id = tasks.project_id
            AND (
                projects.owner_id = auth.uid() OR
                EXISTS (
                    SELECT 1 FROM project_members
                    WHERE project_members.project_id = projects.id
                    AND project_members.user_id = auth.uid()
                )
            )
        )
    );

CREATE POLICY "Users can manage tasks in projects they have access to" ON tasks
    FOR ALL USING (
        EXISTS (
            SELECT 1 FROM projects
            WHERE projects.id = tasks.project_id
            AND (
                projects.owner_id = auth.uid() OR
                EXISTS (
                    SELECT 1 FROM project_members
                    WHERE project_members.project_id = projects.id
                    AND project_members.user_id = auth.uid()
                )
            )
        )
    );

-- Documents policies
CREATE POLICY "Users can view documents in projects they have access to or public templates" ON documents
    FOR SELECT USING (
        is_template = true OR
        project_id IS NULL OR
        EXISTS (
            SELECT 1 FROM projects
            WHERE projects.id = documents.project_id
            AND (
                projects.owner_id = auth.uid() OR
                EXISTS (
                    SELECT 1 FROM project_members
                    WHERE project_members.project_id = projects.id
                    AND project_members.user_id = auth.uid()
                )
            )
        )
    );

CREATE POLICY "Users can manage documents in projects they have access to" ON documents
    FOR ALL USING (
        auth.uid() = created_by OR
        (
            project_id IS NOT NULL AND
            EXISTS (
                SELECT 1 FROM projects
                WHERE projects.id = documents.project_id
                AND projects.owner_id = auth.uid()
            )
        )
    );

-- Notifications policies
CREATE POLICY "Users can view their own notifications" ON notifications
    FOR SELECT USING (auth.uid() = user_id);

CREATE POLICY "Users can update their own notifications" ON notifications
    FOR UPDATE USING (auth.uid() = user_id);

-- Create function to handle new user signup
CREATE OR REPLACE FUNCTION handle_new_user()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO profiles (id, email, full_name)
    VALUES (
        NEW.id,
        NEW.email,
        COALESCE(NEW.raw_user_meta_data->>'full_name', NEW.email)
    );
    RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Create trigger for new user signup
CREATE TRIGGER on_auth_user_created
    AFTER INSERT ON auth.users
    FOR EACH ROW EXECUTE FUNCTION handle_new_user();

-- Insert sample data (optional)
INSERT INTO documents (title, content, document_type, is_template, template_category, created_by) VALUES
    ('Software Requirements Specification Template', 
     '# Software Requirements Specification\n\n## 1. Introduction\n### 1.1 Purpose\n### 1.2 Scope\n### 1.3 Definitions\n\n## 2. Overall Description\n### 2.1 Product Perspective\n### 2.2 Product Functions\n### 2.3 User Classes\n\n## 3. System Features\n\n## 4. External Interface Requirements\n\n## 5. Non-functional Requirements\n\n## 6. Other Requirements',
     'srs', 
     true, 
     'documentation',
     '00000000-0000-0000-0000-000000000000'),
    
    ('README Template',
     '# Project Name\n\n## Description\nA brief description of what this project does and who it''s for\n\n## Installation\n```bash\n# Installation steps\n```\n\n## Usage\n```bash\n# Usage examples\n```\n\n## Contributing\nContribution guidelines\n\n## License\nThis project is licensed under the MIT License',
     'readme',
     true,
     'documentation',
     '00000000-0000-0000-0000-000000000000'),
     
    ('Development Log Template',
     '# Development Log\n\n## Week 1\n### Completed\n- [ ] Task 1\n- [ ] Task 2\n\n### In Progress\n- [ ] Task 3\n\n### Blockers\n- Issue with X\n\n### Next Week\n- Plan for next week\n\n---\n\n## Week 2\n(Copy the structure above)',
     'devlog',
     true,
     'documentation',
     '00000000-0000-0000-0000-000000000000');