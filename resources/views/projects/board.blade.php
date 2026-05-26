<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $project->name }} — Kanban Board
                </h2>
                <p class="text-sm text-gray-500">Drag and drop tasks to update status.</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Sprint Filter -->
                <select x-data @change="window.location.href = '{{ route('projects.board', $project) }}' + ($event.target.value ? '?sprint=' + $event.target.value : '')"
                        class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Sprints</option>
                    @foreach($sprints as $s)
                        <option value="{{ $s->id }}" {{ request('sprint') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }} ({{ ucfirst($s->status) }})
                        </option>
                    @endforeach
                    <option value="backlog" {{ request('sprint') === 'backlog' ? 'selected' : '' }}>Backlog (No Sprint)</option>
                </select>

                <a href="{{ route('projects.show', $project) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Back to Project</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
            @endif

            <div id="kanban-board" class="grid grid-cols-4 gap-4">
                @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $status)
                    <div class="kanban-column bg-gray-50 rounded-lg border border-gray-200 flex flex-col">
                        <!-- Column Header -->
                        <div class="px-4 py-3 border-b border-gray-200 bg-white rounded-t-lg">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ $status }}</h3>
                                <span class="text-xs text-gray-400 px-2 py-0.5 bg-gray-100 rounded-full">
                                    {{ $tasks->get($status, collect())->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- Drop Zone -->
                        <div class="kanban-dropzone flex-1 p-3 space-y-2 min-h-[300px]"
                             data-status="{{ $status }}">
                            @foreach($tasks->get($status, collect()) as $task)
                                <div class="kanban-card bg-white rounded-lg border border-gray-200 p-3 hover:shadow-md transition-shadow cursor-grab active:cursor-grabbing"
                                     data-task-id="{{ $task->id }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('tasks.show', $task) }}"
                                               class="task-title text-sm font-medium text-gray-900 hover:text-indigo-600 block truncate">
                                                {{ $task->title }}
                                            </a>
                                            <p class="task-assignee text-xs text-gray-500 mt-1">
                                                {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-1 ml-2">
                                            @if($task->priority === 'Critical')
                                                <span class="w-2 h-2 rounded-full bg-red-500" title="Critical"></span>
                                            @elseif($task->priority === 'High')
                                                <span class="w-2 h-2 rounded-full bg-yellow-500" title="High"></span>
                                            @elseif($task->priority === 'Medium')
                                                <span class="w-2 h-2 rounded-full bg-blue-500" title="Medium"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-gray-300" title="Low"></span>
                                            @endif
                                            <span class="drag-handle cursor-grab text-gray-300 hover:text-gray-500">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>
                                            </span>
                                        </div>
                                    </div>
                                    @if($task->due_date)
                                        <p class="text-xs mt-2 {{ $task->isOverdue() ? 'text-red-500' : 'text-gray-400' }}">
                                            {{ $task->due_date->format('M d') }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.KanbanBoard) {
            window.KanbanBoard.init('#kanban-board', {
                channel: 'project.{{ $project->id }}',
            });
        } else {
            console.warn('KanbanBoard module not loaded');
        }
    });
    </script>
    @endpush
</x-app-layout>
