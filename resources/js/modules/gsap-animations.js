/**
 * GSAP Animation Module
 * Advanced animations using GSAP
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export const GSAPAnimations = {
    /**
     * Parallax effect on scroll
     */
    parallax(element, speed = 0.5) {
        gsap.to(element, {
            y: () => window.innerHeight * speed,
            ease: 'none',
            scrollTrigger: {
                trigger: element,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true
            }
        });
    },

    /**
     * Reveal on scroll with stagger
     */
    revealOnScroll(elements, options = {}) {
        gsap.from(elements, {
            y: 50,
            opacity: 0,
            duration: options.duration || 1,
            stagger: options.stagger || 0.1,
            ease: options.ease || 'power3.out',
            scrollTrigger: {
                trigger: elements[0],
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            }
        });
    },

    /**
     * Pin element while scrolling
     */
    pinElement(element, options = {}) {
        ScrollTrigger.create({
            trigger: element,
            start: options.start || 'top top',
            end: options.end || 'bottom bottom',
            pin: true,
            pinSpacing: options.pinSpacing !== undefined ? options.pinSpacing : true
        });
    },

    /**
     * Smooth scroll animation
     */
    smoothScroll(target, options = {}) {
        gsap.to(window, {
            duration: options.duration || 1,
            scrollTo: target,
            ease: options.ease || 'power2.inOut'
        });
    },

    /**
     * Card flip animation
     */
    cardFlip(card, options = {}) {
        const front = card.querySelector('.card-front');
        const back = card.querySelector('.card-back');
        
        let isFlipped = false;
        
        card.addEventListener('click', () => {
            if (!isFlipped) {
                gsap.timeline()
                    .to(front, {
                        rotateY: 180,
                        duration: 0.6,
                        ease: 'power2.inOut'
                    })
                    .to(back, {
                        rotateY: 0,
                        duration: 0.6,
                        ease: 'power2.inOut'
                    }, 0);
            } else {
                gsap.timeline()
                    .to(back, {
                        rotateY: -180,
                        duration: 0.6,
                        ease: 'power2.inOut'
                    })
                    .to(front, {
                        rotateY: 0,
                        duration: 0.6,
                        ease: 'power2.inOut'
                    }, 0);
            }
            isFlipped = !isFlipped;
        });
    },

    /**
     * Magnetic button effect
     */
    magneticButton(button) {
        button.addEventListener('mousemove', (e) => {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            gsap.to(button, {
                x: x * 0.3,
                y: y * 0.3,
                duration: 0.3,
                ease: 'power2.out'
            });
        });
        
        button.addEventListener('mouseleave', () => {
            gsap.to(button, {
                x: 0,
                y: 0,
                duration: 0.5,
                ease: 'elastic.out(1, 0.3)'
            });
        });
    },

    /**
     * Text split and animate
     */
    splitText(element, options = {}) {
        const text = element.textContent;
        const words = text.split(' ');
        element.innerHTML = '';
        
        words.forEach(word => {
            const span = document.createElement('span');
            span.textContent = word + ' ';
            span.style.display = 'inline-block';
            element.appendChild(span);
        });
        
        gsap.from(element.children, {
            y: 50,
            opacity: 0,
            duration: options.duration || 1,
            stagger: options.stagger || 0.1,
            ease: options.ease || 'power3.out'
        });
    }
};

export default GSAPAnimations;
