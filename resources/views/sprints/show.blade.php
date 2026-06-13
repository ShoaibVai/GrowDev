<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="gd-chip">SPRINT-{{ $sprint->id }}</span>
                <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">{{ $sprint->name }}</h2>
                @php $sStat = match($sprint->status) { 'active'=>'in-progress', 'planned'=>'todo', 'completed'=>'done', 'cancelled'=>'blocked', default=>'todo' }; @endphp
                <span class="gd-badge gd-badge-{{ $sStat }}">{{ ucfirst($sprint->status) }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('sprints.edit', [$project, $sprint]) }}" class="gd-btn gd-btn-secondary gd-btn-sm">Edit</a>
                @if($sprint->status === 'planned')
                    <form action="{{ route('sprints.start', [$project, $sprint]) }}" method="POST" class="inline">
                        @csrf <button class="gd-btn gd-btn-primary gd-btn-sm">Start Sprint</button>
                    </form>
                @endif
                @if($sprint->status === 'active')
                    <form action="{{ route('sprints.complete', [$project, $sprint]) }}" method="POST" class="inline">
                        @csrf <button class="gd-btn gd-btn-sm" style="background:var(--color-success);color:#fff">Complete</button>
                    </form>
                    <form action="{{ route('sprints.cancel', [$project, $sprint]) }}" method="POST" class="inline">
                        @csrf <button class="gd-btn gd-btn-danger gd-btn-sm">Cancel</button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Sprint info --}}
        <div class="gd-card p-5">
            <div class="flex flex-wrap items-center gap-6 text-[13px]">
                <div>
                    <span class="gd-label">Goal</span>
                    <span style="color:var(--color-text)">{{ $sprint->goal ?: 'No goal set' }}</span>
                </div>
                <div>
                    <span class="gd-label">Timeline</span>
                    <span style="font-family:var(--font-mono);color:var(--color-text)">{{ $sprint->start_date->format('M d, Y') }} &mdash; {{ $sprint->end_date->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="gd-label">Project</span>
                    <a href="{{ route('projects.show', $project) }}" style="color:var(--color-accent)">{{ $project->name }}</a>
                </div>
            </div>
            @if($progress['total'] > 0)
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[12px] font-medium" style="color:var(--color-text)">Progress</span>
                        <span class="text-[11px]" style="font-family:var(--font-mono);color:var(--color-text-muted)">{{ $progress['done'] }}/{{ $progress['total'] }} ({{ $progress['percentage'] }}%)</span>
                    </div>
                    <div class="gd-progress">
                        <div class="gd-progress-bar" style="width:{{ $progress['percentage'] }}%;
                            background:{{ $progress['percentage']>=70?'linear-gradient(90deg,var(--color-accent),var(--color-success))':($progress['percentage']>=30?'linear-gradient(90deg,var(--color-warning),var(--color-accent))':'linear-gradient(90deg,var(--color-danger),var(--color-warning))') }}"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Tasks by status --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $status)
                @php $ts = match($status) { 'To Do'=>'todo', 'In Progress'=>'in-progress', 'Review'=>'review', 'Done'=>'done', default=>'todo' }; @endphp
                <div class="gd-card p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="gd-badge gd-badge-{{ $ts }}">{{ $status }}</span>
                        <span class="text-[11px] font-semibold tabular-nums" style="font-family:var(--font-mono);color:var(--color-text-muted)">{{ $tasksByStatus->get($status)?->count() ?? 0 }}</span>
                    </div>
                    <div class="space-y-1.5 max-h-60 overflow-y-auto">
                        @foreach($tasksByStatus->get($status, collect()) as $task)
                            <a href="{{ route('tasks.show', $task) }}" class="flex items-center gap-2 p-2 rounded hover:bg-gd-surface-3 transition-colors duration-120">
                                <span class="gd-chip text-[9px]">T-{{ $task->id }}</span>
                                <span class="text-[12px] truncate" style="color:var(--color-text)">{{ $task->title }}</span>
                                @if($task->assignee)
                                    <span class="gd-avatar gd-avatar-sm ml-auto flex-shrink-0" style="font-size:9px">{{ substr($task->assignee->name, 0, 1) }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
