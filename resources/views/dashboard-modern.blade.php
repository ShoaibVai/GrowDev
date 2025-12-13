<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">Overview of your projects and tasks.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Project
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Total Projects -->
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Projects</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">{{ $totalProjects }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('projects.index') }}" class="font-medium text-indigo-600 hover:text-indigo-900">View all</a>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Projects</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">{{ $activeProjects }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('projects.index') }}" class="font-medium text-green-600 hover:text-green-900">View active</a>
                </div>
            </div>
        </div>

        <!-- Open Tasks -->
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Open Tasks</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">{{ $openTasksCount }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="#" class="font-medium text-yellow-600 hover:text-yellow-900">View tasks</a>
                </div>
            </div>
        </div>

        <!-- Teams -->
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-300">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Teams</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">{{ $teamsCount }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('teams.index') }}" class="font-medium text-purple-600 hover:text-purple-900">View teams</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Column (Projects & Tasks) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Recent Projects -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Projects</h3>
                    <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View all</a>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($projects as $project)
                        <div class="group relative bg-white border border-gray-200 rounded-lg p-5 hover:border-indigo-300 hover:shadow-md transition-all duration-200 cursor-pointer" onclick="window.location='{{ route('projects.show', $project) }}'">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $project->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $project->description }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Progress</span>
                                    <span>{{ $project->progress ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $project->progress ?? 0 }}%"></div>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex -space-x-2 overflow-hidden">
                                    <!-- Mock avatars for now, replace with real team members if available -->
                                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-gray-300 flex items-center justify-center text-xs text-white">A</div>
                                    <div class="inline-block h-6 w-6 rounded-full ring-2 ring-white bg-gray-400 flex items-center justify-center text-xs text-white">B</div>
                                </div>
                                <div class="text-xs text-gray-400">
                                    Updated {{ $project->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-gray-500">
                            No projects found. <a href="{{ route('projects.create') }}" class="text-indigo-600 hover:underline">Create one?</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- My Tasks -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">My Tasks</h3>
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View all</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tasksAssigned as $task)
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location='{{ route('tasks.show', $task) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-0">
                                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($task->title, 40) }}</div>
                                                <div class="text-xs text-gray-500">{{ $task->priority }} Priority</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ Str::limit($task->project->name ?? 'N/A', 20) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'To Do' => 'bg-gray-100 text-gray-800',
                                                'In Progress' => 'bg-blue-100 text-blue-800',
                                                'Review' => 'bg-yellow-100 text-yellow-800',
                                                'Done' => 'bg-green-100 text-green-800',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $task->due_date ? $task->due_date->format('M d') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No tasks assigned to you.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right Column (Widgets) -->
        <div class="space-y-8">
            
            <!-- Upcoming Deadlines -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Upcoming Deadlines</h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-4">
                        @forelse($upcomingTasks as $task)
                            <li class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <span class="inline-block h-2 w-2 rounded-full bg-red-500 mt-2"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <a href="{{ route('tasks.show', $task) }}" class="hover:underline">{{ $task->title }}</a>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Due {{ $task->due_date->format('M d, Y') }}
                                    </p>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">No upcoming deadlines this week.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Recent SRS Documents -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Documents</h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-4">
                        @forelse($recentSrs as $doc)
                            <li class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <a href="{{ route('documentation.srs.edit', $doc) }}" class="hover:underline">{{ $doc->title }}</a>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Updated {{ $doc->updated_at->diffForHumans() }}
                                    </p>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">No recent documents.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
