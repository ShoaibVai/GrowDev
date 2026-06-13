<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[22px] font-semibold" style="font-family:var(--font-sans);color:var(--color-text)">
                    Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, <span style="color:var(--color-text)">{{ Auth::user()->name }}</span>.
                </p>
                @if($activeSprints->first())
                    <p class="mt-1 text-[13px]" style="font-family:var(--font-mono);color:var(--color-text-muted)">
                        {{ $activeSprints->first()->name }} &middot; {{ $activeSprints->first()->end_date->diffInDays(now()) }}d remaining
                    </p>
                @endif
            </div>
            <a href="{{ route('projects.create') }}" class="gd-btn gd-btn-primary">
                <svg class="h-4 w-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Project
            </a>
        </div>
    </x-slot>

    {{-- ===== STATS ROW ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 stagger">
        <div class="gd-card p-4 flex flex-col">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider" style="color:var(--color-text-faint)">Total Projects</p>
                    <p class="text-[36px] font-bold mt-1 tracking-tight" style="font-family:var(--font-mono);color:var(--color-text)">{{ $totalProjects }}</p>
                </div>
                <div class="w-9 h-9 rounded-md flex items-center justify-center flex-shrink-0" style="background:color-mix(in srgb, var(--color-accent) 12%, transparent)">
                    <svg class="h-4 w-4" style="color:var(--color-accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
            </div>
            <a href="{{ route('projects.index') }}" class="mt-2 text-[12px] hover:underline" style="color:var(--color-accent)">View all</a>
        </div>

        <div class="gd-card p-4 flex flex-col">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider" style="color:var(--color-text-faint)">Active</p>
                    <p class="text-[36px] font-bold mt-1 tracking-tight" style="font-family:var(--font-mono);color:var(--color-success)">{{ $activeProjects }}</p>
                </div>
                <div class="w-9 h-9 rounded-md flex items-center justify-center flex-shrink-0" style="background:color-mix(in srgb, var(--color-success) 12%, transparent)">
                    <svg class="h-4 w-4" style="color:var(--color-success)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <span class="mt-2 text-[12px]" style="color:var(--color-text-muted)">{{ $completedProjects }} completed</span>
        </div>

        <div class="gd-card p-4 flex flex-col">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider" style="color:var(--color-text-faint)">Open Tasks</p>
                    <p class="text-[36px] font-bold mt-1 tracking-tight" style="font-family:var(--font-mono);color:var(--color-warning)">{{ $openTasksCount }}</p>
                </div>
                <div class="w-9 h-9 rounded-md flex items-center justify-center flex-shrink-0" style="background:color-mix(in srgb, var(--color-warning) 12%, transparent)">
                    <svg class="h-4 w-4" style="color:var(--color-warning)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>
            <a href="{{ route('tasks.my-tasks') }}" class="mt-2 text-[12px] hover:underline" style="color:var(--color-accent)">View tasks</a>
        </div>

        <div class="gd-card p-4 flex flex-col">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider" style="color:var(--color-text-faint)">Teams</p>
                    <p class="text-[36px] font-bold mt-1 tracking-tight" style="font-family:var(--font-mono);color:var(--color-purple)">{{ $teamsCount }}</p>
                </div>
                <div class="w-9 h-9 rounded-md flex items-center justify-center flex-shrink-0" style="background:color-mix(in srgb, var(--color-purple) 12%, transparent)">
                    <svg class="h-4 w-4" style="color:var(--color-purple)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <a href="{{ route('teams.index') }}" class="mt-2 text-[12px] hover:underline" style="color:var(--color-accent)">View teams</a>
        </div>
    </div>

    {{-- ===== PENDING INVITATIONS ===== --}}
    @if($pendingInvitations->count() > 0)
        <div class="mb-8 gd-card p-4" style="border-left:3px solid var(--color-accent)">
            <p class="text-[12px] font-semibold uppercase tracking-wider mb-3" style="color:var(--color-text-muted)">Pending Invitations</p>
            <div class="space-y-2">
                @foreach($pendingInvitations as $inv)
                    <div class="flex items-center justify-between text-[13px]">
                        <span>
                            <span style="color:var(--color-text)">{{ $inv->team->name }}</span>
                            <span class="text-[11px] ml-2" style="color:var(--color-text-muted);font-family:var(--font-mono)">invited by {{ $inv->inviter->name }}</span>
                        </span>
                        <div class="flex gap-2">
                            <a href="{{ route('invitations.accept', $inv->token) }}" class="gd-btn gd-btn-primary gd-btn-sm">Accept</a>
                            <a href="{{ route('invitations.decline', $inv->token) }}" class="gd-btn gd-btn-secondary gd-btn-sm">Decline</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ===== MAIN GRID ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 stagger">

        {{-- LEFT COLUMN (66%) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Active Sprints --}}
            @if($activeSprints->count() > 0)
                <div class="gd-card p-0 overflow-hidden">
                    <div class="px-5 py-4" style="border-bottom:1px solid var(--color-border)">
                        <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Active Sprints</p>
                    </div>
                    <div class="divide-y" style="border-color:var(--color-border)">
                        @foreach($activeSprints as $sprint)
                            @php $p = $sprint->progress(); @endphp
                            <a href="{{ route('sprints.show', [$sprint->project, $sprint]) }}" class="block px-5 py-4 hover:bg-gd-surface-3 transition-colors duration-120">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="gd-chip text-[10px] mr-2">SPRINT-{{ $sprint->id }}</span>
                                        <span class="text-[14px] font-medium" style="color:var(--color-text)">{{ $sprint->name }}</span>
                                    </div>
                                    <span class="gd-badge gd-badge-in-progress">Active</span>
                                </div>
                                <p class="text-[12px] mb-2" style="color:var(--color-text-faint)">
                                    {{ $sprint->project->name }}
                                    &middot; {{ $sprint->start_date->format('M d') }} &mdash; {{ $sprint->end_date->format('M d') }}
                                </p>
                                @if($p['total'] > 0)
                                    <div class="flex items-center gap-3">
                                        <div class="gd-progress flex-1">
                                            <div class="gd-progress-bar"
                                                 style="width:{{ $p['percentage'] }}%;
                                                 background:{{ $p['percentage'] >= 70 ? 'linear-gradient(90deg, var(--color-accent), var(--color-success))' : ($p['percentage'] >= 30 ? 'linear-gradient(90deg, var(--color-warning), var(--color-accent))' : 'linear-gradient(90deg, var(--color-danger), var(--color-warning))') }}"></div>
                                        </div>
                                        <span class="text-[12px] font-semibold tabular-nums" style="font-family:var(--font-mono);color:var(--color-text-muted)">{{ $p['done'] }}/{{ $p['total'] }}</span>
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Recent Projects --}}
            <div class="gd-card p-0 overflow-hidden">
                <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid var(--color-border)">
                    <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Recent Projects</p>
                    <a href="{{ route('projects.index') }}" class="text-[12px] hover:underline" style="color:var(--color-accent)">View all</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-px" style="background:var(--color-border)">
                    @forelse($projects as $project)
                        <div class="gd-card-interactive p-4 cursor-pointer" style="background:var(--color-surface)" onclick="window.location='{{ route('projects.show', $project) }}'">
                            <div class="flex items-start justify-between mb-2">
                                <p class="text-[15px] font-semibold truncate" style="font-family:var(--font-mono);color:var(--color-text)">{{ $project->name }}</p>
                                @php
                                    $statusBadge = match($project->status) {
                                        'active' => 'in-progress',
                                        'completed' => 'done',
                                        'on_hold' => 'todo',
                                        default => 'todo'
                                    };
                                @endphp
                                <span class="gd-badge gd-badge-{{ $statusBadge }}">{{ ucfirst($project->status) }}</span>
                            </div>
                            <p class="text-[13px] mb-3 line-clamp-2" style="color:var(--color-text-muted)">{{ $project->description ?: 'No description' }}</p>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="gd-progress flex-1">
                                    <div class="gd-progress-bar"
                                         style="width:{{ $project->progress ?? 0 }}%;
                                         background:{{ ($project->progress ?? 0) >= 70 ? 'linear-gradient(90deg, var(--color-accent), var(--color-success))' : (($project->progress ?? 0) >= 30 ? 'linear-gradient(90deg, var(--color-warning), var(--color-accent))' : 'linear-gradient(90deg, var(--color-danger), var(--color-warning))') }}"></div>
                                </div>
                                <span class="text-[11px] tabular-nums" style="font-family:var(--font-mono);color:var(--color-text-muted)">{{ $project->progress ?? 0 }}%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[11px]" style="color:var(--color-text-faint);font-family:var(--font-mono)">{{ $project->updated_at->diffForHumans() }}</span>
                                @if($project->team)
                                    <span class="gd-chip text-[10px]">{{ $project->team->name }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 p-8 text-center" style="background:var(--color-surface)">
                            <p class="text-[13px] mb-3" style="color:var(--color-text-muted)">No projects yet</p>
                            <a href="{{ route('projects.create') }}" class="gd-btn gd-btn-primary">Create your first project</a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- My Tasks --}}
            <div class="gd-card p-0 overflow-hidden">
                <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid var(--color-border)">
                    <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">My Tasks</p>
                    <a href="{{ route('tasks.my-tasks') }}" class="text-[12px] hover:underline" style="color:var(--color-accent)">View all</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-[13px]">
                        <thead>
                            <tr style="border-bottom:1px solid var(--color-border)">
                                <th class="text-left px-5 py-2.5 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--color-text-faint)">Task</th>
                                <th class="text-left px-2 py-2.5 text-[11px] font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:var(--color-text-faint)">Project</th>
                                <th class="text-left px-2 py-2.5 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--color-text-faint)">Status</th>
                                <th class="text-right px-5 py-2.5 text-[11px] font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:var(--color-text-faint)">Due</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:var(--color-border)">
                            @forelse($tasksAssigned as $task)
                                <tr class="hover:bg-gd-surface-3 transition-colors duration-120 cursor-pointer" onclick="window.location='{{ route('tasks.show', $task) }}'">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="gd-chip text-[10px] hidden sm:inline-flex">T-{{ $task->id }}</span>
                                            <span class="font-medium truncate block max-w-[200px]" style="color:var(--color-text)">{{ Str::limit($task->title, 40) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-3 hidden sm:table-cell" style="color:var(--color-text-muted)">{{ Str::limit($task->project->name ?? 'N/A', 20) }}</td>
                                    <td class="px-2 py-3">
                                        @php
                                            $taskStatus = match($task->status) {
                                                'To Do' => 'todo',
                                                'In Progress' => 'in-progress',
                                                'Review' => 'review',
                                                'Done' => 'done',
                                                default => 'todo'
                                            };
                                        @endphp
                                        <span class="gd-badge gd-badge-{{ $taskStatus }}">{{ $task->status }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right hidden sm:table-cell">
                                        <span class="text-[12px] tabular-nums" style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $task->due_date ? $task->due_date->format('M d') : '—' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-6 text-center text-[13px]" style="color:var(--color-text-muted)">No tasks assigned to you</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN (34%) --}}
        <div class="space-y-6">

            {{-- Upcoming Deadlines --}}
            <div class="gd-card p-0 overflow-hidden">
                <div class="px-5 py-4" style="border-bottom:1px solid var(--color-border)">
                    <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Upcoming Deadlines</p>
                </div>
                <div class="p-5">
                    @forelse($upcomingTasks as $task)
                        @php
                            $daysLeft = $task->due_date->diffInDays(now());
                            $urgencyDot = $daysLeft < 2 ? 'var(--color-danger)' : ($daysLeft < 7 ? 'var(--color-orange)' : 'var(--color-text-faint)');
                        @endphp
                        <div class="flex items-start gap-3 py-2" @if(!$loop->last) style="border-bottom:1px solid var(--color-border)" @endif>
                            <span class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" style="background:{{ $urgencyDot }}"></span>
                            <div class="min-w-0">
                                <a href="{{ route('tasks.show', $task) }}" class="text-[13px] font-medium hover:underline block truncate" style="color:var(--color-text)">{{ $task->title }}</a>
                                <p class="text-[12px] mt-0.5" style="color:var(--color-text-faint)">
                                    <span style="font-family:var(--font-mono)">{{ $task->due_date->format('M d') }}</span>
                                    &middot; {{ $task->project->name ?? '' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[13px] text-center py-4" style="color:var(--color-text-muted)">No upcoming deadlines this week</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent SRS Documents --}}
            <div class="gd-card p-0 overflow-hidden">
                <div class="px-5 py-4" style="border-bottom:1px solid var(--color-border)">
                    <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">Recent Documents</p>
                </div>
                <div class="p-5">
                    @forelse($recentSrs as $doc)
                        <div class="flex items-start gap-3 py-2" @if(!$loop->last) style="border-bottom:1px solid var(--color-border)" @endif>
                            <svg class="h-4 w-4 flex-shrink-0 mt-0.5" style="color:var(--color-text-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <div class="min-w-0">
                                <a href="{{ route('documentation.srs.edit', $doc) }}" class="text-[13px] font-medium hover:underline block truncate" style="color:var(--color-text)">{{ $doc->title }}</a>
                                <p class="text-[11px] mt-0.5" style="font-family:var(--font-mono);color:var(--color-text-faint)">{{ $doc->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[13px] text-center py-4" style="color:var(--color-text-muted)">No SRS documents yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
