<header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
    <!-- Mobile Menu Button -->
    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
    </button>

    <!-- Search Bar -->
    <div class="flex-1 flex justify-center lg:justify-start lg:ml-6">
        <div class="w-full max-w-lg lg:max-w-xs relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" id="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Search projects, tasks...">
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="ml-4 flex items-center md:ml-6 space-x-4">
        
        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span class="sr-only">View notifications</span>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
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
                 class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                 style="display: none;">
                 <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                     <span class="text-sm font-semibold text-gray-700">Notifications</span>
                     <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800">View All</a>
                 </div>
                 <div class="max-h-64 overflow-y-auto">
                    @forelse(Auth::user()->notifications->take(5) as $notification)
                        <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 transition duration-150 ease-in-out">
                            <p class="text-sm text-gray-900 font-medium">{{ $notification->data['title'] ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $notification->data['message'] ?? '' }}</p>
                        </a>
                    @empty
                        <div class="px-4 py-3 text-sm text-gray-500 text-center">No new notifications</div>
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
                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                 style="display: none;">
                 <a href="{{ route('projects.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New Project</a>
                 <a href="{{ route('documentation.srs.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New SRS Document</a>
                 <a href="{{ route('teams.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New Team</a>
            </div>
        </div>

    </div>
</header>
