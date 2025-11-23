<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Projects') }}</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">+ Create Project</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-list-controls :route="route('projects.index')" :query="request()->q" :sort="request()->sort" :view="request()->view ?? 'grid'" :extraFilters="['team_id' => $teams]">
                <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">New</a>
            </x-list-controls>

            @if($projects->count())
                @if(request('view') == 'list')
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($projects as $project)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                            <div class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($project->description, 120) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($project->type ?? 'solo') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $project->team ? $project->team->name : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($project->status) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:underline">Open</a>
                                            <a href="{{ route('projects.edit', $project) }}" class="text-yellow-500 hover:underline ml-4">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $project)
                            <x-project-card :project="$project" />
                        @endforeach
                    </div>
                @endif
                <div class="mt-6">{{ $projects->links() }}</div>
            @else
                <div class="text-center py-12 bg-white rounded-lg shadow">
                    <div class="mx-auto mb-4 w-24 h-24 flex items-center justify-center bg-indigo-50 rounded-full">
                        <svg class="w-12 h-12 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>
                    <p class="text-gray-600 text-lg mb-4">No projects yet.</p>
                    <a href="{{ route('projects.create') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">Create your first project</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
