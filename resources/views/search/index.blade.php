<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Search Results: "{{ $q }}"
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <form method="GET" action="{{ route('search') }}" class="max-w-xl">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $q }}" placeholder="Search projects, tasks, teams..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               autofocus>
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>

            <div class="space-y-8">
                <!-- Projects -->
                @if($projects->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Projects ({{ $projects->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($projects as $project)
                                <a href="{{ route('projects.show', $project) }}" class="block bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-gray-900">{{ $project->name }}</span>
                                        <span class="text-xs px-2 py-1 rounded-full
                                            @if($project->status === 'active') bg-green-100 text-green-800
                                            @elseif($project->status === 'completed') bg-gray-100 text-gray-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tasks -->
                @if($tasks->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Tasks ({{ $tasks->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($tasks as $task)
                                <a href="{{ route('tasks.show', $task) }}" class="block bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-medium text-gray-900">{{ $task->title }}</span>
                                            <span class="text-xs text-gray-500 ml-2">in {{ $task->project->name }}</span>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full
                                            @if($task->status === 'Done') bg-green-100 text-green-800
                                            @elseif($task->status === 'In Progress') bg-blue-100 text-blue-800
                                            @elseif($task->status === 'Review') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $task->status }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Teams -->
                @if($teams->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Teams ({{ $teams->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($teams as $team)
                                <a href="{{ route('teams.show', $team) }}" class="block bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                                    <span class="font-medium text-gray-900">{{ $team->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- SRS Documents -->
                @if($srsDocs->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">SRS Documents ({{ $srsDocs->count() }})</h3>
                        <div class="space-y-2">
                            @foreach($srsDocs as $doc)
                                <a href="{{ route('documentation.srs.edit', $doc) }}" class="block bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $doc->title }}</span>
                                        @if($doc->project)
                                            <span class="text-xs text-gray-500 ml-2">— {{ $doc->project->name }}</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($projects->isEmpty() && $tasks->isEmpty() && $teams->isEmpty() && $srsDocs->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <p class="mt-2 text-gray-500">No results found for "{{ $q }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
