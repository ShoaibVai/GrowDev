import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { db } from '@/services/supabase'
import { useAuthStore } from './auth'

export const useProjectStore = defineStore('projects', () => {
  const projects = ref([])
  const currentProject = ref(null)
  const loading = ref(false)
  const error = ref(null)

  const authStore = useAuthStore()

  const userProjects = computed(() => {
    if (!authStore.user) return []
    return projects.value.filter(project => 
      project.owner_id === authStore.user.id ||
      project.project_members?.some(member => member.user_id === authStore.user.id)
    )
  })

  const ownedProjects = computed(() => {
    if (!authStore.user) return []
    return projects.value.filter(project => project.owner_id === authStore.user.id)
  })

  const memberProjects = computed(() => {
    if (!authStore.user) return []
    return projects.value.filter(project => 
      project.project_members?.some(member => member.user_id === authStore.user.id)
    )
  })

  const fetchProjects = async () => {
    if (!authStore.user) return

    loading.value = true
    error.value = null
    
    try {
      const { data, error: fetchError } = await db.getProjects(authStore.user.id)
      if (fetchError) throw fetchError
      
      projects.value = data || []
    } catch (err) {
      error.value = err.message
      console.error('Error fetching projects:', err)
    } finally {
      loading.value = false
    }
  }

  const createProject = async (projectData) => {
    loading.value = true
    error.value = null
    
    try {
      const newProject = {
        ...projectData,
        owner_id: authStore.user.id,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }

      const { data, error: createError } = await db.createProject(newProject)
      if (createError) throw createError
      
      if (data && data[0]) {
        projects.value.push(data[0])
        return { success: true, project: data[0] }
      }
    } catch (err) {
      error.value = err.message
      console.error('Error creating project:', err)
      return { success: false, error: err.message }
    } finally {
      loading.value = false
    }
  }

  const updateProject = async (projectId, updates) => {
    loading.value = true
    error.value = null
    
    try {
      const { data, error: updateError } = await db.updateProject(projectId, {
        ...updates,
        updated_at: new Date().toISOString()
      })
      if (updateError) throw updateError
      
      if (data && data[0]) {
        const index = projects.value.findIndex(p => p.id === projectId)
        if (index !== -1) {
          projects.value[index] = data[0]
        }
        
        if (currentProject.value?.id === projectId) {
          currentProject.value = data[0]
        }
        
        return { success: true, project: data[0] }
      }
    } catch (err) {
      error.value = err.message
      console.error('Error updating project:', err)
      return { success: false, error: err.message }
    } finally {
      loading.value = false
    }
  }

  const setCurrentProject = (project) => {
    currentProject.value = project
  }

  const addProjectMember = async (projectId, userId, role) => {
    try {
      const { data, error: addError } = await db.addProjectMember(projectId, userId, role)
      if (addError) throw addError
      
      // Refresh projects to update member list
      await fetchProjects()
      
      return { success: true, member: data[0] }
    } catch (err) {
      console.error('Error adding project member:', err)
      return { success: false, error: err.message }
    }
  }

  const getProjectsByStage = (stage) => {
    return userProjects.value.filter(project => project.stage === stage)
  }

  const getProjectsByRole = (role) => {
    if (!authStore.user) return []
    
    return userProjects.value.filter(project => {
      if (project.owner_id === authStore.user.id) return true
      
      const member = project.project_members?.find(m => m.user_id === authStore.user.id)
      return member?.role === role
    })
  }

  return {
    projects,
    currentProject,
    loading,
    error,
    userProjects,
    ownedProjects,
    memberProjects,
    fetchProjects,
    createProject,
    updateProject,
    setCurrentProject,
    addProjectMember,
    getProjectsByStage,
    getProjectsByRole
  }
})