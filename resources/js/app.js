import './bootstrap';

import Alpine from 'alpinejs';
import KanbanBoard from './modules/kanban';

window.Alpine = Alpine;
window.KanbanBoard = KanbanBoard;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const pageContent = document.querySelector('main');
    if (pageContent) {
        pageContent.style.opacity = '0';
        requestAnimationFrame(() => {
            pageContent.style.transition = 'opacity 200ms ease-out';
            pageContent.style.opacity = '1';
        });
    }
});
