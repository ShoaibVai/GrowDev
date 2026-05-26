<div x-data="timeLogger({{ $task->id }})" class="mt-6 border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Time Tracking</h3>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Estimated</p>
            <p class="text-2xl font-bold text-gray-900">{{ $task->estimated_hours ?? '—' }}<span class="text-sm text-gray-500" x-show="{{ $task->estimated_hours }}">h</span></p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Logged</p>
            <p class="text-2xl font-bold text-indigo-600" x-text="`${totalHours}h`"></p>
        </div>
    </div>

    <!-- Logged time entries -->
    <div class="space-y-2 mb-4" x-ref="logList">
        <template x-for="log in logs" :key="log.id">
            <div class="flex items-center justify-between bg-gray-50 rounded px-3 py-2">
                <div>
                    <span class="text-sm font-medium text-gray-900" x-text="log.user.name"></span>
                    <span class="text-xs text-gray-500 ml-2" x-text="log.logged_at"></span>
                    <p x-show="log.description" class="text-xs text-gray-500" x-text="log.description"></p>
                </div>
                <span class="text-sm font-semibold text-gray-700" x-text="`${log.hours}h`"></span>
            </div>
        </template>
        <p x-show="logs.length === 0" class="text-sm text-gray-400 text-center py-4">No time logged yet.</p>
    </div>

    <!-- Log time form -->
    <form @submit.prevent="logTime" class="flex items-end space-x-2">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Hours</label>
            <input type="number" x-model="hours" step="0.25" min="0.25" max="24"
                   class="w-20 border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex-1">
            <label class="block text-xs text-gray-500 mb-1">Description (optional)</label>
            <input type="text" x-model="description" placeholder="What did you work on?"
                   class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <button type="submit" :disabled="!hours"
                class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 disabled:opacity-50">
            Log
        </button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('timeLogger', (taskId) => ({
        logs: [],
        totalHours: 0,
        hours: '',
        description: '',
        logged_at: new Date().toISOString().split('T')[0],

        init() {
            this.loadLogs();
        },

        loadLogs() {
            fetch(`/tasks/${taskId}/time-logs`)
                .then(r => r.json())
                .then(data => {
                    this.logs = data.logs;
                    this.totalHours = data.total_hours;
                })
                .catch(() => {});
        },

        logTime() {
            if (!this.hours || this.hours <= 0) return;
            fetch(`/tasks/${taskId}/time-logs`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    hours: this.hours,
                    logged_at: this.logged_at,
                    description: this.description,
                }),
            })
            .then(r => r.json())
            .then(log => {
                this.logs.unshift(log);
                this.totalHours = this.logs.reduce((sum, l) => sum + parseFloat(l.hours), 0);
                this.hours = '';
                this.description = '';
            });
        },
    }));
});
</script>
@endpush
