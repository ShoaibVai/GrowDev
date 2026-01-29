@php
    $taskStats = $project->tasks->groupBy('status')->map->count();
    $totalTasks = $project->tasks->count();
    $doneTasks = $taskStats->get('Done', 0);
    $inProgressTasks = $taskStats->get('In Progress', 0);
    $todoTasks = $taskStats->get('To Do', 0);
    $reviewTasks = $taskStats->get('Review', 0);
    $completionPercent = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 border-l-4 card project-card']) }} 
     data-aos="fade-up" 
     data-animate-card>
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <h3 class="text-xl font-bold text-gray-900 mb-1" data-aos="fade-right" data-aos-delay="100">{{ $project->name }}</h3>
            <p class="text-gray-600 text-sm mb-3 line-clamp-2" data-aos="fade-right" data-aos-delay="150">{{ $project->description }}</p>
            <div class="text-xs text-gray-500 flex items-center gap-4" data-aos="fade-right" data-aos-delay="200">
                <span class="inline-flex items-center gap-1"><strong class="text-gray-700">Type:</strong> {{ ucfirst($project->type ?? 'solo') }}</span>
                <span class="inline-flex items-center gap-1"><strong class="text-gray-700">Status:</strong> {{ ucfirst($project->status) }}</span>
            </div>
        </div>
        <div class="text-right text-sm text-gray-500" data-aos="fade-left" data-aos-delay="100">
            <span>{{ optional($project->start_date)->format('M d') ?? '' }} - {{ optional($project->end_date)->format('M d') ?? '' }}</span>
            <div class="mt-2">
                @if($project->team)
                    <a href="{{ route('teams.show', $project->team) }}" class="text-indigo-600 hover:underline text-sm">Team: {{ $project->team->name }}</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Task Status Summary (FR2.5/FR2.6) --}}
    @if($totalTasks > 0)
        <div class="mt-4 pt-4 border-t border-gray-100" data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600">Task Progress</span>
                <span class="text-xs font-bold text-indigo-600" data-counter="{{ $completionPercent }}">0</span>%
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2 rounded-full transition-all duration-1000 ease-out progress-bar" 
                     style="width: 0%" 
                     data-progress="{{ $completionPercent }}"></div>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($todoTasks > 0)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700 transition-transform hover:scale-105" data-aos="zoom-in" data-aos-delay="300">
                        📋 {{ $todoTasks }} To Do
                    </span>
                @endif
                @if($inProgressTasks > 0)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 transition-transform hover:scale-105" data-aos="zoom-in" data-aos-delay="350">
                        🔄 {{ $inProgressTasks }} In Progress
                    </span>
                @endif
                @if($reviewTasks > 0)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 transition-transform hover:scale-105" data-aos="zoom-in" data-aos-delay="400">
                        👀 {{ $reviewTasks }} Review
                    </span>
                @endif
                @if($doneTasks > 0)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 transition-transform hover:scale-105" data-aos="zoom-in" data-aos-delay="450">
                        ✅ {{ $doneTasks }} Done
                    </span>
                @endif
            </div>
        </div>
    @else
        <div class="mt-4 pt-4 border-t border-gray-100">
            <span class="text-xs text-gray-400">No tasks yet</span>
        </div>
    @endif

    <div class="flex gap-3 mt-4" data-aos="fade-up" data-aos-delay="500">
        <a href="{{ route('projects.show', $project) }}" 
           class="flex-1 px-3 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white text-sm rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all duration-300 text-center transform hover:-translate-y-0.5 hover:shadow-md" 
           aria-label="Open {{ $project->name }}"
           data-ripple>Open</a>
        <a href="{{ route('projects.edit', $project) }}" 
           class="flex-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all duration-300 text-center transform hover:-translate-y-0.5 hover:shadow-md" 
           aria-label="Edit {{ $project->name }}"
           data-ripple>Edit</a>
        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete project?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="w-full px-3 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md" 
                    aria-label="Delete {{ $project->name }}"
                    data-ripple>Delete</button>
        </form>
    </div>
</div>

<script>
    // Animate progress bar on page load
    document.addEventListener('DOMContentLoaded', () => {
        const progressBars = document.querySelectorAll('[data-progress]');
        progressBars.forEach(bar => {
            setTimeout(() => {
                const progress = bar.getAttribute('data-progress');
                bar.style.width = progress + '%';
            }, 500);
        });
    });
</script>
