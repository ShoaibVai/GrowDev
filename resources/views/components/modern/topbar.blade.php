<header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
    <!-- Mobile Menu Button -->
    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
    </button>

    <!-- Search Bar -->
    <div class="flex-1 flex justify-center lg:justify-start lg:ml-6">
        <div class="w-full max-w-lg lg:max-w-xs relative" x-data="{ query: '', results: null, open: false }"
             @click.away="open = false">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" x-model="query"
                   @input.debounce.300ms="if (query.length >= 2) { fetch(`/api/search?q=${encodeURIComponent(query)}`).then(r => r.json()).then(d => { results = d; open = true; }); } else { results = null; open = false; }"
                   @keydown.escape="open = false"
                   @keydown.enter="if (query) window.location.href = '/search?q=' + encodeURIComponent(query)"
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-gray-100 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out"
                   placeholder="Search projects, tasks... (Ctrl+K)">
            <div x-show="open && results" x-cloak
                 class="absolute top-full left-0 right-0 mt-1 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-80 overflow-y-auto">
                <template x-if="results.projects?.length">
                    <div class="p-2">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase px-2 py-1">Projects</p>
                        <template x-for="p in results.projects.slice(0, 3)">
                            <a :href="`/projects/${p.id}`" class="block px-2 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" x-text="p.name"></a>
                        </template>
                    </div>
                </template>
                <template x-if="results.tasks?.length">
                    <div class="p-2 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase px-2 py-1">Tasks</p>
                        <template x-for="t in results.tasks.slice(0, 3)">
                            <a :href="`/tasks/${t.id}`" class="block px-2 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded" x-text="t.title"></a>
                        </template>
                    </div>
                </template>
                <div class="p-2 border-t border-gray-100 dark:border-gray-700">
                    <a :href="`/search?q=${encodeURIComponent(query)}`" class="block px-2 py-1.5 text-sm text-indigo-600 dark:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-center">
                        View all results →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="ml-4 flex items-center md:ml-6 space-x-4">

        <!-- Theme Toggle -->
        <button @click="toggle()" class="p-1 rounded-full text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors"
                :title="preference === 'dark' ? 'Switch to light mode' : preference === 'light' ? 'Switch to system mode' : 'Switch to dark mode'">
            <template x-if="isDark">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </template>
            <template x-if="!isDark">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </template>
        </button>

        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="bg-white dark:bg-gray-800 p-1 rounded-full text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                <span class="sr-only">View notifications</span>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                @endif
            </button>

            <!-- Dropdown -->
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                 style="display: none;">
                 <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                     <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Notifications</span>
                     <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800">View All</a>
                 </div>
                 <div class="max-h-64 overflow-y-auto">
                    @forelse(Auth::user()->notifications->take(5) as $notification)
                        <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                            <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $notification->data['title'] ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $notification->data['message'] ?? '' }}</p>
                        </a>
                    @empty
                        <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">No new notifications</div>
                    @endforelse
                 </div>
            </div>
        </div>

        <!-- Quick Action Button -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </button>
             <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                 style="display: none;">
                 <a href="{{ route('projects.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">New Project</a>
                 <a href="{{ route('documentation.srs.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">New SRS Document</a>
                 <a href="{{ route('teams.create') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">New Team</a>
            </div>
        </div>

    </div>
</header>
