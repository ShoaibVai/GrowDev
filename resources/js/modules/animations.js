/**
 * Animation Module using Anime.js
 * Provides reusable animation utilities for the application
 */
import anime from 'animejs';

export const Animations = {
    /**
     * Fade in element with scale
     */
    fadeInScale(element, options = {}) {
        return anime({
            targets: element,
            opacity: [0, 1],
            scale: [0.8, 1],
            duration: options.duration || 600,
            easing: options.easing || 'easeOutElastic(1, .8)',
            delay: options.delay || 0
        });
    },

    /**
     * Slide in from direction
     */
    slideIn(element, direction = 'left', options = {}) {
        const translations = {
            left: { translateX: [-100, 0] },
            right: { translateX: [100, 0] },
            top: { translateY: [-100, 0] },
            bottom: { translateY: [100, 0] }
        };

        return anime({
            targets: element,
            opacity: [0, 1],
            ...translations[direction],
            duration: options.duration || 800,
            easing: options.easing || 'easeOutExpo',
            delay: options.delay || 0
        });
    },

    /**
     * Stagger animation for multiple elements
     */
    staggerFadeIn(elements, options = {}) {
        return anime({
            targets: elements,
            opacity: [0, 1],
            translateY: [30, 0],
            duration: options.duration || 600,
            easing: options.easing || 'easeOutQuad',
            delay: anime.stagger(options.stagger || 100)
        });
    },

    /**
     * Card hover effect
     */
    cardHoverEffect(element) {
        const card = element;
        
        card.addEventListener('mouseenter', () => {
            anime({
                targets: card,
                scale: 1.03,
                boxShadow: '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
                duration: 300,
                easing: 'easeOutQuad'
            });
        });

        card.addEventListener('mouseleave', () => {
            anime({
                targets: card,
                scale: 1,
                boxShadow: '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
                duration: 300,
                easing: 'easeOutQuad'
            });
        });
    },

    /**
     * Button ripple effect
     */
    rippleEffect(button) {
        button.addEventListener('click', function(e) {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.width = '0';
            ripple.style.height = '0';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.6)';
            ripple.style.pointerEvents = 'none';
            
            button.style.position = 'relative';
            button.style.overflow = 'hidden';
            button.appendChild(ripple);

            anime({
                targets: ripple,
                width: 200,
                height: 200,
                opacity: [1, 0],
                marginLeft: -100,
                marginTop: -100,
                duration: 600,
                easing: 'easeOutQuad',
                complete: () => ripple.remove()
            });
        });
    },

    /**
     * Number counter animation
     */
    counterAnimation(element, targetValue, options = {}) {
        const obj = { value: 0 };
        return anime({
            targets: obj,
            value: targetValue,
            duration: options.duration || 2000,
            easing: options.easing || 'easeOutExpo',
            round: 1,
            update: function() {
                element.textContent = obj.value;
            }
        });
    },

    /**
     * Pulse animation for notifications
     */
    pulse(element, options = {}) {
        return anime({
            targets: element,
            scale: [1, 1.05, 1],
            duration: options.duration || 800,
            easing: 'easeInOutQuad',
            loop: options.loop || 1
        });
    },

    /**
     * Shake animation for errors
     */
    shake(element, options = {}) {
        return anime({
            targets: element,
            translateX: [
                { value: -10 },
                { value: 10 },
                { value: -10 },
                { value: 10 },
                { value: 0 }
            ],
            duration: options.duration || 400,
            easing: 'easeInOutQuad'
        });
    },

    /**
     * Loading spinner animation
     */
    spinner(element) {
        return anime({
            targets: element,
            rotate: 360,
            duration: 1000,
            easing: 'linear',
            loop: true
        });
    },

    /**
     * Page transition
     */
    pageTransition(options = {}) {
        return anime.timeline()
            .add({
                targets: '.page-content',
                opacity: [1, 0],
                translateY: [0, -20],
                duration: 300,
                easing: 'easeInQuad'
            })
            .add({
                targets: '.page-content',
                opacity: [0, 1],
                translateY: [20, 0],
                duration: 400,
                easing: 'easeOutQuad'
            });
    },

    /**
     * Morph SVG paths
     */
    morphSVG(element, path, options = {}) {
        return anime({
            targets: element,
            d: path,
            duration: options.duration || 800,
            easing: options.easing || 'easeInOutQuad'
        });
    },

    /**
     * Text reveal animation
     */
    textReveal(element, options = {}) {
        const text = element.textContent;
        element.textContent = '';
        element.style.opacity = 1;
        
        const chars = text.split('');
        chars.forEach(char => {
            const span = document.createElement('span');
            span.textContent = char === ' ' ? '\u00A0' : char;
            span.style.opacity = 0;
            element.appendChild(span);
        });

        return anime({
            targets: element.children,
            opacity: [0, 1],
            translateY: [20, 0],
            duration: options.duration || 600,
            easing: options.easing || 'easeOutExpo',
            delay: anime.stagger(options.stagger || 30)
        });
    },

    /**
     * Progress bar animation
     */
    progressBar(element, percent, options = {}) {
        return anime({
            targets: element,
            width: percent + '%',
            duration: options.duration || 1000,
            easing: options.easing || 'easeOutExpo'
        });
    }
};

export default Animations;
