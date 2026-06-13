<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="gd-chip">P-{{ $project->id }}</span>
                <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">Sprints</h2>
            </div>
            <a href="{{ route('sprints.create', $project) }}" class="gd-btn gd-btn-primary gd-btn-sm">
                <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Sprint
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 stagger">
        @forelse($sprints as $sprint)
            @php $p = $sprint->progress(); @endphp
            <a href="{{ route('sprints.show', [$project, $sprint]) }}" class="gd-card gd-card-interactive p-5 block">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <span class="gd-chip text-[10px] mb-1">SPRINT-{{ $sprint->id }}</span>
                        <p class="text-[15px] font-semibold" style="color:var(--color-text)">{{ $sprint->name }}</p>
                    </div>
                    @php
                        $sStatus = match($sprint->status) {
                            'active' => 'in-progress',
                            'planned' => 'todo',
                            'completed' => 'done',
                            'cancelled' => 'blocked',
                            default => 'todo'
                        };
                    @endphp
                    <span class="gd-badge gd-badge-{{ $sStatus }}">{{ ucfirst($sprint->status) }}</span>
                </div>
                @if($sprint->goal)
                    <p class="text-[12px] mb-3" style="color:var(--color-text-muted)">{{ Str::limit($sprint->goal, 80) }}</p>
                @endif
                <div class="flex items-center justify-between text-[11px] mb-3" style="color:var(--color-text-faint)">
                    <span style="font-family:var(--font-mono)">{{ $sprint->start_date->format('M d') }} &mdash; {{ $sprint->end_date->format('M d') }}</span>
                    <span>{{ $sprint->tasks_count }} tasks</span>
                </div>
                @if($p['total'] > 0)
                    <div class="gd-progress">
                        <div class="gd-progress-bar" style="width:{{ $p['percentage'] }}%;
                            background:{{ $p['percentage']>=70?'linear-gradient(90deg,var(--color-accent),var(--color-success))':($p['percentage']>=30?'linear-gradient(90deg,var(--color-warning),var(--color-accent))':'linear-gradient(90deg,var(--color-danger),var(--color-warning))') }}"></div>
                    </div>
                @endif
            </a>
        @empty
            <div class="col-span-full gd-card p-12 text-center">
                <p class="text-[14px] font-medium mb-2" style="color:var(--color-text)">No sprints yet</p>
                <p class="text-[13px] mb-4" style="color:var(--color-text-muted)">Plan your work iterations with sprints.</p>
                <a href="{{ route('sprints.create', $project) }}" class="gd-btn gd-btn-primary">Create Sprint</a>
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $sprints->withQueryString()->links() }}</div>
</x-app-layout>
