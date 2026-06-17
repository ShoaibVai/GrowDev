<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="growdevTheme()"
      x-init="init()"
      :data-theme="theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>{{ config('app.name', 'GrowDev') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased" style="font-family:var(--font-sans);background:var(--color-base);color:var(--color-text)">

<div class="flex h-screen overflow-hidden" style="background:var(--color-base)">

    {{-- ============ SIDEBAR ============ --}}
    <aside
        x-data="{ collapsed: localStorage.getItem('gd-sidebar-collapsed') === 'true' }"
        x-init="$watch('collapsed', v => localStorage.setItem('gd-sidebar-collapsed', v))"
        :class="collapsed ? 'w-[56px]' : 'w-[220px]'"
        class="flex-shrink-0 transition-all duration-200 ease-out h-full flex flex-col"
        style="background:var(--color-surface);border-right:1px solid var(--color-border)">

        {{-- Logo --}}
        <div class="flex items-center h-12 px-3" :class="collapsed ? 'justify-center' : 'justify-start'"
             style="border-bottom:1px solid var(--color-border)">
            <span class="block h-7 w-7 rounded-md flex items-center justify-center flex-shrink-0"
                  style="background:var(--color-accent)">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </span>
            <span x-show="!collapsed" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                  class="ml-2.5 text-sm font-bold tracking-wide whitespace-nowrap"
                  style="font-family:var(--font-mono);color:var(--color-text)">
                GROWDEV
            </span>
        </div>

        {{-- Nav Items --}}
        <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">
            {{-- WORKSPACE section --}}
            <p x-show="!collapsed" class="px-3 pt-3 pb-1 text-[10px] font-semibold uppercase tracking-widest" style="color:var(--color-text-faint)">Workspace</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center rounded-md transition-colors duration-120 hover:bg-gd-surface-3 group"
               :class="collapsed ? 'justify-center px-0 h-9 w-9 mx-auto' : 'px-3 h-9'"
               style="color:{{ request()->routeIs('dashboard') ? 'var(--color-accent)' : 'var(--color-text-muted)' }}">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span x-show="!collapsed" class="ml-3 text-[13px] font-medium whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('projects.index') }}"
               class="flex items-center rounded-md transition-colors duration-120 hover:bg-gd-surface-3 group"
               :class="collapsed ? 'justify-center px-0 h-9 w-9 mx-auto' : 'px-3 h-9'"
               style="color:{{ request()->routeIs('projects.*') && !request()->routeIs('projects.*.sprints.*') ? 'var(--color-accent)' : 'var(--color-text-muted)' }}">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <span x-show="!collapsed" class="ml-3 text-[13px] font-medium whitespace-nowrap">Projects</span>
            </a>

            <a href="{{ route('tasks.my-tasks') }}"
               class="flex items-center rounded-md transition-colors duration-120 hover:bg-gd-surface-3 group"
               :class="collapsed ? 'justify-center px-0 h-9 w-9 mx-auto' : 'px-3 h-9'"
               style="color:{{ request()->routeIs('tasks.my-tasks') ? 'var(--color-accent)' : 'var(--color-text-muted)' }}">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span x-show="!collapsed" class="ml-3 text-[13px] font-medium whitespace-nowrap">My Tasks</span>
            </a>

            {{-- TEAM section --}}
            <p x-show="!collapsed" class="px-3 pt-5 pb-1 text-[10px] font-semibold uppercase tracking-widest" style="color:var(--color-text-faint)">Team</p>

            @if(Route::has('teams.index'))
            <a href="{{ route('teams.index') }}"
               class="flex items-center rounded-md transition-colors duration-120 hover:bg-gd-surface-3 group"
               :class="collapsed ? 'justify-center px-0 h-9 w-9 mx-auto' : 'px-3 h-9'"
               style="color:{{ request()->routeIs('teams.*') ? 'var(--color-accent)' : 'var(--color-text-muted)' }}">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span x-show="!collapsed" class="ml-3 text-[13px] font-medium whitespace-nowrap">Teams</span>
            </a>
            @endif

            <a href="{{ route('documentation.srs.index') }}"
               class="flex items-center rounded-md transition-colors duration-120 hover:bg-gd-surface-3 group"
               :class="collapsed ? 'justify-center px-0 h-9 w-9 mx-auto' : 'px-3 h-9'"
               style="color:{{ request()->routeIs('documentation.*') ? 'var(--color-accent)' : 'var(--color-text-muted)' }}">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="!collapsed" class="ml-3 text-[13px] font-medium whitespace-nowrap">Docs</span>
            </a>
        </nav>

        {{-- Bottom: collapse toggle + user --}}
        <div style="border-top:1px solid var(--color-border)" :class="collapsed ? 'p-2' : 'p-3'">
            <div :class="collapsed ? 'flex-col space-y-2' : 'flex items-center justify-between'">
                <div class="flex items-center" :class="collapsed ? 'justify-center' : ''">
                    <a href="{{ route('profile.edit') }}" class="gd-avatar flex-shrink-0" title="{{ Auth::user()->name }}">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </a>
                    <div x-show="!collapsed" class="ml-3 min-w-0 flex-1">
                        <p class="text-[13px] font-medium truncate" style="color:var(--color-text)">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] truncate" style="color:var(--color-text-faint);font-family:var(--font-mono)">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <button @click="collapsed = !collapsed"
                        class="gd-btn-icon rounded-md hover:bg-gd-surface-3 flex-shrink-0"
                        :class="collapsed ? '' : 'ml-2'"
                        style="color:var(--color-text-muted)" title="Toggle sidebar">
                    <svg class="h-4 w-4 transition-transform duration-200" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    {{-- ============ MAIN CONTENT ============ --}}
    <div class="flex flex-col flex-1 min-w-0">

        {{-- ============ TOPBAR ============ --}}
        <header class="sticky top-0 z-40 flex items-center h-12 px-4 flex-shrink-0"
                style="background:color-mix(in srgb, var(--color-base) 85%, transparent);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid var(--color-border)">

            {{-- Breadcrumb / Page context --}}
            <div class="flex items-center gap-3 min-w-0 flex-1">
                @if(isset($header))
                    <div class="text-[13px] truncate" style="color:var(--color-text)">
                        {{ $header }}
                    </div>
                @endif
            </div>

            {{-- Search --}}
            <div class="hidden sm:flex items-center mr-3" x-data="{ q:'', open:false, results:null }" @click.away="open=false">
                <div class="relative">
                    <input type="text" x-model="q"
                           @input.debounce.300ms="if(q.length>=2){fetch('/api/search?q='+encodeURIComponent(q)).then(r=>r.json()).then(d=>{results=d;open=true})}else{results=null;open=false}"
                           @keydown.escape="open=false"
                           @keydown.enter="if(q)window.location.href='/search?q='+encodeURIComponent(q)"
                           placeholder="Search..."
                           class="gd-input h-7 w-56 text-[12px]"
                           style="padding-left:28px">
                    <svg class="absolute left-2 top-1/2 -translate-y-1/2 h-3.5 w-3.5 pointer-events-none" style="color:var(--color-text-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>

                    {{-- Search results dropdown --}}
                    <div x-show="open && results" x-cloak
                         x-transition:enter="transition ease-out duration-160" x-transition:enter-start="opacity-0 scale-97" x-transition:enter-end="opacity-100 scale-100"
                         class="absolute top-full right-0 mt-1 w-72 gd-dropdown p-2 max-h-80 overflow-y-auto">
                        <template x-if="results.projects?.length">
                            <div>
                                <p class="text-[10px] font-semibold uppercase px-2 py-1" style="color:var(--color-text-faint)">Projects</p>
                                <template x-for="p in results.projects.slice(0,4)">
                                    <a :href="'/projects/'+p.id" class="block px-2 py-1.5 rounded text-[13px] hover:bg-gd-surface-3" style="color:var(--color-text)" x-text="p.name"></a>
                                </template>
                            </div>
                        </template>
                        <template x-if="results.tasks?.length">
                            <div class="mt-1" style="border-top:1px solid var(--color-border)">
                                <p class="text-[10px] font-semibold uppercase px-2 py-1 mt-1" style="color:var(--color-text-faint)">Tasks</p>
                                <template x-for="t in results.tasks.slice(0,4)">
                                    <a :href="'/tasks/'+t.id" class="block px-2 py-1.5 rounded text-[13px] hover:bg-gd-surface-3" style="color:var(--color-text)">
                                        <span class="gd-chip text-[10px] mr-1.5">T-<span x-text="t.id"></span></span>
                                        <span x-text="t.title"></span>
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="relative mr-2" x-data="{ open:false }" @click.away="open=false">
                <button @click="open=!open" class="gd-btn-icon gd-btn-ghost relative">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @php
                        try { $unreadCount = Auth::user()->unreadNotifications->count(); }
                        catch (\Throwable $e) { $unreadCount = 0; }
                    @endphp
                    @if($unreadCount > 0)
                        <span class="absolute top-0.5 right-0.5 w-2 h-2 rounded-full" style="background:var(--color-danger)"></span>
                    @endif
                </button>
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-160" x-transition:enter-start="opacity-0 scale-97" x-transition:enter-end="opacity-100 scale-100"
                      class="absolute right-0 mt-2 gd-dropdown w-80" style="min-width:320px">
                    <div class="px-4 py-3 flex justify-between items-center" style="border-bottom:1px solid var(--color-border)">
                        <span class="text-[13px] font-semibold" style="color:var(--color-text)">Notifications</span>
                        <a href="{{ route('notifications.index') }}" class="text-[12px] hover:underline" style="color:var(--color-accent)">View all</a>
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        @php
                            try { $notifications = Auth::user()->notifications->take(5); }
                            catch (\Throwable $e) { $notifications = collect(); }
                        @endphp
                        @forelse($notifications as $n)
                            @php $d = $n->data; $type = $d['type'] ?? 'default'; @endphp
                            <a href="{{ route('notifications.read', $n->id) }}"
                               class="block px-4 py-3 hover:bg-gd-surface-3 transition-colors duration-120" style="{{ $n->read_at ? '' : 'border-left:2px solid var(--color-accent)' }}">
                                <p class="text-[13px] font-medium truncate" style="color:var(--color-text)">{{ $d['title'] ?? ($d['task_title'] ?? 'Notification') }}</p>
                                <p class="text-[12px] mt-0.5 truncate" style="color:var(--color-text-muted)">{{ $d['message'] ?? '' }}</p>
                                <p class="text-[11px] mt-1" style="color:var(--color-text-faint);font-family:var(--font-mono)">{{ $n->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-[13px]" style="color:var(--color-text-muted)">No notifications</div>
                        @endforelse
                    </div>
                    @if($unreadCount > 0)
                        <div class="px-4 py-2" style="border-top:1px solid var(--color-border)">
                            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                @csrf
                                <button type="submit" class="text-[12px] w-full text-center" style="color:var(--color-accent)">Mark all as read</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick add --}}
            <div class="relative mr-2" x-data="{ open:false }" @click.away="open=false">
                <button @click="open=!open" class="gd-btn-icon rounded-md hover:bg-gd-surface-3" style="color:var(--color-text-muted)">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-160" x-transition:enter-start="opacity-0 scale-97" x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 gd-dropdown w-44 py-1">
                    <a href="{{ route('projects.create') }}" class="block px-3 py-2 text-[13px] hover:bg-gd-surface-3" style="color:var(--color-text)">New Project</a>
                    <a href="{{ route('documentation.srs.create') }}" class="block px-3 py-2 text-[13px] hover:bg-gd-surface-3" style="color:var(--color-text)">New SRS Doc</a>
                    <a href="{{ route('teams.create') }}" class="block px-3 py-2 text-[13px] hover:bg-gd-surface-3" style="color:var(--color-text)">New Team</a>
                </div>
            </div>

            {{-- Theme toggle --}}
            <button @click="toggleTheme()" class="gd-btn-icon gd-btn-ghost mr-1" title="Toggle theme">
                <svg x-show="theme === 'dark'" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg x-show="theme === 'light'" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>

            {{-- User menu --}}
            <div class="relative" x-data="{ open:false }" @click.away="open=false">
                <button @click="open=!open" class="gd-avatar hover:opacity-80 transition-opacity" title="{{ Auth::user()->name }}">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </button>
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-160" x-transition:enter-start="opacity-0 scale-97" x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 gd-dropdown w-48 py-1">
                    <div class="px-4 py-2" style="border-bottom:1px solid var(--color-border)">
                        <p class="text-[13px] font-medium" style="color:var(--color-text)">{{ Auth::user()->name }}</p>
                        <p class="text-[11px]" style="color:var(--color-text-faint);font-family:var(--font-mono)">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-[13px] hover:bg-gd-surface-3" style="color:var(--color-text)">Profile</a>
                    <a href="{{ route('profile.cv-pdf') }}" class="block px-4 py-2 text-[13px] hover:bg-gd-surface-3" style="color:var(--color-text)">Export CV</a>
                    <div style="border-top:1px solid var(--color-border);margin:4px 0"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-[13px] hover:bg-gd-surface-3" style="color:var(--color-danger)">Log Out</button>
                    </form>
                </div>
            </div>
        </header>

        {{-- ============ PAGE CONTENT ============ --}}
        <main class="flex-1 overflow-y-auto">
            {{-- Alerts --}}
            <div class="max-w-[1280px] mx-auto px-6 pt-4">
                @if (session('success'))
                    <div class="gd-toast gd-toast-success mb-4" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)" role="alert">
                        <svg class="h-4 w-4 flex-shrink-0 mt-0.5" style="color:var(--color-success)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="color:var(--color-text)">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="gd-toast gd-toast-error mb-4" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)" role="alert">
                        <svg class="h-4 w-4 flex-shrink-0 mt-0.5" style="color:var(--color-danger)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span style="color:var(--color-text)">{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            {{-- Header slot --}}
            @isset($header)
                <div class="max-w-[1280px] mx-auto px-6 py-2">
                    {{ $header }}
                </div>
            @endisset

            {{-- Main content slot --}}
            <div class="max-w-[1280px] mx-auto px-6 pb-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

@stack('scripts')

{{-- Theme Manager --}}
<script>
function growdevTheme() {
    return {
        theme: 'dark',
        init() {
            const saved = localStorage.getItem('gd-theme');
            if (saved === 'light' || saved === 'dark') {
                this.theme = saved;
            } else {
                this.theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                if (!localStorage.getItem('gd-theme')) {
                    this.theme = e.matches ? 'dark' : 'light';
                }
            });
        },
        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('gd-theme', this.theme);
        }
    };
}
</script>

</body>
</html>
