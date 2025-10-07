<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
            <p class="mt-2 text-gray-600">Manage your software development projects</p>
          </div>
          <Link href="/projects/create" class="btn btn-primary">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Project
          </Link>
        </div>

        <!-- Filters -->
        <div class="mb-6 flex flex-wrap gap-4">
          <select v-model="filters.stage" class="form-input w-auto">
            <option value="">All Stages</option>
            <option value="Idea">Idea</option>
            <option value="Planning">Planning</option>
            <option value="Design">Design</option>
            <option value="Development">Development</option>
            <option value="Testing">Testing</option>
            <option value="Deployment">Deployment</option>
          </select>
          
          <select v-model="filters.type" class="form-input w-auto">
            <option value="">All Types</option>
            <option value="solo">Solo</option>
            <option value="team">Team</option>
          </select>
          
          <select v-model="filters.role" class="form-input w-auto">
            <option value="">All Roles</option>
            <option value="owner">Owner</option>
            <option value="member">Member</option>
          </select>
        </div>

        <!-- Projects Grid -->
        <div v-if="filteredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="project in filteredProjects" :key="project.id" class="card p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-start justify-between mb-4">
              <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                  <Link :href="`/projects/${project.id}`" class="hover:text-primary-600">
                    {{ project.name }}
                  </Link>
                </h3>
                <p class="text-gray-600 text-sm line-clamp-3">{{ project.description }}</p>
              </div>
              
              <div class="flex-shrink-0 ml-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getStageColor(project.stage)">
                  {{ project.stage }}
                </span>
              </div>
            </div>

            <!-- Tech Stack -->
            <div v-if="project.tech_stack && project.tech_stack.length > 0" class="mb-4">
              <div class="flex flex-wrap gap-1">
                <span v-for="tech in project.tech_stack.slice(0, 3)" :key="tech" 
                      class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                  {{ tech }}
                </span>
                <span v-if="project.tech_stack.length > 3" 
                      class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                  +{{ project.tech_stack.length - 3 }}
                </span>
              </div>
            </div>

            <!-- Project Info -->
            <div class="flex items-center justify-between text-sm text-gray-500">
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                {{ getProjectMemberCount(project) }}
              </div>
              
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ formatDate(project.updated_at) }}
              </div>
            </div>

            <!-- Project Actions -->
            <div class="mt-4 flex space-x-2">
              <Link :href="`/projects/${project.id}`" class="flex-1 text-center btn btn-secondary text-sm">
                View
              </Link>
              <Link v-if="isProjectOwner(project)" :href="`/projects/${project.id}/edit`" class="flex-1 text-center btn btn-primary text-sm">
                Edit
              </Link>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No projects found</h3>
          <p class="mt-1 text-sm text-gray-500">
            {{ filters.stage || filters.type || filters.role ? 'Try adjusting your filters.' : 'Get started by creating a new project.' }}
          </p>
          <div class="mt-6">
            <Link href="/projects/create" class="btn btn-primary">
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              New Project
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed } from 'vue'

const props = defineProps({
  projects: Array
})

const filters = ref({
  stage: '',
  type: '',
  role: ''
})

const filteredProjects = computed(() => {
  let filtered = props.projects || []
  
  if (filters.value.stage) {
    filtered = filtered.filter(project => project.stage === filters.value.stage)
  }
  
  if (filters.value.type) {
    filtered = filtered.filter(project => project.type === filters.value.type)
  }
  
  if (filters.value.role) {
    // This would need to be implemented based on user's role in the project
    if (filters.value.role === 'owner') {
      filtered = filtered.filter(project => isProjectOwner(project))
    } else if (filters.value.role === 'member') {
      filtered = filtered.filter(project => !isProjectOwner(project))
    }
  }
  
  return filtered
})

const getStageColor = (stage) => {
  const colors = {
    'Idea': 'bg-gray-100 text-gray-800',
    'Planning': 'bg-blue-100 text-blue-800',
    'Design': 'bg-purple-100 text-purple-800',
    'Development': 'bg-yellow-100 text-yellow-800',
    'Testing': 'bg-orange-100 text-orange-800',
    'Deployment': 'bg-green-100 text-green-800'
  }
  return colors[stage] || 'bg-gray-100 text-gray-800'
}

const getProjectMemberCount = (project) => {
  const memberCount = project.project_members ? project.project_members.length : 0
  return memberCount + 1 // +1 for owner
}

const isProjectOwner = (project) => {
  // This would check if current user is the project owner
  // For now, returning true as placeholder
  return true
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>