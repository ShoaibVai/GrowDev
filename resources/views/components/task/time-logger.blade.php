<div x-data="timeLogger({{ $task->id }})" class="mt-6 border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Time Tracking</h3>

    <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <p class="text-sm text-gray-500">Timer</p>
                <p class="text-xl font-bold text-gray-900" x-text="formatSeconds(timer.time_spent_seconds)"></p>
                <p class="text-xs text-gray-500">
                    <span x-text="timer.timer_state"></span>
                    <template x-if="timer.due_at">
                        <span> · Due <span x-text="formatDate(timer.due_at)"></span></span>
                    </template>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="startTimer" x-show="timer.timer_state === 'idle' || !timer.timer_state"
                        class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Start</button>
                <button type="button" @click="pauseTimer" x-show="timer.timer_state === 'running'"
                        class="px-3 py-1.5 bg-amber-600 text-white text-sm rounded-md hover:bg-amber-700">Pause</button>
                <button type="button" @click="resumeTimer" x-show="timer.timer_state === 'paused'"
                        class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">Resume</button>
                <button type="button" @click="stopTimer" x-show="timer.timer_state === 'running' || timer.timer_state === 'paused'"
                        class="px-3 py-1.5 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-900">Stop</button>
            </div>
        </div>
        <p x-show="timer.is_overdue" class="mt-3 text-sm text-red-600 font-medium">This task is overdue.</p>
    </div>

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
        timer: {
            timer_state: 'idle',
            time_spent_seconds: {{ (int) ($task->time_spent_seconds ?? 0) }},
            due_at: @json($task->due_at?->toISOString()),
            is_overdue: @json($task->isOverdue()),
        },
        hours: '',
        description: '',
        logged_at: new Date().toISOString().split('T')[0],

        init() {
            this.loadLogs();
            this.loadTimer();
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

        loadTimer() {
            fetch(`/tasks/${taskId}/timer`)
                .then(r => r.json())
                .then(data => {
                    this.timer = data;
                })
                .catch(() => {});
        },

        timerAction(action) {
            fetch(`/tasks/${taskId}/timer/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(r => r.json())
            .then(data => {
                this.timer = data;
            });
        },

        startTimer() {
            this.timerAction('start');
        },

        pauseTimer() {
            this.timerAction('pause');
        },

        resumeTimer() {
            this.timerAction('resume');
        },

        stopTimer() {
            this.timerAction('stop');
            this.loadLogs();
        },

        formatSeconds(seconds) {
            seconds = parseInt(seconds || 0, 10);
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            return `${hours}h ${minutes}m`;
        },

        formatDate(value) {
            return new Date(value).toLocaleString();
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
