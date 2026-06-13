<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Projects</h2>
            <a href="{{ route('projects.create') }}" class="gd-btn gd-btn-primary">
                <svg class="h-4 w-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Project
            </a>
        </div>
    </x-slot>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap items-end gap-3 mb-6">
        <div>
            <label class="gd-label">Search</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Filter projects..." class="gd-input w-56">
        </div>
        @if($teams->count())
        <div>
            <label class="gd-label">Team</label>
            <select name="team_id" class="gd-select w-40" onchange="this.form.submit()">
                <option value="">All teams</option>
                @foreach($teams as $id => $name)
                    <option value="{{ $id }}" {{ request('team_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="gd-label">Sort</label>
            <select name="sort" class="gd-select w-36" onchange="this.form.submit()">
                <option value="">Newest</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
            </select>
        </div>
        <button type="submit" class="gd-btn gd-btn-secondary">Apply</button>
        @if(request()->anyFilled(['q','team_id','sort']))
            <a href="{{ route('projects.index') }}" class="gd-btn gd-btn-ghost text-[12px]" style="color:var(--color-text-muted)">Clear</a>
        @endif
    </form>

    {{-- Project Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 stagger">
        @forelse($projects as $project)
            <div class="gd-card gd-card-interactive p-5 cursor-pointer" onclick="window.location='{{ route('projects.show', $project) }}'">
                <div class="flex items-start justify-between mb-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="gd-chip text-[10px]">P-{{ $project->id }}</span>
                            @if($project->team)
                                <span class="gd-chip text-[10px]" style="background:color-mix(in srgb, var(--color-purple) 10%, transparent);border-color:color-mix(in srgb, var(--color-purple) 30%, transparent);color:var(--color-purple)">{{ $project->team->name }}</span>
                            @endif
                        </div>
                        <h3 class="text-[15px] font-semibold truncate" style="font-family:var(--font-mono);color:var(--color-text)">{{ $project->name }}</h3>
                    </div>
                    @php
                        $pStatus = match($project->status) {
                            'active' => 'in-progress',
                            'completed' => 'done',
                            'on_hold' => 'todo',
                            default => 'todo'
                        };
                    @endphp
                    <span class="gd-badge gd-badge-{{ $pStatus }} flex-shrink-0">{{ ucfirst($project->status) }}</span>
                </div>
                <p class="text-[13px] mb-4 line-clamp-2" style="color:var(--color-text-muted)">{{ $project->description ?: 'No description' }}</p>
                <div class="flex items-center gap-2 mb-3">
                    <div class="gd-progress flex-1">
                        <div class="gd-progress-bar"
                             style="width:{{ $project->progress ?? 0 }}%;
                             background:{{ ($project->progress ?? 0) >= 70 ? 'linear-gradient(90deg, var(--color-accent), var(--color-success))' : (($project->progress ?? 0) >= 30 ? 'linear-gradient(90deg, var(--color-warning), var(--color-accent))' : 'linear-gradient(90deg, var(--color-danger), var(--color-warning))') }}"></div>
                    </div>
                    <span class="text-[11px] tabular-nums" style="font-family:var(--font-mono);color:var(--color-text-muted)">{{ $project->progress ?? 0 }}%</span>
                </div>
                <div class="flex items-center justify-between text-[11px]" style="color:var(--color-text-faint)">
                    <span style="font-family:var(--font-mono)">{{ $project->updated_at->diffForHumans() }}</span>
                    <span>{{ $project->tasks_count }} tasks</span>
                </div>
            </div>
        @empty
            <div class="col-span-full gd-card p-12 text-center">
                <svg class="mx-auto h-10 w-10 mb-4" style="color:var(--color-text-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                <p class="text-[14px] font-medium mb-2" style="color:var(--color-text)">No projects yet</p>
                <p class="text-[13px] mb-4" style="color:var(--color-text-muted)">Create your first project to start managing tasks and documentation.</p>
                <a href="{{ route('projects.create') }}" class="gd-btn gd-btn-primary">Create Project</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $projects->withQueryString()->links() }}
    </div>
</x-app-layout>
