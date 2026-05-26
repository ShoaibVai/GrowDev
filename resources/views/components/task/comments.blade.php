<div x-data="taskComments({{ $task->id }})" class="mt-6 border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Comments <span x-text="`(${comments.length})`" class="text-gray-500 text-sm"></span></h3>

    <!-- Comment List -->
    <div class="space-y-4 mb-6" x-ref="commentList">
        <template x-for="comment in comments" :key="comment.id">
            <div class="flex space-x-3">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm font-medium" x-text="comment.user.name.charAt(0).toUpperCase()"></div>
                </div>
                <div class="flex-1 bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900" x-text="comment.user.name"></span>
                        <span class="text-xs text-gray-500" x-text="timeAgo(comment.created_at)"></span>
                    </div>
                    <p class="text-sm text-gray-700 mt-1" x-text="comment.body"></p>
                    <div x-show="canDelete(comment)" class="mt-1">
                        <button @click="deleteComment(comment.id)" class="text-xs text-red-500 hover:text-red-700">Delete</button>
                    </div>
                </div>
            </div>
        </template>
        <p x-show="comments.length === 0" class="text-sm text-gray-400 text-center py-4">No comments yet. Be the first to comment!</p>
    </div>

    <!-- Comment Form -->
    <form @submit.prevent="addComment" class="flex space-x-3">
        <div class="flex-1">
            <textarea x-model="newComment" placeholder="Write a comment... (use @ to mention someone)"
                      class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                      rows="2" maxlength="10000"></textarea>
        </div>
        <button type="submit" :disabled="!newComment.trim()"
                class="self-end px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed">
            Post
        </button>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('taskComments', (taskId) => ({
        comments: [],
        newComment: '',

        init() {
            this.loadComments();
        },

        loadComments() {
            fetch(`/tasks/${taskId}/comments`)
                .then(r => r.json())
                .then(data => {
                    this.comments = data.data ?? data ?? [];
                })
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
            .then(comment => {
                this.comments.unshift(comment);
                this.newComment = '';
            });
        },

        deleteComment(commentId) {
            if (!confirm('Delete this comment?')) return;
            fetch(`/tasks/${taskId}/comments/${commentId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            })
            .then(() => {
                this.comments = this.comments.filter(c => c.id !== commentId);
            });
        },

        canDelete(comment) {
            const fiveMinutesAgo = new Date(Date.now() - 5 * 60 * 1000);
            return comment.user_id === Number('{{ Auth::id() }}') && new Date(comment.created_at) > fiveMinutesAgo;
        },

        timeAgo(date) {
            const diff = Date.now() - new Date(date).getTime();
            const mins = Math.floor(diff / 60000);
            if (mins < 1) return 'just now';
            if (mins < 60) return `${mins}m ago`;
            const hours = Math.floor(mins / 60);
            if (hours < 24) return `${hours}h ago`;
            const days = Math.floor(hours / 24);
            return `${days}d ago`;
        },
    }));
});
</script>
@endpush
