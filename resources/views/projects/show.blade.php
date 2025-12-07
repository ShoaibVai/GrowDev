<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
            <div>
                <a href="{{ route('projects.board', $project) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">Board</a>
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

            <!-- Requirements Checklist -->
            @if($srsDocument)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">📋 Requirements Checklist</h3>
                        <a href="{{ route('documentation.srs.edit', $srsDocument) }}" class="text-sm text-indigo-600 hover:underline">Edit SRS →</a>
                    </div>

                    @php
                        $statusColors = [
                            'listed' => 'bg-gray-100 text-gray-700 border-gray-300',
                            'work_in_progress' => 'bg-blue-100 text-blue-700 border-blue-300',
                            'completed' => 'bg-green-100 text-green-700 border-green-300',
                            'compromised' => 'bg-red-100 text-red-700 border-red-300',
                            'under_maintenance' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                        ];
                        $statusIcons = [
                            'listed' => '📝',
                            'work_in_progress' => '🔄',
                            'completed' => '✅',
                            'compromised' => '⚠️',
                            'under_maintenance' => '🔧',
                        ];
                        $statusLabels = [
                            'listed' => 'Listed',
                            'work_in_progress' => 'Work in Progress',
                            'completed' => 'Completed',
                            'compromised' => 'Compromised',
                            'under_maintenance' => 'Under Maintenance',
                        ];
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Functional Requirements -->
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                                Functional Requirements ({{ $allFunctionalReqs->count() }})
                            </h4>
                            @if($allFunctionalReqs->count())
                                <div class="space-y-2 max-h-96 overflow-y-auto pr-2">
                                    @foreach($allFunctionalReqs as $req)
                                        <div class="border rounded-lg p-3 hover:shadow-sm transition {{ ($req->implementation_status ?? 'listed') === 'completed' ? 'bg-green-50' : 'bg-white' }}">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-mono text-gray-500">{{ $req->section_number }}</span>
                                                        <span class="font-medium text-sm text-gray-900 truncate">{{ $req->title }}</span>
                                                    </div>
                                                    <div class="mt-1">
                                                        <span class="px-2 py-0.5 text-xs rounded-full border {{ $statusColors[$req->implementation_status ?? 'listed'] }}">
                                                            {{ $statusIcons[$req->implementation_status ?? 'listed'] }} {{ $statusLabels[$req->implementation_status ?? 'listed'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @can('update', $project)
                                                <form action="{{ route('projects.requirements.update', [$project, 'functional', $req->id]) }}" method="POST" class="flex-shrink-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="implementation_status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded py-1 pl-2 pr-6">
                                                        @foreach($statusLabels as $val => $label)
                                                            <option value="{{ $val }}" {{ ($req->implementation_status ?? 'listed') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No functional requirements defined.</p>
                            @endif
                        </div>

                        <!-- Non-Functional Requirements -->
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                                Non-Functional Requirements ({{ $allNonFunctionalReqs->count() }})
                            </h4>
                            @if($allNonFunctionalReqs->count())
                                <div class="space-y-2 max-h-96 overflow-y-auto pr-2">
                                    @foreach($allNonFunctionalReqs as $req)
                                        <div class="border rounded-lg p-3 hover:shadow-sm transition {{ ($req->implementation_status ?? 'listed') === 'completed' ? 'bg-green-50' : 'bg-white' }}">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-mono text-gray-500">{{ $req->section_number }}</span>
                                                        <span class="font-medium text-sm text-gray-900 truncate">{{ $req->title }}</span>
                                                    </div>
                                                    <div class="mt-1 flex items-center gap-2">
                                                        <span class="px-2 py-0.5 text-xs rounded-full border {{ $statusColors[$req->implementation_status ?? 'listed'] }}">
                                                            {{ $statusIcons[$req->implementation_status ?? 'listed'] }} {{ $statusLabels[$req->implementation_status ?? 'listed'] }}
                                                        </span>
                                                        @if($req->category)
                                                            <span class="text-xs text-gray-500">{{ ucfirst($req->category) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @can('update', $project)
                                                <form action="{{ route('projects.requirements.update', [$project, 'non_functional', $req->id]) }}" method="POST" class="flex-shrink-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="implementation_status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded py-1 pl-2 pr-6">
                                                        @foreach($statusLabels as $val => $label)
                                                            <option value="{{ $val }}" {{ ($req->implementation_status ?? 'listed') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No non-functional requirements defined.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Progress Summary -->
                    @php
                        $totalReqs = $allFunctionalReqs->count() + $allNonFunctionalReqs->count();
                        $completedReqs = $allFunctionalReqs->where('implementation_status', 'completed')->count() 
                                       + $allNonFunctionalReqs->where('implementation_status', 'completed')->count();
                        $inProgressReqs = $allFunctionalReqs->where('implementation_status', 'work_in_progress')->count() 
                                        + $allNonFunctionalReqs->where('implementation_status', 'work_in_progress')->count();
                        $progressPercent = $totalReqs > 0 ? round(($completedReqs / $totalReqs) * 100) : 0;
                    @endphp
                    @if($totalReqs > 0)
                    <div class="mt-6 pt-4 border-t">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Implementation Progress</span>
                            <span class="text-sm text-gray-500">{{ $completedReqs }}/{{ $totalReqs }} completed ({{ $progressPercent }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-green-500 h-2.5 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <div class="flex gap-4 mt-2 text-xs text-gray-500">
                            <span>✅ {{ $completedReqs }} Completed</span>
                            <span>🔄 {{ $inProgressReqs }} In Progress</span>
                            <span>📝 {{ $totalReqs - $completedReqs - $inProgressReqs }} Other</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-yellow-800 mb-2">📋 No SRS Document</h3>
                    <p class="text-sm text-yellow-700 mb-3">Create an SRS document to track requirements for this project.</p>
                    <a href="{{ route('documentation.srs.create', ['project_id' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                        Create SRS Document
                    </a>
                </div>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Members Summary</h3>
                    </div>
                    <ul id="memberSummaryList" class="space-y-2 text-sm text-gray-600">
                        <li>Loading members...</li>
                    </ul>
                </div>
            </div>

            <!-- AI Task Generation Card -->
            @can('update', $project)
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold mb-1">🤖 AI Task Generation</h3>
                            <p class="text-sm opacity-90">Automatically generate tasks from your project requirements using AI</p>
                        </div>
                        <a href="{{ route('projects.ai-tasks.preview', $project) }}" 
                           class="px-4 py-2 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition shadow">
                            Generate Tasks
                        </a>
                    </div>
                </div>
            </div>
            @endcan

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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requirement</th>
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
                                            @if($task->requirement)
                                                <span class="px-2 py-1 text-xs rounded-full {{ $task->requirement_type === \App\Models\SrsFunctionalRequirement::class ? 'bg-indigo-100 text-indigo-700' : 'bg-purple-100 text-purple-700' }}" title="{{ $task->requirement->title }}">
                                                    {{ $task->requirement->section_number }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
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
                                                $taskStatusColors = [
                                                    'To Do' => 'bg-gray-100 text-gray-800 border-gray-300',
                                                    'In Progress' => 'bg-blue-100 text-blue-800 border-blue-300',
                                                    'Review' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                    'Done' => 'bg-green-100 text-green-800 border-green-300',
                                                ];
                                                $taskStatusIcons = [
                                                    'To Do' => '📋',
                                                    'In Progress' => '🔄',
                                                    'Review' => '👀',
                                                    'Done' => '✅',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 inline-flex items-center gap-1 text-xs font-semibold rounded-full border {{ $taskStatusColors[$task->status] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                                                {{ $taskStatusIcons[$task->status] ?? '📋' }} {{ $task->status }}
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
                                                <button type="button" onclick="openEditTaskModal({{ $task->id }}, '{{ addslashes($task->title) }}', '{{ $task->priority }}', '{{ $task->status }}', {{ $task->assigned_to ?? 'null' }}, '{{ $task->assignee ? addslashes($task->assignee->name) : '' }}', '{{ $task->assignee ? addslashes($task->assignee->email) : '' }}', '{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}', '{{ $task->requirement_type === \App\Models\SrsFunctionalRequirement::class ? 'functional' : ($task->requirement_type === \App\Models\SrsNonFunctionalRequirement::class ? 'non_functional' : '') }}', {{ $task->requirement_id ?? 'null' }})" 
                                                        class="text-indigo-500 hover:text-indigo-700 p-1 rounded hover:bg-indigo-50 transition" title="Edit task">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>

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
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">No tasks for this project yet.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="createTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-[480px] shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4">Add New Task</h3>
                <form action="{{ route('projects.tasks.store', $project) }}" method="POST" class="text-left">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                        <input type="text" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Priority</label>
                            <select name="priority" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
                            <input type="date" name="due_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>
                    
                    <!-- Requirement Selection -->
                    @if($srsDocument && ($allFunctionalReqs->count() || $allNonFunctionalReqs->count()))
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Link to Requirement</label>
                        <select name="requirement_combined" id="requirementSelect" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" onchange="updateRequirementFields('create')">
                            <option value="">— No requirement —</option>
                            @if($allFunctionalReqs->count())
                                <optgroup label="Functional Requirements">
                                    @foreach($allFunctionalReqs as $req)
                                        <option value="functional:{{ $req->id }}">{{ $req->section_number }} - {{ Str::limit($req->title, 40) }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if($allNonFunctionalReqs->count())
                                <optgroup label="Non-Functional Requirements">
                                    @foreach($allNonFunctionalReqs as $req)
                                        <option value="non_functional:{{ $req->id }}">{{ $req->section_number }} - {{ Str::limit($req->title, 40) }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        <input type="hidden" name="requirement_type" id="requirementType">
                        <input type="hidden" name="requirement_id" id="requirementId">
                        <small class="text-xs text-gray-500">Link this task to a specific requirement from the SRS.</small>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Assign To</label>
                        <div class="relative">
                            <input type="text" id="assignSearch" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Search users by name or email..." autocomplete="off">
                            <input type="hidden" name="assigned_to" id="assignedToId">
                            <div id="assignResults" class="absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 hidden max-h-48 overflow-y-auto"></div>
                        </div>
                        <small class="text-xs text-gray-500">Type at least 2 characters to search. The assignee will be notified.</small>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-[480px] shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4">Edit Task</h3>
                <form id="editTaskForm" method="POST" class="text-left">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                        <input type="text" name="title" id="editTaskTitle" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Priority</label>
                            <select name="priority" id="editTaskPriority" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                            <select name="status" id="editTaskStatus" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="To Do">To Do</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Review">Review</option>
                                <option value="Done">Done</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
                        <input type="date" name="due_date" id="editTaskDueDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    
                    <!-- Requirement Selection for Edit -->
                    @if($srsDocument && ($allFunctionalReqs->count() || $allNonFunctionalReqs->count()))
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Link to Requirement</label>
                        <select name="requirement_combined" id="editRequirementSelect" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" onchange="updateRequirementFields('edit')">
                            <option value="">— No requirement —</option>
                            @if($allFunctionalReqs->count())
                                <optgroup label="Functional Requirements">
                                    @foreach($allFunctionalReqs as $req)
                                        <option value="functional:{{ $req->id }}">{{ $req->section_number }} - {{ Str::limit($req->title, 40) }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if($allNonFunctionalReqs->count())
                                <optgroup label="Non-Functional Requirements">
                                    @foreach($allNonFunctionalReqs as $req)
                                        <option value="non_functional:{{ $req->id }}">{{ $req->section_number }} - {{ Str::limit($req->title, 40) }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        <input type="hidden" name="requirement_type" id="editRequirementType">
                        <input type="hidden" name="requirement_id" id="editRequirementId">
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Assign To</label>
                        <div class="relative">
                            <input type="text" id="editAssignSearch" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Search users by name or email..." autocomplete="off">
                            <input type="hidden" name="assigned_to" id="editAssignedToId">
                            <div id="editAssignResults" class="absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 hidden max-h-48 overflow-y-auto"></div>
                        </div>
                        <small class="text-xs text-gray-500">Type to search or clear to unassign.</small>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('editTaskModal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('memberSummaryList');
    fetch('{{ route('projects.members.summary', $project) }}', {
        headers: { 'Accept': 'application/json' }
    }).then(res => res.json())
    .then(data => {
        list.innerHTML = '';
        data.members.forEach(m => {
            const li = document.createElement('li');
            li.className = 'flex justify-between items-center';
            li.innerHTML = `<span>${m.name} <span class="text-xs text-gray-400">${m.email}</span></span><span class="text-sm text-gray-500">${m.active_tasks} open / ${m.total_tasks} total</span>`;
            list.appendChild(li);
        });
    }).catch(() => {
        list.innerHTML = '<li class="text-red-500">Failed to load members.</li>';
    });
});

// Requirement field sync for both create and edit modals
function updateRequirementFields(mode = 'create') {
    const prefix = mode === 'edit' ? 'edit' : '';
    const select = document.getElementById(prefix ? 'editRequirementSelect' : 'requirementSelect');
    const typeInput = document.getElementById(prefix ? 'editRequirementType' : 'requirementType');
    const idInput = document.getElementById(prefix ? 'editRequirementId' : 'requirementId');
    
    if (select && select.value) {
        const [type, id] = select.value.split(':');
        typeInput.value = type;
        idInput.value = id;
    } else {
        if (typeInput) typeInput.value = '';
        if (idInput) idInput.value = '';
    }
}

// Open edit task modal
function openEditTaskModal(taskId, title, priority, status, assignedTo, assigneeName, assigneeEmail, dueDate, reqType, reqId) {
    const form = document.getElementById('editTaskForm');
    form.action = `/tasks/${taskId}`;
    
    document.getElementById('editTaskTitle').value = title;
    document.getElementById('editTaskPriority').value = priority;
    document.getElementById('editTaskStatus').value = status;
    document.getElementById('editTaskDueDate').value = dueDate || '';
    
    // Handle requirement
    const reqSelect = document.getElementById('editRequirementSelect');
    if (reqSelect) {
        if (reqType && reqId) {
            reqSelect.value = `${reqType}:${reqId}`;
        } else {
            reqSelect.value = '';
        }
        updateRequirementFields('edit');
    }
    
    // Handle assignee
    const editAssignedToId = document.getElementById('editAssignedToId');
    const editAssignSearch = document.getElementById('editAssignSearch');
    if (assignedTo && assigneeName) {
        editAssignedToId.value = assignedTo;
        editAssignSearch.value = `${assigneeName} (${assigneeEmail})`;
    } else {
        editAssignedToId.value = '';
        editAssignSearch.value = '';
    }
    
    document.getElementById('editTaskModal').classList.remove('hidden');
}

// User search helper - returns a function that handles search for any input/results pair
function setupUserSearch(inputId, resultsId, hiddenId) {
    const input = document.getElementById(inputId);
    const results = document.getElementById(resultsId);
    const hidden = document.getElementById(hiddenId);
    let timeout;
    
    if (!input || !results || !hidden) return;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const q = this.value.trim();
        if (q.length < 2) { 
            results.classList.add('hidden'); 
            return; 
        }
        timeout = setTimeout(() => {
            fetch(`/web-api/users/search?q=${encodeURIComponent(q)}`, { 
                headers: { 'Accept': 'application/json' }, 
                credentials: 'same-origin' 
            })
            .then(res => res.json())
            .then(data => {
                if (data.users && data.users.length > 0) {
                    results.innerHTML = data.users.map(u => {
                        // Escape the data for safe HTML embedding
                        const safeData = JSON.stringify({id: u.id, name: u.name, email: u.email});
                        return `<div class="px-3 py-2 hover:bg-indigo-50 cursor-pointer user-search-result" data-user='${safeData.replace(/'/g, "&#39;")}'>${escapeHtml(u.name)} <span class='text-xs text-gray-500'>${escapeHtml(u.email)}</span></div>`;
                    }).join('');
                    
                    // Add click handlers
                    results.querySelectorAll('.user-search-result').forEach(el => {
                        el.addEventListener('click', function() {
                            const userData = JSON.parse(this.dataset.user);
                            hidden.value = userData.id;
                            input.value = `${userData.name} (${userData.email})`;
                            results.classList.add('hidden');
                        });
                    });
                    
                    results.classList.remove('hidden');
                } else {
                    results.innerHTML = '<div class="px-3 py-2 text-gray-500">No users found</div>';
                    results.classList.remove('hidden');
                }
            }).catch(() => {
                results.innerHTML = '<div class="px-3 py-2 text-red-500">Search error</div>';
                results.classList.remove('hidden');
            });
        }, 250);
    });
    
    // Clear hidden value when user clears input
    input.addEventListener('blur', function() {
        setTimeout(() => {
            if (!this.value.trim()) {
                hidden.value = '';
            }
            results.classList.add('hidden');
        }, 200);
    });
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initialize user search for both modals
document.addEventListener('DOMContentLoaded', function() {
    setupUserSearch('assignSearch', 'assignResults', 'assignedToId');
    setupUserSearch('editAssignSearch', 'editAssignResults', 'editAssignedToId');
});
</script>
@endpush

</x-app-layout>