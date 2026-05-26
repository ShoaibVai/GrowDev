<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $sprint->name }}
                </h2>
                <p class="text-sm text-gray-500">{{ $project->name }} — {{ $sprint->start_date->format('M d, Y') }} to {{ $sprint->end_date->format('M d, Y') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 rounded-full text-xs font-medium
                    @if($sprint->status === 'active') bg-green-100 text-green-800
                    @elseif($sprint->status === 'planned') bg-blue-100 text-blue-800
                    @elseif($sprint->status === 'completed') bg-gray-100 text-gray-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($sprint->status) }}
                </span>
                @if($sprint->status === 'planned')
                    <form action="{{ route('sprints.start', [$project, $sprint]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Start Sprint</button>
                    </form>
                @endif
                @if($sprint->status === 'active')
                    <form action="{{ route('sprints.complete', [$project, $sprint]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700" onclick="return confirm('Complete this sprint? Incomplete tasks will move back to backlog.')">Complete Sprint</button>
                    </form>
                @endif
                @if(in_array($sprint->status, ['planned', 'active']))
                    <form action="{{ route('sprints.cancel', [$project, $sprint]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white text-sm rounded-md hover:bg-red-700" onclick="return confirm('Cancel this sprint? All tasks will move back to backlog.')">Cancel</button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
            @endif

            <!-- Sprint Goal -->
            @if($sprint->goal)
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                    <p class="text-sm font-medium text-indigo-800">Sprint Goal</p>
                    <p class="text-sm text-indigo-700 mt-1">{{ $sprint->goal }}</p>
                </div>
            @endif

            <!-- Progress Bar -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress</span>
                    <span class="text-sm text-gray-500">{{ $progress['done'] }}/{{ $progress['total'] }} tasks done ({{ $progress['percentage'] }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress['percentage'] }}%"></div>
                </div>
            </div>

            <!-- Kanban-style task columns -->
            <div class="grid grid-cols-4 gap-4">
                @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $status)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">{{ $status }}</h3>
                        <div class="space-y-2 min-h-[200px]">
                            @forelse(($tasksByStatus->get($status, collect())) as $task)
                                <a href="{{ route('tasks.show', $task) }}" class="block p-3 bg-white rounded border border-gray-200 hover:shadow-md transition-shadow">
                                    <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-gray-500">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                            @if($task->priority === 'Critical') bg-red-100 text-red-800
                                            @elseif($task->priority === 'High') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $task->priority }}
                                        </span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm text-gray-400 text-center py-8">No tasks</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
