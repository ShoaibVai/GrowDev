import Sortable from 'sortablejs';

const KanbanBoard = {
    init(containerSelector, options = {}) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        const columns = container.querySelectorAll('.kanban-column');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        columns.forEach(column => {
            const dropzone = column.querySelector('.kanban-dropzone');
            if (!dropzone) return;

            const status = dropzone.dataset.status;

            new Sortable(dropzone, {
                group: {
                    name: 'shared',
                    pull: true,
                    put: true,
                },
                animation: 200,
                easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                ghostClass: 'opacity-50',
                dragClass: 'shadow-lg rotate-2',
                handle: '.drag-handle',
                onEnd: (evt) => {
                    const taskId = evt.item.dataset.taskId;
                    const newStatus = evt.to.dataset.status;
                    const sortOrder = Array.from(evt.to.children)
                        .filter(el => el.dataset.taskId)
                        .indexOf(evt.item);

                    // Optimistic UI update
                    evt.item.classList.remove('shadow-lg', 'rotate-2');

                    // Update task position indicator
                    const taskEl = evt.item;

                    // Send update to server
                    fetch(`/tasks/${taskId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            status: newStatus,
                            sort_order: sortOrder,
                            title: taskEl.querySelector('.task-title')?.textContent || '',
                        }),
                    })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Failed to update task');
                        }
                    })
                    .catch(err => {
                        console.error('Kanban update failed:', err);
                    });
                },
            });
        });

        // Initialize real-time Echo listener for board updates
        if (window.Echo && options.channel) {
            Echo.channel(options.channel)
                .listen('TaskUpdated', (e) => {
                    const task = e.task;
                    const existing = document.querySelector(`.kanban-card[data-task-id='${task.id}']`);

                    if (existing) {
                        // If status changed, move the card
                        const currentColumn = existing.closest('.kanban-dropzone');
                        if (currentColumn && currentColumn.dataset.status !== task.status) {
                            const targetColumn = document.querySelector(`.kanban-dropzone[data-status='${task.status}']`);
                            if (targetColumn) {
                                targetColumn.appendChild(existing);
                            }
                        }

                        // Update card content
                        existing.querySelector('.task-title').textContent = task.title;
                        existing.querySelector('.task-assignee').textContent =
                            task.assignee ? `Assigned to: ${task.assignee.name}` : 'Unassigned';

                        // Highlight update
                        existing.classList.add('ring-2', 'ring-indigo-400');
                        setTimeout(() => existing.classList.remove('ring-2', 'ring-indigo-400'), 2000);
                    }
                });
        }
    },
};

export default KanbanBoard;
