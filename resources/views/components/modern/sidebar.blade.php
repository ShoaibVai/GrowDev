<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden"></div>

<div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
     class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-xl">
    
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-slate-950 shadow-md">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <x-application-logo class="h-8 w-8 fill-current text-indigo-500" />
            <span class="text-xl font-bold tracking-wider">GrowDev</span>
        </a>
    </div>

    <!-- Nav Links -->
    <nav class="mt-5 px-4 space-y-2">
        
        <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu</p>

        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <a href="{{ route('projects.index') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('projects.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            Projects
        </a>

        @if(Route::has('teams.index'))
        <a href="{{ route('teams.index') }}" 
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-150 {{ request()->routeIs('teams.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Teams
        </a>
        @endif

        <!-- Documentation Group -->
        <div x-data="{ expanded: {{ request()->routeIs('documentation.*') ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-slate-300 rounded-lg hover:bg-slate-800 hover:text-white transition-colors duration-150">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Documentation
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-90': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
            <div x-show="expanded" x-collapse class="pl-11 pr-4 space-y-1 mt-1">
                <a href="{{ route('documentation.srs.index') }}" class="block px-2 py-2 text-sm text-slate-400 hover:text-white rounded-md hover:bg-slate-800 transition-colors {{ request()->routeIs('documentation.srs.*') ? 'text-white bg-slate-800' : '' }}">
                    SRS Documents
                </a>
                <!-- Add more doc links here if needed -->
            </div>
        </div>

    </nav>

    <!-- Bottom Section (User Profile / Logout) -->
    <div class="absolute bottom-0 w-full p-4 bg-slate-950 border-t border-slate-800">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <a href="{{ route('profile.edit') }}" class="block hover:opacity-80 transition-opacity">
                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </a>
            </div>
            <div class="ml-3">
                <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-white hover:underline">{{ Auth::user()->name }}</a>
                <p class="text-xs text-slate-400 truncate w-32">{{ Auth::user()->email }}</p>
            </div>
            <div class="ml-auto flex items-center space-x-3">
                <a href="{{ route('profile.edit') }}" class="text-slate-400 hover:text-white transition-colors" title="Profile">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-white transition-colors" title="Log Out">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
