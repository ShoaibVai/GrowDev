/**
 * Dashboard Animations
 * Initialize all animations for the dashboard page
 */
document.addEventListener('DOMContentLoaded', () => {
    // Animate dashboard stats with counter
    const stats = document.querySelectorAll('[data-counter]');
    stats.forEach((stat, index) => {
        setTimeout(() => {
            const target = parseInt(stat.getAttribute('data-counter'));
            if (!isNaN(target)) {
                Animations.counterAnimation(stat, target, { duration: 1500 });
            }
        }, index * 100);
    });

    // Animate progress bars
    const progressBars = document.querySelectorAll('[data-progress]');
    progressBars.forEach((bar, index) => {
        setTimeout(() => {
            const progress = bar.getAttribute('data-progress');
            Animations.progressBar(bar, progress, { duration: 1200 });
        }, 500 + (index * 100));
    });

    // Add stagger animation to project cards
    const projectCards = document.querySelectorAll('.project-card');
    if (projectCards.length > 0) {
        Animations.staggerFadeIn(projectCards, { stagger: 150 });
    }

    // Add stagger animation to task items
    const taskItems = document.querySelectorAll('.task-item');
    if (taskItems.length > 0) {
        Animations.staggerFadeIn(taskItems, { stagger: 80 });
    }

    // Magnetic effect on action buttons
    const actionButtons = document.querySelectorAll('.action-button');
    actionButtons.forEach(button => {
        GSAPAnimations.magneticButton(button);
    });

    // Add hover animation to all cards
    const allCards = document.querySelectorAll('.card, [data-animate-card]');
    allCards.forEach(card => {
        Animations.cardHoverEffect(card);
    });

    // Animate alerts with slide in
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        Animations.slideIn(alert, 'top', { duration: 500 });
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            Animations.fadeInScale(alert, { 
                duration: 300,
                easing: 'easeInQuad' 
            });
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }, 300);
        }, 5000);
    });

    // Add pulse animation to notification badges
    const notificationBadges = document.querySelectorAll('.notification-badge');
    notificationBadges.forEach(badge => {
        Animations.pulse(badge, { loop: true, duration: 2000 });
    });

    // Initialize form animations for any forms on the page
    FormAnimations.floatingLabels();
    FormAnimations.checkboxAnimation();
});

// Listen for dynamic content updates
document.addEventListener('htmx:afterSwap', () => {
    ScrollAnimations.refresh();
    
    // Re-initialize animations on newly loaded content
    const newCards = document.querySelectorAll('.card:not(.animated)');
    newCards.forEach(card => {
        card.classList.add('animated');
        Animations.cardHoverEffect(card);
    });
});
