<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <!-- Header -->
            <div class="mb-8">
              <h1 class="text-3xl font-bold text-gray-900">Welcome to GrowDev</h1>
              <p class="mt-2 text-lg text-gray-600">Manage your software development projects with ease</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
              <div class="card p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                      <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Projects</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.totalProjects }}</p>
                  </div>
                </div>
              </div>

              <div class="card p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Projects</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.activeProjects }}</p>
                  </div>
                </div>
              </div>

              <div class="card p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                      <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending Tasks</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.pendingTasks }}</p>
                  </div>
                </div>
              </div>

              <div class="card p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                      <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Team Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ stats.teamMembers }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Projects -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                  <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
                  <Link href="/projects" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    View all
                  </Link>
                </div>
                
                <div class="space-y-4">
                  <div v-for="project in recentProjects" :key="project.id" class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                    <div>
                      <h3 class="font-medium text-gray-900">{{ project.name }}</h3>
                      <p class="text-sm text-gray-500">{{ project.description }}</p>
                      <div class="flex items-center mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getStageColor(project.stage)">
                          {{ project.stage }}
                        </span>
                      </div>
                    </div>
                    <div class="text-right">
                      <p class="text-sm text-gray-500">{{ formatDate(project.updated_at) }}</p>
                    </div>
                  </div>
                  
                  <div v-if="recentProjects.length === 0" class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
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

              <!-- Quick Actions -->
              <div class="card p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                
                <div class="space-y-3">
                  <Link href="/projects/create" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-3">
                      <p class="font-medium text-gray-900">Create New Project</p>
                      <p class="text-sm text-gray-500">Start a new software development project</p>
                    </div>
                  </Link>

                  <Link href="/teams/invite" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-3">
                      <p class="font-medium text-gray-900">Invite Team Members</p>
                      <p class="text-sm text-gray-500">Add developers, designers, and testers</p>
                    </div>
                  </Link>

                  <Link href="/templates" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex-shrink-0">
                      <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-3">
                      <p class="font-medium text-gray-900">Browse Templates</p>
                      <p class="text-sm text-gray-500">SRS, README, and documentation templates</p>
                    </div>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed } from 'vue'

const props = defineProps({
  projects: Array,
  stats: Object
})

const recentProjects = computed(() => {
  return props.projects?.slice(0, 5) || []
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

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}
</script>