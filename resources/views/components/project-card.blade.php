<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4']) }}>
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $project->name }}</h3>
            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $project->description }}</p>
            <div class="text-xs text-gray-500 flex items-center gap-4">
                <span class="inline-flex items-center gap-1"><strong class="text-gray-700">Type:</strong> {{ ucfirst($project->type ?? 'solo') }}</span>
                <span class="inline-flex items-center gap-1"><strong class="text-gray-700">Status:</strong> {{ ucfirst($project->status) }}</span>
            </div>
        </div>
        <div class="text-right text-sm text-gray-500">
            <span>{{ optional($project->start_date)->format('M d') ?? '' }} - {{ optional($project->end_date)->format('M d') ?? '' }}</span>
            <div class="mt-2">
                @if($project->team)
                    <a href="{{ route('teams.show', $project->team) }}" class="text-indigo-600 hover:underline text-sm">Team: {{ $project->team->name }}</a>
                @endif
            </div>
        </div>
    </div>

    <div class="flex gap-3 mt-4">
        <a href="{{ route('projects.show', $project) }}" class="flex-1 px-3 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition text-center" aria-label="Open {{ $project->name }}">Open</a>
        <a href="{{ route('projects.edit', $project) }}" class="flex-1 px-3 py-2 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition text-center" aria-label="Edit {{ $project->name }}">Edit</a>
        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete project?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition" aria-label="Delete {{ $project->name }}">Delete</button>
        </form>
    </div>
</div>
