<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Tasks') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Status Filter Tabs -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('tasks.my-tasks') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All ({{ $statusCounts->sum() }})
                    </a>
                    @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $status)
                        <a href="{{ route('tasks.my-tasks', ['status' => $status]) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === $status ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $status }} ({{ $statusCounts[$status] ?? 0 }})
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Tasks List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($tasks->count())
                    <div class="divide-y divide-gray-200">
                        @foreach($tasks as $task)
                            <a href="{{ route('tasks.show', $task) }}" class="block hover:bg-gray-50 transition">
                                <div class="p-6">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $task->title }}</h3>
                                                @if($task->pendingStatusRequest)
                                                    <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">
                                                        ⏳ Pending Approval
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Project: {{ $task->project->name }}
                                                @if($task->requirement)
                                                    · Requirement: {{ $task->requirement->section_number }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            @php
                                                $priorityColors = [
                                                    'Critical' => 'bg-red-100 text-red-800',
                                                    'High' => 'bg-orange-100 text-orange-800',
                                                    'Medium' => 'bg-yellow-100 text-yellow-800',
                                                    'Low' => 'bg-green-100 text-green-800',
                                                ];
                                                $statusColors = [
                                                    'To Do' => 'bg-gray-100 text-gray-800',
                                                    'In Progress' => 'bg-blue-100 text-blue-800',
                                                    'Review' => 'bg-yellow-100 text-yellow-800',
                                                    'Done' => 'bg-green-100 text-green-800',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$task->priority] ?? 'bg-gray-100' }}">
                                                {{ $task->priority }}
                                            </span>
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusColors[$task->status] ?? 'bg-gray-100' }}">
                                                {{ $task->status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center gap-4 text-sm text-gray-500">
                                        @if($task->due_date)
                                            <span class="{{ $task->due_date->isPast() && $task->status !== 'Done' ? 'text-red-600 font-medium' : '' }}">
                                                📅 Due {{ $task->due_date->format('M d, Y') }}
                                                @if($task->due_date->isPast() && $task->status !== 'Done')
                                                    (Overdue)
                                                @elseif($task->due_date->isToday())
                                                    (Today)
                                                @endif
                                            </span>
                                        @endif
                                        <span>👤 Owner: {{ $task->project->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t">
                        {{ $tasks->withQueryString()->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📋</div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No tasks found</h3>
                        <p class="text-gray-500">
                            @if(request('status'))
                                No tasks with status "{{ request('status') }}" assigned to you.
                            @else
                                You don't have any tasks assigned to you yet.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
