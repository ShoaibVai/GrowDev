<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->name }} — Kanban Board
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <h1 class="text-xl font-bold mb-4">{{ $project->name }} — Kanban Board</h1>
    <div class="grid grid-cols-4 gap-4">
        @foreach(['To Do', 'In Progress', 'Review', 'Done'] as $status)
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <h3 class="text-sm font-semibold mb-2">{{ $status }}</h3>
            <div class="min-h-48 space-y-2 dropzone" data-status="{{ $status }}">
                @foreach($tasks->get($status, collect()) as $task)
                <div class="p-3 border rounded bg-gray-50 draggable" draggable="true" data-task-id="{{ $task->id }}">
                    <div class="text-sm font-medium">{{ $task->title }}</div>
                    <div class="text-xs text-gray-500">Assigned to: {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
        </div>
    </div>

    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const draggables = document.querySelectorAll('.draggable');
    const dropzones = document.querySelectorAll('.dropzone');

    draggables.forEach(d => {
        d.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', d.dataset.taskId);
            d.classList.add('opacity-50');
        });
        d.addEventListener('dragend', (e) => {
            d.classList.remove('opacity-50');
        });
    });

    dropzones.forEach(zone => {
        zone.addEventListener('dragover', (e) => { e.preventDefault(); });
        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            const id = e.dataTransfer.getData('text/plain');
            const taskEl = document.querySelector(`.draggable[data-task-id='${id}']`);
            if (!taskEl) return;
            zone.appendChild(taskEl);
            const newStatus = zone.dataset.status;
            // Send request to update task
            fetch(`/tasks/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: newStatus, title: taskEl.querySelector('.font-medium').textContent })
            }).then(res => {
                if (!res.ok) {
                    alert('Failed to move task');
                }
            });
        });
    });
    // Real-time updates via Echo
    if (window.Echo) {
        Echo.channel('project.{{ $project->id }}')
            .listen('TaskUpdated', (e) => {
                const task = e.task;
                const existing = document.querySelector(`.draggable[data-task-id='${task.id}']`);
                // Remove existing element if present
                if (existing) {
                    existing.remove();
                }
                // Create new element
                const el = document.createElement('div');
                el.className = 'p-3 border rounded bg-gray-50 draggable';
                el.setAttribute('draggable', 'true');
                el.setAttribute('data-task-id', task.id);
                el.innerHTML = `<div class="text-sm font-medium">${task.title}</div><div class="text-xs text-gray-500">Assigned to: ${task.assignee ? task.assignee.name : 'Unassigned'}</div>`;
                // Append to status column
                const zone = document.querySelector(`.dropzone[data-status='${task.status}']`);
                if (zone) {
                    zone.appendChild(el);
                    // Re-attach drag handlers
                    el.addEventListener('dragstart', (e) => { e.dataTransfer.setData('text/plain', el.dataset.taskId); el.classList.add('opacity-50'); });
                    el.addEventListener('dragend', (e) => { el.classList.remove('opacity-50'); });
                }
            });
    }
});
</script>
    @endpush
</x-app-layout>
