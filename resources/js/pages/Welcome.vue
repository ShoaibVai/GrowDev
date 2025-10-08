<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-100 to-purple-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 relative overflow-hidden">
    <!-- Animated Background Patterns -->
    <div class="absolute inset-0">
      <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 animate-pulse"></div>
      <div class="absolute top-0 left-0 w-full h-full">
        <div class="absolute top-20 left-20 w-64 h-64 bg-gradient-to-r from-indigo-400/20 to-purple-400/20 rounded-full filter blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-gradient-to-r from-purple-400/20 to-pink-400/20 rounded-full filter blur-3xl animate-float" style="animation-delay: 2s;"></div>
      </div>
    </div>

    <!-- Header -->
    <header class="relative z-10 py-6 backdrop-blur-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent animate-fadeInUp">GrowDev</h1>
        <div class="flex gap-4 animate-fadeInUp" style="animation-delay: 0.3s;">
          <Link :href="route('login')" class="px-6 py-2 text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 font-medium transition-all duration-300 hover:scale-105">
            Sign In
          </Link>
          <Link :href="route('register')" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            Get Started
          </Link>
        </div>
      </div>
    </header>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Hero Section -->
      <div class="text-center mb-20">
        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white mb-6 animate-fadeInUp">
          Welcome to <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">GrowDev</span>
        </h2>
        <p class="text-lg sm:text-xl lg:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-4xl mx-auto leading-relaxed animate-fadeInUp" style="animation-delay: 0.3s;">
          Transform your software development process with our comprehensive project management platform.
          <br class="hidden sm:block" />
          <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Streamline workflows, enhance collaboration, and accelerate delivery.</span>
        </p>
        
        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center animate-fadeInUp" style="animation-delay: 0.6s;">
          <Link :href="route('register')" class="group inline-flex items-center justify-center px-10 py-5 text-lg font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-500 shadow-2xl hover:shadow-indigo-500/25 transform hover:-translate-y-2 hover:scale-105">
            <svg class="-ml-1 mr-3 h-6 w-6 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span class="relative">
              Start Your Project
              <span class="absolute inset-0 bg-white/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
            </span>
          </Link>
          <Link :href="route('login')" class="group inline-flex items-center justify-center px-10 py-5 text-lg font-semibold text-indigo-600 bg-white/80 backdrop-blur-sm dark:bg-gray-800/80 dark:text-indigo-400 rounded-2xl hover:bg-white dark:hover:bg-gray-700 transition-all duration-500 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 border border-indigo-200 dark:border-indigo-800">
            <svg class="-ml-1 mr-3 h-6 w-6 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Sign In
          </Link>
        </div>

        <!-- Stats Section -->
        <div class="mt-16 grid grid-cols-2 lg:grid-cols-4 gap-8 animate-fadeInUp" style="animation-delay: 0.9s;">
          <div class="text-center">
            <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">500+</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Projects Launched</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">50+</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Development Teams</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-green-600 dark:text-green-400">99%</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Success Rate</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-pink-600 dark:text-pink-400">24/7</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Support Available</div>
          </div>
        </div>
      </div>

      <!-- Features Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 mb-16">
        <!-- Team Collaboration -->
        <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
          <div class="mx-auto h-16 w-16 bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900 dark:to-indigo-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300">Team Collaboration</h3>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Seamlessly work together with developers, designers, testers, and project managers in one unified platform</p>
        </div>

        <!-- Guided Project Development -->
        <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
          <div class="mx-auto h-16 w-16 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900 dark:to-green-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 7l2 2 4-4"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-300">Guided Project Development</h3>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Follow structured workflows from idea conception to planning, design, development, testing, and successful deployment</p>
        </div>

        <!-- Documentation Templates -->
        <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
          <div class="mx-auto h-16 w-16 bg-gradient-to-br from-yellow-100 to-yellow-200 dark:from-yellow-900 dark:to-yellow-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors duration-300">Documentation Templates</h3>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Access ready-to-use templates for Software Requirements Specification, README files, and comprehensive development logs</p>
        </div>

        <!-- Real-time Chat -->
        <div class="group bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
          <div class="mx-auto h-16 w-16 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-300">Real-time Communication</h3>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">Stay connected with project-based messaging system for instant team communication and seamless collaboration</p>
        </div>
      </div>

      <!-- Call to Action Section -->
      <div class="text-center mt-20 animate-fadeInUp" style="animation-delay: 1.2s;">
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl p-8 sm:p-12 shadow-2xl transform hover:scale-105 transition-all duration-500 relative overflow-hidden">
          <!-- Animated background overlay -->
          <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 to-purple-600/20 animate-pulse"></div>
          <div class="relative z-10">
            <h3 class="text-2xl sm:text-3xl font-bold text-white mb-6">Ready to Transform Your Development Process?</h3>
            <p class="text-lg sm:text-xl text-indigo-100 mb-8 max-w-2xl mx-auto leading-relaxed">
              Join thousands of development teams who have streamlined their workflow with GrowDev's comprehensive project management platform.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <Link :href="route('register')" class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-indigo-600 bg-white rounded-xl hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-2">
                <svg class="-ml-1 mr-3 h-6 w-6 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Start Your Journey
              </Link>
              <Link :href="route('login')" class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white border-2 border-white rounded-xl hover:bg-white hover:text-indigo-600 transition-all duration-300 transform hover:-translate-y-2">
                <svg class="-ml-1 mr-3 h-6 w-6 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Sign In Now
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
@keyframes float {
  0%, 100% { 
    transform: translateY(0px) rotate(0deg); 
  }
  50% { 
    transform: translateY(-20px) rotate(5deg); 
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slideInRight {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-float {
  animation: float 8s ease-in-out infinite;
}

.animate-fadeInUp {
  animation: fadeInUp 0.8s ease-out;
}

.animate-slideInLeft {
  animation: slideInLeft 0.8s ease-out;
}

.animate-slideInRight {
  animation: slideInRight 0.8s ease-out;
}

/* Responsive animations */
@media (max-width: 640px) {
  .animate-float {
    animation: float 6s ease-in-out infinite;
  }
  
  .animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
  }
}

/* Hover effects for better UX */
.group:hover .group-hover\:animate-bounce {
  animation: bounce 1s infinite;
}

.group:hover .group-hover\:rotate-12 {
  transform: rotate(12deg);
}

.group:hover .group-hover\:translate-x-1 {
  transform: translateX(0.25rem);
}

/* Custom gradient animations */
@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.bg-gradient-animated {
  background-size: 200% 200%;
  animation: gradientShift 3s ease infinite;
}
</style>