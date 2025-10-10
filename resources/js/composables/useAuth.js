import { ref, onMounted, readonly } from 'vue'
import { supabase, auth } from '@/supabase'
import { router } from '@inertiajs/vue3'

export function useAuth() {
  const user = ref(null)
  const session = ref(null)
  const loading = ref(true)
  const error = ref(null)

  // Sign up new user
  const signUp = async (email, password, metadata = {}) => {
    try {
      error.value = null
      loading.value = true
      
      const { data, error: signUpError } = await auth.signUp(email, password, metadata)
      
      if (signUpError) {
        error.value = signUpError.message
        return { success: false, error: signUpError }
      }

      // Check if email confirmation is required
      if (data.user && !data.session) {
        return { 
          success: true, 
          message: 'Please check your email to confirm your account',
          requiresConfirmation: true 
        }
      }

      // Auto login if no confirmation required
      if (data.session) {
        user.value = data.user
        session.value = data.session
        return { success: true, user: data.user }
      }

      return { success: true, data }
    } catch (err) {
      error.value = err.message
      return { success: false, error: err }
    } finally {
      loading.value = false
    }
  }

  // Sign in existing user
  const signIn = async (email, password) => {
    try {
      error.value = null
      loading.value = true
      
      const { data, error: signInError } = await auth.signIn(email, password)
      
      if (signInError) {
        error.value = signInError.message
        return { success: false, error: signInError }
      }

      if (data.user && data.session) {
        user.value = data.user
        session.value = data.session
        return { success: true, user: data.user }
      }

      return { success: false, error: { message: 'Login failed' } }
    } catch (err) {
      error.value = err.message
      return { success: false, error: err }
    } finally {
      loading.value = false
    }
  }

  // Sign out
  const signOut = async () => {
    try {
      error.value = null
      const { error: signOutError } = await auth.signOut()
      
      if (signOutError) {
        error.value = signOutError.message
        return { success: false, error: signOutError }
      }

      user.value = null
      session.value = null
      router.visit('/')
      return { success: true }
    } catch (err) {
      error.value = err.message
      return { success: false, error: err }
    }
  }

  // Initialize auth state
  const initializeAuth = async () => {
    try {
      loading.value = true
      
      // Get current session
      const { data: { session: currentSession } } = await auth.getCurrentSession()
      
      if (currentSession) {
        user.value = currentSession.user
        session.value = currentSession
      }
    } catch (err) {
      console.error('Error initializing auth:', err)
      error.value = err.message
    } finally {
      loading.value = false
    }
  }

  // Listen to auth state changes
  const setupAuthListener = () => {
    auth.onAuthStateChange((event, newSession) => {
      console.log('Auth event:', event, newSession)
      
      if (newSession) {
        user.value = newSession.user
        session.value = newSession
      } else {
        user.value = null
        session.value = null
      }
      
      loading.value = false
    })
  }

  // Check if user is authenticated
  const isAuthenticated = () => {
    return user.value !== null && session.value !== null
  }

  // Get user profile data
  const getUserProfile = async () => {
    if (!user.value) return null
    
    try {
      const { data, error: profileError } = await supabase
        .from('profiles')
        .select('*')
        .eq('id', user.value.id)
        .single()
      
      if (profileError) {
        console.error('Error fetching profile:', profileError)
        return null
      }
      
      return data
    } catch (err) {
      console.error('Error getting user profile:', err)
      return null
    }
  }

  // Initialize on mount
  onMounted(() => {
    initializeAuth()
    setupAuthListener()
  })

  return {
    user: readonly(user),
    session: readonly(session),
    loading: readonly(loading),
    error: readonly(error),
    signUp,
    signIn,
    signOut,
    isAuthenticated,
    getUserProfile,
    initializeAuth
  }
}