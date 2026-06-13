<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <span class="gd-chip">P-{{ $project->id }}</span>
                <h2 class="text-[18px] font-semibold truncate" style="color:var(--color-text)">{{ $project->name }}</h2>
                <span class="text-[12px] hidden sm:inline" style="color:var(--color-text-muted)">Kanban</span>
            </div>
            <div class="flex items-center gap-3">
                <select x-data @change="window.location.href='{{ route('projects.board', $project) }}'+($event.target.value?'?sprint='+$event.target.value:'')"
                        class="gd-select h-7 text-[12px] w-40">
                    <option value="">All Sprints</option>
                    @foreach($sprints as $s)
                        <option value="{{ $s->id }}" {{ request('sprint') == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ ucfirst($s->status) }})</option>
                    @endforeach
                    <option value="backlog" {{ request('sprint') === 'backlog' ? 'selected' : '' }}>Backlog</option>
                </select>
                <a href="{{ route('projects.show', $project) }}" class="gd-btn gd-btn-secondary gd-btn-sm">List View</a>
            </div>
        </div>
    </x-slot>

    <div id="kanban-board" class="flex gap-3 overflow-x-auto pb-4 h-[calc(100vh-180px)] -mx-1 px-1">
        @php $kanbanStatuses = ['To Do', 'In Progress', 'Review', 'Done']; @endphp
        @foreach($kanbanStatuses as $status)
            @php
                $columnTasks = $tasks->get($status, collect());
                $statusKey = match($status) {
                    'To Do' => 'todo',
                    'In Progress' => 'in-progress',
                    'Review' => 'review',
                    'Done' => 'done',
                    default => 'todo'
                };
            @endphp
            <div class="kanban-column flex-shrink-0 w-[280px] flex flex-col rounded-lg" style="background:var(--color-surface);border:1px solid var(--color-border)">
                <div class="sticky top-0 z-10 px-4 py-3 flex items-center justify-between rounded-t-lg" style="background:var(--color-surface);border-bottom:1px solid var(--color-border)">
                    <div class="flex items-center gap-2">
                        <span class="gd-badge gd-badge-{{ $statusKey }}" style="font-size:11px;padding:3px 10px">{{ $status }}</span>
                    </div>
                    <span class="text-[11px] font-semibold w-6 h-6 rounded-full flex items-center justify-center" style="background:var(--color-border);color:var(--color-text-muted);font-family:var(--font-mono)">{{ $columnTasks->count() }}</span>
                </div>
                <div class="kanban-dropzone flex-1 overflow-y-auto p-3 space-y-2 min-h-[240px]" data-status="{{ $status }}"
                     @dragover.prevent="" @drop.prevent="">
                    @foreach($columnTasks as $task)
                        <div class="kanban-card gd-card p-3 cursor-grab active:cursor-grabbing"
                             data-task-id="{{ $task->id }}"
                             draggable="true">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 mb-1.5 flex-wrap">
                                        <span class="gd-chip text-[9px]">T-{{ $task->id }}</span>
                                        @if($task->is_scaffold)
                                            <span class="gd-badge gd-badge-purple text-[9px]">Scaffold</span>
                                        @elseif($task->scaffold_task_id)
                                            <span class="text-[10px]" style="font-family:var(--font-mono);color:var(--color-text-faint)">SF#{{ $task->scaffold_task_id }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('tasks.show', $task) }}" class="task-title text-[13px] font-medium hover:underline line-clamp-2 block" style="color:var(--color-text)">{{ $task->title }}</a>
                                </div>
                                <span class="drag-handle cursor-grab flex-shrink-0 mt-1" style="color:var(--color-text-faint)">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-2.5">
                                <div class="flex items-center gap-1.5">
                                    @if($task->assignee)
                                        <span class="gd-avatar gd-avatar-sm" style="font-size:10px" title="{{ $task->assignee->name }}">{{ substr($task->assignee->name, 0, 1) }}</span>
                                    @else
                                        <span class="gd-avatar gd-avatar-sm" style="background:var(--color-text-faint);font-size:10px">?</span>
                                    @endif
                                    @php
                                        $prioColor = match($task->priority) {
                                            'Critical' => 'var(--color-danger)',
                                            'High' => 'var(--color-orange)',
                                            'Medium' => 'var(--color-warning)',
                                            default => 'var(--color-text-faint)'
                                        };
                                    @endphp
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $prioColor }}" title="{{ $task->priority }}"></span>
                                </div>
                                @if($task->due_date)
                                    <span class="text-[10px] tabular-nums" style="font-family:var(--font-mono);color:{{ $task->isOverdue() ? 'var(--color-danger)' : 'var(--color-text-faint)' }}">{{ $task->due_date->format('M d') }}</span>
                                @endif
                            </div>
                            @if($task->timer_state && $task->timer_state !== 'idle')
                                <div class="mt-2 pt-2 flex items-center gap-1.5" style="border-top:1px solid var(--color-border)">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:var(--color-accent);animation:gd-spin 2s linear infinite"></span>
                                    <span class="text-[10px]" style="color:var(--color-accent)">{{ ucfirst($task->timer_state) }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="kanban-empty text-center py-8" style="{{ $columnTasks->count() > 0 ? 'display:none' : '' }}">
                        <p class="text-[12px]" style="color:var(--color-text-faint)">Drop tasks here</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.KanbanBoard) {
            window.KanbanBoard.init('#kanban-board', {
                channel: 'project.{{ $project->id }}',
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
