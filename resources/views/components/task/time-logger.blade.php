<div x-data="gdTimeLogger({{ $task->id }})">
    <p class="text-[12px] font-semibold uppercase tracking-wider mb-4" style="color:var(--color-text-muted)">Time Tracking</p>

    {{-- Timer display --}}
    <div class="rounded-md p-4 mb-4" style="background:var(--color-base);border:1px solid var(--color-border)">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-[11px] font-medium uppercase tracking-wider" style="color:var(--color-text-faint)">Timer</p>
                <p class="text-[24px] font-bold tracking-tight" style="font-family:var(--font-mono);color:var(--color-text)" x-text="formatSeconds(timer.time_spent_seconds)"></p>
                <p class="text-[11px] mt-0.5" style="font-family:var(--font-mono)">
                    <span x-text="timer.timer_state || 'idle'" style="color:var(--color-text-muted)"></span>
                    <template x-if="timer.due_at">
                        <span style="color:var(--color-text-faint)"> &middot; Due <span x-text="formatDate(timer.due_at)"></span></span>
                    </template>
                </p>
            </div>
            <div class="flex flex-wrap gap-1.5">
                <button @click="startTimer" x-show="timer.timer_state === 'idle' || !timer.timer_state"
                        class="gd-btn gd-btn-sm" style="background:var(--color-success);color:#fff">Start</button>
                <button @click="pauseTimer" x-show="timer.timer_state === 'running'"
                        class="gd-btn gd-btn-sm" style="background:var(--color-warning);color:#fff">Pause</button>
                <button @click="resumeTimer" x-show="timer.timer_state === 'paused'"
                        class="gd-btn gd-btn-primary gd-btn-sm">Resume</button>
                <button @click="stopTimer" x-show="timer.timer_state === 'running' || timer.timer_state === 'paused'"
                        class="gd-btn gd-btn-danger gd-btn-sm">Stop</button>
            </div>
        </div>
        <p x-show="timer.is_overdue" class="mt-3 text-[12px] font-medium" style="color:var(--color-danger)">This task is overdue.</p>
    </div>

    {{-- Estimate vs Logged --}}
    <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="rounded-md p-3 text-center" style="background:var(--color-base)">
            <p class="text-[11px] font-medium uppercase tracking-wider" style="color:var(--color-text-faint)">Estimated</p>
            <p class="text-[20px] font-bold" style="font-family:var(--font-mono);color:var(--color-text)">{{ $task->estimated_hours ?? '—' }}<span class="text-[12px]" style="color:var(--color-text-muted)">h</span></p>
        </div>
        <div class="rounded-md p-3 text-center" style="background:var(--color-base)">
            <p class="text-[11px] font-medium uppercase tracking-wider" style="color:var(--color-text-faint)">Logged</p>
            <p class="text-[20px] font-bold" style="font-family:var(--font-mono);color:var(--color-accent)" x-text="totalHours+'h'"></p>
        </div>
    </div>

    {{-- Time log entries --}}
    <div class="space-y-1.5 mb-4 max-h-40 overflow-y-auto">
        <template x-for="log in logs" :key="log.id">
            <div class="flex items-center justify-between text-[12px] px-2 py-1.5 rounded" style="background:var(--color-base)">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="gd-avatar gd-avatar-sm" style="font-size:9px" x-text="log.user?.name?.charAt(0)"></span>
                    <span style="color:var(--color-text)" x-text="log.user?.name"></span>
                    <span x-show="log.description" class="truncate" style="color:var(--color-text-muted)" x-text="log.description"></span>
                </div>
                <span class="font-semibold tabular-nums flex-shrink-0 ml-2" style="font-family:var(--font-mono);color:var(--color-text)" x-text="log.hours+'h'"></span>
            </div>
        </template>
        <p x-show="logs.length === 0" class="text-center py-3 text-[12px]" style="color:var(--color-text-muted)">No time logged yet</p>
    </div>

    {{-- Log time form --}}
    <form @submit.prevent="logTime" class="flex items-end gap-2">
        <input type="number" x-model="hours" step="0.25" min="0.25" max="24"
               class="gd-input h-7 text-[12px]" style="width:64px" placeholder="0.5">
        <input type="text" x-model="description" placeholder="What did you work on?"
               class="gd-input h-7 text-[12px] flex-1">
        <button type="submit" :disabled="!hours" class="gd-btn gd-btn-primary gd-btn-sm">Log</button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('gdTimeLogger', (taskId) => ({
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
        init() { this.loadLogs(); this.loadTimer(); },
        loadLogs() {
            fetch(`/tasks/${taskId}/time-logs`).then(r => r.json()).then(d => { this.logs = d.logs; this.totalHours = d.total_hours; });
        },
        loadTimer() {
            fetch(`/tasks/${taskId}/timer`).then(r => r.json()).then(d => { this.timer = d; });
        },
        timerAction(action) {
            fetch(`/tasks/${taskId}/timer/${action}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            }).then(r => r.json()).then(d => { this.timer = d; });
        },
        startTimer() { this.timerAction('start'); },
        pauseTimer() { this.timerAction('pause'); },
        resumeTimer() { this.timerAction('resume'); },
        stopTimer() { this.timerAction('stop'); this.loadLogs(); },
        formatSeconds(sec) {
            sec = parseInt(sec || 0, 10);
            return Math.floor(sec/3600)+'h '+Math.floor((sec%3600)/60)+'m';
        },
        formatDate(v) { return new Date(v).toLocaleString(); },
        logTime() {
            if (!this.hours || this.hours <= 0) return;
            fetch(`/tasks/${taskId}/time-logs`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ hours: this.hours, logged_at: new Date().toISOString().split('T')[0], description: this.description }),
            }).then(r => r.json()).then(log => {
                this.logs.unshift(log);
                this.totalHours = this.logs.reduce((s, l) => s + parseFloat(l.hours), 0);
                this.hours = ''; this.description = '';
            });
        },
    }));
});
</script>
@endpush
