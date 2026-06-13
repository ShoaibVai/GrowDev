<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GrowDev') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400..600&family=JetBrains+Mono:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { background: var(--color-base); color: var(--color-text); font-family: var(--font-sans); }
        .hero-grid { background-image: repeating-linear-gradient(0deg, transparent, transparent 23px, var(--color-border) 23px, var(--color-border) 24px), repeating-linear-gradient(90deg, transparent, transparent 23px, var(--color-border) 23px, var(--color-border) 24px); background-size: 24px 24px; }
    </style>
</head>
<body class="antialiased">
<div class="min-h-screen flex flex-col">

    {{-- Nav --}}
    <nav class="flex items-center justify-between h-14 px-6" style="border-bottom:1px solid var(--color-border);background:color-mix(in srgb, var(--color-base) 90%, transparent);backdrop-filter:blur(12px);position:sticky;top:0;z-index:50">
        <div class="flex items-center gap-2.5">
            <span class="block h-7 w-7 rounded-md flex items-center justify-center" style="background:var(--color-accent)">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </span>
            <span class="text-sm font-bold tracking-wide" style="font-family:var(--font-mono)">GROWDEV</span>
        </div>
        <div class="flex items-center gap-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="gd-btn gd-btn-primary gd-btn-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="gd-btn gd-btn-ghost gd-btn-sm">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="gd-btn gd-btn-primary gd-btn-sm">Get Started</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    {{-- Hero --}}
    <main class="flex-1 flex items-center">
        <div class="max-w-6xl mx-auto px-6 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Left: Copy --}}
            <div>
                <h1 class="text-[64px] leading-[1.1] font-bold tracking-tight" style="font-family:var(--font-mono);color:var(--color-text)">
                    Build<span style="color:var(--color-accent)">.</span> Ship<span style="color:var(--color-accent)">.</span> Repeat<span style="color:var(--color-accent)">.</span>
                </h1>
                <p class="mt-6 text-[16px] leading-relaxed max-w-md" style="color:var(--color-text-muted)">
                    Plan your projects, track tasks on a live Kanban board, generate AI-powered implementation plans from SRS documents, and ship with your team — all from a tool that respects your workflow.
                </p>
                <div class="mt-8 flex gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="gd-btn gd-btn-primary gd-btn-lg">Go to Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="gd-btn gd-btn-primary gd-btn-lg">Start Building</a>
                        <a href="{{ route('login') }}" class="gd-btn gd-btn-secondary gd-btn-lg">Log In</a>
                    @endif
                </div>
            </div>

            {{-- Right: Animated Kanban Mockup --}}
            <div class="hidden lg:block relative" x-data="{ col: 0 }" x-init="setInterval(() => col = (col + 1) % 4, 3000)">
                <div class="rounded-xl p-4 relative overflow-hidden" style="background:var(--color-surface);border:1px solid var(--color-border);box-shadow:0 0 60px color-mix(in srgb, var(--color-accent) 8%, transparent)">
                    <div class="hero-grid absolute inset-0" style="opacity:0.03"></div>
                    <div class="grid grid-cols-2 gap-2 relative z-10">
                        @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $i => $col)
                            <div class="rounded-md p-2 text-center" style="background:{{ $i === 0 ? 'var(--color-base)' : 'transparent' }};border:{{ $i === 0 ? '1px solid var(--color-border)' : '1px solid transparent' }}">
                                <p class="text-[9px] font-semibold uppercase tracking-wider" style="color:var(--color-text-faint)">{{ $col }}</p>
                                <div class="mt-2 space-y-1.5">
                                    @php
                                        $cards = [
                                            0 => [['CSS Refactor', 'Medium'], ['API auth', 'High']],
                                            1 => [['User onboarding', 'Medium']],
                                            2 => [['E2E tests', 'High']],
                                            3 => [['SRS review', 'Low']],
                                        ];
                                    @endphp
                                    @foreach($cards[$i] as $c)
                                        <div class="rounded px-2 py-1.5 text-left transition-all duration-500"
                                             :class="{'opacity-30 scale-95': col === {{ $i }}, 'opacity-100': col !== {{ $i }}}"
                                             style="background:var(--color-base);border:1px solid var(--color-border)">
                                            <p class="text-[10px] font-medium truncate" style="color:var(--color-text)">{{ $c[0] }}</p>
                                            <p class="text-[8px] mt-0.5" style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $c[1] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Feature Grid --}}
    <section class="py-20 px-6" style="border-top:1px solid var(--color-border)">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center md:text-left">
                <div class="w-9 h-9 rounded-md flex items-center justify-center mb-4" style="background:color-mix(in srgb, var(--color-accent) 10%, transparent)">
                    <svg class="h-4 w-4" style="color:var(--color-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
                <h3 class="text-[14px] font-semibold mb-1" style="color:var(--color-text)">Project Management</h3>
                <p class="text-[12px]" style="color:var(--color-text-muted)">Track projects, tasks, and deadlines in a centralized dashboard with drag-and-drop Kanban.</p>
            </div>
            <div class="text-center md:text-left">
                <div class="w-9 h-9 rounded-md flex items-center justify-center mb-4" style="background:color-mix(in srgb, var(--color-purple) 10%, transparent)">
                    <svg class="h-4 w-4" style="color:var(--color-purple)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                </div>
                <h3 class="text-[14px] font-semibold mb-1" style="color:var(--color-text)">AI-Powered Planning</h3>
                <p class="text-[12px]" style="color:var(--color-text-muted)">Generate implementation tasks from SRS documents automatically using layered AI scaffolding.</p>
            </div>
            <div class="text-center md:text-left">
                <div class="w-9 h-9 rounded-md flex items-center justify-center mb-4" style="background:color-mix(in srgb, var(--color-success) 10%, transparent)">
                    <svg class="h-4 w-4" style="color:var(--color-success)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-[14px] font-semibold mb-1" style="color:var(--color-text)">Team Collaboration</h3>
                <p class="text-[12px]" style="color:var(--color-text-muted)">Invite team members, assign roles with granular permissions, and review work together.</p>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 text-center" style="border-top:1px solid var(--color-border)">
        <p class="text-[11px]" style="font-family:var(--font-mono);color:var(--color-text-faint)">&copy; {{ date('Y') }} GrowDev</p>
    </footer>
</div>
</body>
</html>
