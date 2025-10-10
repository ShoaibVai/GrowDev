<template>
  <Head title="Confirm Email" />
  
  <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <div class="flex justify-center">
        <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
      </div>
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Check your email
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        We've sent you a confirmation link
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <div class="text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          
          <h3 class="text-lg font-medium text-gray-900 mb-2">
            Email confirmation required
          </h3>
          
          <p class="text-sm text-gray-600 mb-6">
            Please check your email inbox and click the confirmation link to verify your account.
          </p>

          <div class="space-y-4">
            <form @submit.prevent="resendEmail" class="space-y-4">
              <div>
                <label for="email" class="sr-only">Email address</label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                  placeholder="Enter your email address"
                />
              </div>
              
              <button
                type="submit"
                :disabled="form.processing"
                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
              >
                <span v-if="form.processing">Sending...</span>
                <span v-else>Resend confirmation email</span>
              </button>
            </form>

            <div class="text-center">
              <Link
                :href="route('login')"
                class="text-sm text-blue-600 hover:text-blue-500"
              >
                Back to login
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
  email: ''
})

const resendEmail = () => {
  form.post(route('verification.resend'), {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>