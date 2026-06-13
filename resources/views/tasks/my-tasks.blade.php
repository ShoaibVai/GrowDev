<x-app-layout>
    <x-slot name="header">
        <h2 class="text-[18px] font-semibold" style="color:var(--color-text)">My Tasks</h2>
    </x-slot>

    {{-- Status tabs --}}
    <div class="flex flex-wrap gap-1.5 mb-6">
        <a href="{{ route('tasks.my-tasks') }}" class="gd-btn gd-btn-sm {{ !request('status') ? '' : '' }}"
           style="background:{{ !request('status') ? 'var(--color-accent)' : 'var(--color-surface)' }};color:{{ !request('status') ? '#fff' : 'var(--color-text)' }};border:1px solid {{ request('status') ? 'var(--color-border)' : 'transparent' }}">
            All ({{ $statusCounts->sum() }})
        </a>
        @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $st)
            <a href="{{ route('tasks.my-tasks', ['status' => $st]) }}" class="gd-btn gd-btn-sm"
               style="background:{{ request('status') === $st ? 'var(--color-accent)' : 'var(--color-surface)' }};color:{{ request('status') === $st ? '#fff' : 'var(--color-text)' }};border:1px solid {{ request('status') === $st ? 'transparent' : 'var(--color-border)' }}">
                {{ $st }} ({{ $statusCounts[$st] ?? 0 }})
            </a>
        @endforeach
    </div>

    <div class="gd-card overflow-hidden">
        @if($tasks->count())
            <div class="divide-y" style="border-color:var(--color-border)">
                @foreach($tasks as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="block p-4 hover:bg-gd-surface-3 transition-colors duration-120">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="gd-chip text-[10px]">T-{{ $task->id }}</span>
                                    <span class="text-[14px] font-medium truncate" style="color:var(--color-text)">{{ $task->title }}</span>
                                    @if($task->pendingStatusRequest)
                                        <span class="gd-badge gd-badge-warning text-[9px]">Pending Approval</span>
                                    @endif
                                    @if($task->is_scaffold)
                                        <span class="gd-badge gd-badge-purple text-[9px]">Scaffold</span>
                                    @endif
                                </div>
                                <p class="text-[12px] mt-1" style="color:var(--color-text-muted)">
                                    {{ $task->project->name }}
                                    @if($task->requirement)
                                        &middot; <span style="font-family:var(--font-mono);color:var(--color-accent)">{{ $task->requirement->section_number }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                @php $pr = match($task->priority) { 'Critical'=>'critical', 'High'=>'high', 'Medium'=>'medium', default=>'low' }; @endphp
                                <span class="gd-badge gd-badge-{{ $pr }}">{{ $task->priority }}</span>
                                @php $ts = match($task->status) { 'To Do'=>'todo', 'In Progress'=>'in-progress', 'Review'=>'review', 'Done'=>'done', default=>'todo' }; @endphp
                                <span class="gd-badge gd-badge-{{ $ts }}">{{ $task->status }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 mt-2 text-[12px]">
                            @if($task->due_date)
                                <span style="font-family:var(--font-mono);color:{{ $task->due_date->isPast() && $task->status !== 'Done' ? 'var(--color-danger)' : 'var(--color-text-faint)' }}">
                                    Due {{ $task->due_date->format('M d') }}
                                </span>
                            @endif
                            @if($task->due_at)
                                <span style="font-family:var(--font-mono);color:{{ $task->isOverdue() ? 'var(--color-danger)' : 'var(--color-text-faint)' }}">
                                    Due {{ $task->due_at->format('M d, g:i A') }}
                                </span>
                            @endif
                            <span style="color:var(--color-text-faint)">Timer: {{ $task->timer_state ?? 'idle' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="p-4" style="border-top:1px solid var(--color-border)">
                {{ $tasks->withQueryString()->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-[14px] font-medium mb-2" style="color:var(--color-text)">No tasks found</p>
                <p class="text-[13px] mb-4" style="color:var(--color-text-muted)">
                    @if(request('status')) No tasks with status "{{ request('status') }}" assigned to you.
                    @else You don't have any tasks assigned yet.
                    @endif
                </p>
                <a href="{{ route('projects.index') }}" class="gd-btn gd-btn-primary gd-btn-sm">View Projects</a>
            </div>
        @endif
    </div>
</x-app-layout>
