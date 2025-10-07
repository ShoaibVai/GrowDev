<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <Link href="/" class="flex items-center">
              <div class="flex-shrink-0">
                <h1 class="text-2xl font-bold text-primary-600">GrowDev</h1>
              </div>
            </Link>
            
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8" v-if="$page.props.auth.user">
              <Link 
                href="/dashboard" 
                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                :class="{ 'border-primary-500 text-primary-600': $page.component === 'Dashboard' }"
              >
                Dashboard
              </Link>
              <Link 
                href="/projects" 
                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                :class="{ 'border-primary-500 text-primary-600': $page.component.startsWith('Projects') }"
              >
                Projects
              </Link>
              <Link 
                href="/teams" 
                class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200"
                :class="{ 'border-primary-500 text-primary-600': $page.component.startsWith('Teams') }"
              >
                Teams
              </Link>
            </div>
          </div>

          <div class="flex items-center space-x-4">
            <div v-if="$page.props.auth.user" class="relative">
              <Menu as="div" class="relative">
                <MenuButton class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                  <img 
                    class="h-8 w-8 rounded-full" 
                    :src="$page.props.auth.user.user_metadata?.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent($page.props.auth.user.email)}&background=3b82f6&color=fff`"
                    :alt="$page.props.auth.user.email"
                  >
                  <span class="hidden md:block text-gray-700">{{ $page.props.auth.user.user_metadata?.full_name || $page.props.auth.user.email }}</span>
                  <ChevronDownIcon class="h-4 w-4 text-gray-400" />
                </MenuButton>
                
                <transition
                  enter-active-class="transition duration-100 ease-out"
                  enter-from-class="transform scale-95 opacity-0"
                  enter-to-class="transform scale-100 opacity-100"
                  leave-active-class="transition duration-75 ease-in"
                  leave-from-class="transform scale-100 opacity-100"
                  leave-to-class="transform scale-95 opacity-0"
                >
                  <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <MenuItem v-slot="{ active }">
                      <Link
                        href="/profile"
                        :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                      >
                        Your Profile
                      </Link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <Link
                        href="/settings"
                        :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                      >
                        Settings
                      </Link>
                    </MenuItem>
                    <MenuItem v-slot="{ active }">
                      <Link
                        href="/logout"
                        method="post"
                        as="button"
                        :class="[active ? 'bg-gray-100' : '', 'block w-full text-left px-4 py-2 text-sm text-gray-700']"
                      >
                        Sign out
                      </Link>
                    </MenuItem>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
            
            <div v-else class="flex items-center space-x-4">
              <Link 
                href="/login" 
                class="text-gray-500 hover:text-gray-700 font-medium"
              >
                Sign in
              </Link>
              <Link 
                href="/register" 
                class="btn btn-primary"
              >
                Get Started
              </Link>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <slot />
    </main>

    <!-- Toast Container -->
    <div id="toast-container"></div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
</script>