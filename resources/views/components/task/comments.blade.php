<div x-data="gdComments({{ $task->id }})">
    <div class="flex items-center gap-2 mb-4">
        <p class="text-[12px] font-semibold uppercase tracking-wider" style="color:var(--color-text-muted)">
            Comments <span x-text="`(${comments.length})`" style="color:var(--color-text-faint)"></span>
        </p>
    </div>

    {{-- Comment list --}}
    <div class="space-y-3 mb-5 max-h-80 overflow-y-auto" x-ref="commentList">
        <template x-for="comment in comments" :key="comment.id">
            <div class="flex gap-3">
                <div class="gd-avatar flex-shrink-0" style="font-size:11px" x-text="comment.user?.name?.charAt(0).toUpperCase()"></div>
                <div class="flex-1 min-w-0 rounded-md p-3 text-[13px]" style="background:var(--color-base)">
                    <div class="flex items-center justify-between">
                        <span class="font-medium" style="color:var(--color-text)" x-text="comment.user?.name"></span>
                        <span class="text-[11px]" style="font-family:var(--font-mono);color:var(--color-text-faint)" x-text="timeAgo(comment.created_at)"></span>
                    </div>
                    <p class="mt-1" style="color:var(--color-text)" x-text="comment.body"></p>
                    <div x-show="canDelete(comment)" class="mt-1.5">
                        <button @click="deleteComment(comment.id)" class="text-[11px] hover:underline" style="color:var(--color-danger)">Delete</button>
                    </div>
                </div>
            </div>
        </template>
        <p x-show="comments.length === 0" class="text-center py-6 text-[13px]" style="color:var(--color-text-muted)">No comments yet.</p>
    </div>

    {{-- Comment form --}}
    <form @submit.prevent="addComment" class="flex gap-2">
        <textarea x-model="newComment" placeholder="Write a comment..."
                  class="gd-textarea flex-1 text-[13px]" rows="2" maxlength="10000"></textarea>
        <button type="submit" :disabled="!newComment.trim()"
                class="gd-btn gd-btn-primary self-end">Post</button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('gdComments', (taskId) => ({
        comments: [],
        newComment: '',
        init() { this.loadComments() },
        loadComments() {
            fetch(`/tasks/${taskId}/comments`)
                .then(r => r.json())
                .then(data => { this.comments = data.data ?? data ?? [] })
                .catch(() => {});
        },
        addComment() {
            if (!this.newComment.trim()) return;
            fetch(`/tasks/${taskId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ body: this.newComment }),
            })
            .then(r => r.json())
            .then(comment => { this.comments.unshift(comment); this.newComment = ''; });
        },
        deleteComment(commentId) {
            if (!confirm('Delete this comment?')) return;
            fetch(`/tasks/${taskId}/comments/${commentId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            }).then(() => { this.comments = this.comments.filter(c => c.id !== commentId) });
        },
        canDelete(comment) {
            const fiveMin = new Date(Date.now() - 5 * 60 * 1000);
            return comment.user_id === Number('{{ Auth::id() }}') && new Date(comment.created_at) > fiveMin;
        },
        timeAgo(date) {
            const diff = Date.now() - new Date(date).getTime();
            const mins = Math.floor(diff / 60000);
            if (mins < 1) return 'just now';
            if (mins < 60) return mins+'m ago';
            const hrs = Math.floor(mins / 60);
            if (hrs < 24) return hrs+'h ago';
            return Math.floor(hrs / 24)+'d ago';
        },
    }));
});
</script>
@endpush
