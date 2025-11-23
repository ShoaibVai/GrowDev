<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('projects.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('New Project') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Dashboard layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-2 space-y-6">
                    {{-- Top Stats --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <x-dashboard-stat title="Projects" :value="$totalProjects" color="indigo" :icon="'📁'" />
                        <x-dashboard-stat title="Active Projects" :value="$activeProjects" color="green" :icon="'🚀'" />
                        <x-dashboard-stat title="Open Tasks" :value="$openTasksCount" color="yellow" :icon="'📝'" />
                        <x-dashboard-stat title="Teams" :value="$teamsCount" color="purple" :icon="'👥'" />
                    </div>

                    {{-- Recent Projects --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Projects</h3>
                            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:underline">View All</a>
                        </div>
                        @if($projects->count())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($projects as $project)
                                    <x-project-card :project="$project" />
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">No recent projects.</div>
                        @endif
                    </div>

                    {{-- My Tasks --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">My Tasks</h3>
                            <a href="{{ route('projects.index') }}" class="text-sm text-indigo-600 hover:underline">View Tasks</a>
                        </div>
                        @if($tasksAssigned->count())
                            <div class="overflow-hidden rounded-md border">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignee</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($tasksAssigned as $task)
                                            <x-recent-task :task="$task" />
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">No tasks assigned to you.</div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <h4 class="font-semibold mb-2">Upcoming Tasks</h4>
                        @if($upcomingTasks->count())
                            <ul class="divide-y divide-gray-100">
                                @foreach($upcomingTasks as $ut)
                                    <li class="py-3 flex items-start justify-between">
                                        <div>
                                            <div class="font-medium text-sm text-gray-900">{{ $ut->title }}</div>
                                            <div class="text-xs text-gray-500">Due {{ optional($ut->due_date)->format('M d, Y') }}</div>
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $ut->status }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-sm text-gray-500">No upcoming tasks in the next 7 days.</div>
                        @endif
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-semibold">Quick Actions</h4>
                        </div>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('projects.create') }}" class="px-3 py-2 rounded bg-indigo-600 text-white text-sm text-center">New Project</a>
                            <a href="{{ route('documentation.srs.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white text-sm text-center">New SRS</a>
                            <a href="{{ route('documentation.sdd.create') }}" class="px-3 py-2 rounded bg-green-600 text-white text-sm text-center">New SDD</a>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <h4 class="font-semibold mb-2">Recent Documents</h4>
                        @if($recentSrs->count() || $recentSdd->count())
                            <div class="space-y-2">
                                @foreach($recentSrs as $srs)
                                    <a href="{{ route('documentation.srs.edit', $srs) }}" class="block text-sm text-gray-700 hover:underline">📄 {{ $srs->title }}</a>
                                @endforeach
                                @foreach($recentSdd as $sdd)
                                    <a href="{{ route('documentation.sdd.edit', $sdd) }}" class="block text-sm text-gray-700 hover:underline">🏗️ {{ $sdd->title }}</a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-500">No recent documents.</div>
                        @endif
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                        <h4 class="font-semibold mb-2">Your Teams</h4>
                        @if($teams->count())
                            <div class="space-y-2">
                                @foreach($teams as $team)
                                    <a href="{{ route('teams.show', $team) }}" class="block text-sm text-gray-700 hover:underline">👥 {{ $team->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-gray-500">Not part of any teams yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
