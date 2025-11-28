<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
            <div>
                <a href="{{ route('projects.edit', $project) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-gray-600 mb-4">{{ $project->description }}</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-bold">Status:</span> {{ ucfirst($project->status) }}
                        </div>
                        <div>
                            <span class="font-bold">Type:</span> {{ ucfirst($project->type) ?? 'Solo' }}
                        </div>
                        @if($project->team)
                            <div>
                                <span class="font-bold">Team:</span>
                                <a href="{{ route('teams.show', $project->team) }}" class="text-indigo-600 hover:underline">{{ $project->team->name }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Tasks</h3>
                            <div class="flex items-center gap-2">
                                <x-list-controls :route="route('projects.show', $project)" :query="request()->q" :sort="request()->sort" :view="request()->view ?? 'list'" :extraFilters="['status' => ['To Do' => 'To Do', 'In Progress' => 'In Progress', 'Review' => 'Review', 'Done' => 'Done'], 'assigned_to' => $members]">
                                    <button type="button" onclick="document.getElementById('createTaskModal').classList.remove('hidden')" class="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Add Task</button>
                                </x-list-controls>
                            </div>
                        </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if($tasks->count())
                                    @foreach($tasks as $task)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $task->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $priorityColors = [
                                                    'Critical' => 'bg-red-100 text-red-800 border-red-200',
                                                    'High' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                    'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'Low' => 'bg-green-100 text-green-800 border-green-200',
                                                ];
                                                $priorityIcons = [
                                                    'Critical' => '🔥',
                                                    'High' => '⚠️',
                                                    'Medium' => '📌',
                                                    'Low' => '📋',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full border {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                                {{ $priorityIcons[$task->priority] ?? '📋' }} {{ $task->priority }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'To Do' => 'bg-gray-100 text-gray-800 border-gray-300',
                                                    'In Progress' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                    'Review' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                    'Done' => 'bg-green-100 text-green-800 border-green-300',
                                                ];
                                                $statusIcons = [
                                                    'To Do' => '📋',
                                                    'In Progress' => '🔄',
                                                    'Review' => '👀',
                                                    'Done' => '✅',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full border {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                                                {{ $statusIcons[$task->status] ?? '📋' }} {{ $task->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($task->assignee)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                                                        {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ $task->assignee->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 italic">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline-flex items-center gap-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="title" value="{{ $task->title }}">
                                                    <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 pr-8">
                                                        <option value="To Do" {{ $task->status == 'To Do' ? 'selected' : '' }}>📋 To Do</option>
                                                        <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>🔄 In Progress</option>
                                                        <option value="Review" {{ $task->status == 'Review' ? 'selected' : '' }}>👀 Review</option>
                                                        <option value="Done" {{ $task->status == 'Done' ? 'selected' : '' }}>✅ Done</option>
                                                    </select>
                                                </form>

                                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete task?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition" title="Delete task">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No tasks for this project yet.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="createTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Task</h3>
                <form action="{{ route('projects.tasks.store', $project) }}" method="POST" class="mt-2 text-left">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                        <input type="text" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Priority</label>
                        <select name="priority" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Assign To</label>
                        <select name="assigned_to" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Unassigned</option>
                            @if($project->team)
                                @foreach($project->team->members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            @else
                                <option value="{{ auth()->id() }}">Me</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
                        <input type="date" name="due_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
