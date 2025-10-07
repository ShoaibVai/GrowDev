import { createClient } from '@supabase/supabase-js'

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY

export const supabase = createClient(supabaseUrl, supabaseAnonKey, {
  auth: {
    autoRefreshToken: true,
    persistSession: true,
    detectSessionInUrl: true
  },
  realtime: {
    params: {
      eventsPerSecond: 10
    }
  }
})

// Auth helpers
export const auth = {
  signUp: async (email, password, metadata = {}) => {
    const { data, error } = await supabase.auth.signUp({
      email,
      password,
      options: {
        data: metadata
      }
    })
    return { data, error }
  },

  signIn: async (email, password) => {
    const { data, error } = await supabase.auth.signInWithPassword({
      email,
      password
    })
    return { data, error }
  },

  signOut: async () => {
    const { error } = await supabase.auth.signOut()
    return { error }
  },

  getCurrentUser: () => {
    return supabase.auth.getUser()
  },

  onAuthStateChange: (callback) => {
    return supabase.auth.onAuthStateChange(callback)
  }
}

// Database helpers
export const db = {
  // Projects
  getProjects: async (userId) => {
    const { data, error } = await supabase
      .from('projects')
      .select(`
        *,
        project_members (
          id,
          role,
          user_id,
          profiles (
            id,
            full_name,
            avatar_url
          )
        )
      `)
      .or(`owner_id.eq.${userId},project_members.user_id.eq.${userId}`)
    return { data, error }
  },

  createProject: async (projectData) => {
    const { data, error } = await supabase
      .from('projects')
      .insert([projectData])
      .select()
    return { data, error }
  },

  updateProject: async (id, updates) => {
    const { data, error } = await supabase
      .from('projects')
      .update(updates)
      .eq('id', id)
      .select()
    return { data, error }
  },

  // Project Members
  addProjectMember: async (projectId, userId, role) => {
    const { data, error } = await supabase
      .from('project_members')
      .insert([{
        project_id: projectId,
        user_id: userId,
        role: role
      }])
      .select()
    return { data, error }
  },

  // Messages
  getProjectMessages: async (projectId) => {
    const { data, error } = await supabase
      .from('messages')
      .select(`
        *,
        profiles (
          id,
          full_name,
          avatar_url
        )
      `)
      .eq('project_id', projectId)
      .order('created_at', { ascending: true })
    return { data, error }
  },

  sendMessage: async (messageData) => {
    const { data, error } = await supabase
      .from('messages')
      .insert([messageData])
      .select(`
        *,
        profiles (
          id,
          full_name,
          avatar_url
        )
      `)
    return { data, error }
  },

  // Real-time subscriptions
  subscribeToProject: (projectId, callback) => {
    return supabase
      .channel(`project:${projectId}`)
      .on('postgres_changes', 
        { event: '*', schema: 'public', table: 'messages', filter: `project_id=eq.${projectId}` },
        callback
      )
      .subscribe()
  }
}

export default supabase