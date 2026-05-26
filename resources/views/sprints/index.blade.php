<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $project->name }} — Sprints
                </h2>
                <p class="text-sm text-gray-500">Manage iterations for your project.</p>
            </div>
            <a href="{{ route('sprints.create', $project) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Sprint
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
            @endif

            @forelse($sprints as $sprint)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <a href="{{ route('sprints.show', [$project, $sprint]) }}" class="text-lg font-semibold text-gray-900 hover:text-indigo-600">
                                    {{ $sprint->name }}
                                </a>
                                @if($sprint->goal)
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($sprint->goal, 120) }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                    <span>{{ $sprint->start_date->format('M d, Y') }} — {{ $sprint->end_date->format('M d, Y') }}</span>
                                    <span>{{ $sprint->tasks_count ?? 0 }} tasks</span>
                                </div>
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
                                        <button type="submit" class="text-sm text-green-600 hover:text-green-800 font-medium">Start</button>
                                    </form>
                                @endif
                                <a href="{{ route('sprints.edit', [$project, $sprint]) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No sprints</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first sprint.</p>
                    <a href="{{ route('sprints.create', $project) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">Create Sprint</a>
                </div>
            @endforelse

            <div class="mt-4">{{ $sprints->links() }}</div>
        </div>
    </div>
</x-app-layout>
