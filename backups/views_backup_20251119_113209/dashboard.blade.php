<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1>Dashboard</h1>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                New Project
            </a>
        </div>
    </x-slot>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Projects Grid -->
    @if ($projects->count() > 0)
        <div class="grid grid-2">
            @foreach ($projects as $project)
                <div class="card">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3>{{ $project->name }}</h3>
                            <p class="mb-lg">{{ $project->description ?? 'No description provided' }}</p>
                            <div class="flex gap-md items-center">
                                <span class="badge badge-{{ $project->status === 'active' ? 'primary' : ($project->status === 'completed' ? 'success' : 'warning') }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                                <small>Created {{ $project->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="flex gap-md">
                            <a href="{{ route('projects.edit', $project) }}" class="btn btn-ghost btn-sm" title="Edit">
                                ✏️
                            </a>
                            <form method="POST" action="{{ route('projects.destroy', $project) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost btn-sm" title="Delete" onclick="return confirm('Delete this project?')">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card text-center p-xl">
            <h3>📁 No projects yet</h3>
            <p class="mb-lg">Get started by creating your first project.</p>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a>
        </div>
    @endif
</x-app-layout>
