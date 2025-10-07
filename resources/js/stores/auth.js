import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { auth } from '@/services/supabase'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const session = ref(null)
  const loading = ref(false)

  const isAuthenticated = computed(() => !!user.value)
  const userRole = computed(() => user.value?.user_metadata?.role || 'developer')

  const login = async (email, password) => {
    loading.value = true
    try {
      const { data, error } = await auth.signIn(email, password)
      if (error) throw error
      
      user.value = data.user
      session.value = data.session
      return { success: true }
    } catch (error) {
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  const register = async (email, password, userData) => {
    loading.value = true
    try {
      const { data, error } = await auth.signUp(email, password, userData)
      if (error) throw error
      
      return { success: true, data }
    } catch (error) {
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    loading.value = true
    try {
      const { error } = await auth.signOut()
      if (error) throw error
      
      user.value = null
      session.value = null
      return { success: true }
    } catch (error) {
      return { success: false, error: error.message }
    } finally {
      loading.value = false
    }
  }

  const initializeAuth = async () => {
    try {
      const { data } = await auth.getCurrentUser()
      user.value = data.user
    } catch (error) {
      console.error('Auth initialization error:', error)
    }
  }

  const setUser = (userData) => {
    user.value = userData
  }

  const setSession = (sessionData) => {
    session.value = sessionData
  }

  return {
    user,
    session,
    loading,
    isAuthenticated,
    userRole,
    login,
    register,
    logout,
    initializeAuth,
    setUser,
    setSession
  }
})