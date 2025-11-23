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
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->priority === 'Critical' ? 'bg-red-100 text-red-800' : ($task->priority === 'High' ? 'bg-orange-100 text-orange-800' : ($task->priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">{{ $task->priority }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->status }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="title" value="{{ $task->title }}">
                                                <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded-md shadow-sm">
                                                    <option value="To Do" {{ $task->status == 'To Do' ? 'selected' : '' }}>To Do</option>
                                                    <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="Review" {{ $task->status == 'Review' ? 'selected' : '' }}>Review</option>
                                                    <option value="Done" {{ $task->status == 'Done' ? 'selected' : '' }}>Done</option>
                                                </select>
                                            </form>

                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Delete task?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
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
