<template>
  <AppLayout>
    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="card p-8">
          <!-- Header -->
          <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Create New Project</h1>
            <p class="mt-2 text-gray-600">Start a new software development project and invite your team</p>
          </div>

          <!-- Form -->
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Project Name -->
            <div>
              <label for="name" class="form-label">Project Name</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                class="form-input"
                :class="{ 'border-red-500': errors.name }"
                placeholder="Enter project name"
                required
              />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="form-label">Description</label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="form-input"
                :class="{ 'border-red-500': errors.description }"
                placeholder="Describe your project, its goals, and what you want to build"
                required
              ></textarea>
              <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
            </div>

            <!-- Project Type and Stage -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="type" class="form-label">Project Type</label>
                <select
                  id="type"
                  v-model="form.type"
                  class="form-input"
                  :class="{ 'border-red-500': errors.type }"
                  required
                >
                  <option value="">Select type</option>
                  <option value="solo">Solo Project</option>
                  <option value="team">Team Project</option>
                </select>
                <p v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type }}</p>
              </div>

              <div>
                <label for="stage" class="form-label">Current Stage</label>
                <select
                  id="stage"
                  v-model="form.stage"
                  class="form-input"
                  :class="{ 'border-red-500': errors.stage }"
                  required
                >
                  <option value="">Select stage</option>
                  <option value="Idea">💡 Idea</option>
                  <option value="Planning">📋 Planning</option>
                  <option value="Design">🎨 Design</option>
                  <option value="Development">💻 Development</option>
                  <option value="Testing">🧪 Testing</option>
                  <option value="Deployment">🚀 Deployment</option>
                </select>
                <p v-if="errors.stage" class="mt-1 text-sm text-red-600">{{ errors.stage }}</p>
              </div>
            </div>

            <!-- Tech Stack -->
            <div>
              <label for="tech_stack" class="form-label">Technology Stack</label>
              <div class="space-y-2">
                <div class="flex flex-wrap gap-2">
                  <span v-for="(tech, index) in form.tech_stack" :key="index" 
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                    {{ tech }}
                    <button type="button" @click="removeTech(index)" class="ml-2 text-primary-600 hover:text-primary-800">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                    </button>
                  </span>
                </div>
                <div class="flex">
                  <input
                    v-model="newTech"
                    type="text"
                    class="form-input rounded-r-none"
                    placeholder="Add technology (e.g., Laravel, Vue.js, React)"
                    @keyup.enter="addTech"
                  />
                  <button type="button" @click="addTech" class="px-4 py-2 bg-primary-600 text-white rounded-r-md hover:bg-primary-700 transition-colors duration-200">
                    Add
                  </button>
                </div>
              </div>
              <p class="mt-1 text-sm text-gray-500">Add the technologies you plan to use in this project</p>
            </div>

            <!-- Deadline -->
            <div>
              <label for="deadline" class="form-label">Deadline (Optional)</label>
              <input
                id="deadline"
                v-model="form.deadline"
                type="date"
                class="form-input"
                :class="{ 'border-red-500': errors.deadline }"
              />
              <p v-if="errors.deadline" class="mt-1 text-sm text-red-600">{{ errors.deadline }}</p>
            </div>

            <!-- Repository URL -->
            <div>
              <label for="repository_url" class="form-label">Repository URL (Optional)</label>
              <input
                id="repository_url"
                v-model="form.repository_url"
                type="url"
                class="form-input"
                placeholder="https://github.com/username/project-name"
              />
              <p class="mt-1 text-sm text-gray-500">Link to your GitHub, GitLab, or other repository</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
              <Link href="/projects" class="btn btn-secondary">
                Cancel
              </Link>
              <button type="submit" class="btn btn-primary" :disabled="processing">
                <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ processing ? 'Creating...' : 'Create Project' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref } from 'vue'

const newTech = ref('')

const form = useForm({
  name: '',
  description: '',
  stage: '',
  type: '',
  tech_stack: [],
  deadline: '',
  repository_url: ''
})

const addTech = () => {
  if (newTech.value.trim() && !form.tech_stack.includes(newTech.value.trim())) {
    form.tech_stack.push(newTech.value.trim())
    newTech.value = ''
  }
}

const removeTech = (index) => {
  form.tech_stack.splice(index, 1)
}

const submit = () => {
  form.post(route('projects.store'), {
    onSuccess: () => {
      console.log('Project created successfully!')
    },
    onError: (errors) => {
      console.error('Please fix the errors and try again.')
    }
  })
}
</script>