import './bootstrap';

import Alpine from 'alpinejs';
import Animations from './modules/animations';
import ScrollAnimations from './modules/scroll-animations';
import GSAPAnimations from './modules/gsap-animations';
import TypedAnimations from './modules/typed-animations';
import FormAnimations from './modules/form-animations';
import ToastNotification from './modules/toast';

window.Alpine = Alpine;

// Make animation utilities globally available
window.Animations = Animations;
window.ScrollAnimations = ScrollAnimations;
window.GSAPAnimations = GSAPAnimations;
window.TypedAnimations = TypedAnimations;
window.FormAnimations = FormAnimations;

Alpine.start();

// Initialize animations on page load
document.addEventListener('DOMContentLoaded', () => {
    // Initialize scroll animations
    ScrollAnimations.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Animate cards on hover
    const cards = document.querySelectorAll('.card, .project-card, [data-animate-card]');
    cards.forEach(card => {
        Animations.cardHoverEffect(card);
    });

    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('button, .btn, [data-ripple]');
    buttons.forEach(button => {
        Animations.rippleEffect(button);
    });

    // Stagger fade in for lists
    const listItems = document.querySelectorAll('[data-stagger-list] > *');
    if (listItems.length > 0) {
        Animations.staggerFadeIn(listItems);
    }

    // Animate stats counters
    const counters = document.querySelectorAll('[data-counter]');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-counter'));
        Animations.counterAnimation(counter, target);
    });

    // Initialize form animations
    FormAnimations.floatingLabels();
    FormAnimations.checkboxAnimation();
    FormAnimations.selectAnimation();

    // Page transition effect
    const pageContent = document.querySelector('main');
    if (pageContent) {
        pageContent.classList.add('page-content');
        Animations.fadeInScale(pageContent, { duration: 400 });
    }

    // Magnetic effect for primary buttons
    const magneticButtons = document.querySelectorAll('[data-magnetic]');
    magneticButtons.forEach(button => {
        GSAPAnimations.magneticButton(button);
    });

    // Alert animations
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        Animations.slideIn(alert, 'top', { duration: 500 });
        setTimeout(() => {
            if (alert.parentElement) {
                Animations.fadeInScale(alert, { 
                    duration: 300,
                    easing: 'easeInQuad' 
                }).finished.then(() => {
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 300);
                });
            }
        }, 5000);
    });
});

// Re-initialize animations on dynamic content load
document.addEventListener('htmx:afterSwap', () => {
    ScrollAnimations.refresh();
});

// Add smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            GSAPAnimations.smoothScroll(href);
        }
    });
});
