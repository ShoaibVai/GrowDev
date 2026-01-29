/**
 * Particles.js Background Effect
 */
import 'particles.js';

export const ParticlesEffect = {
    /**
     * Initialize particles background
     */
    init(elementId, options = {}) {
        if (typeof particlesJS === 'undefined') {
            console.error('particles.js not loaded');
            return;
        }

        particlesJS(elementId, {
            particles: {
                number: {
                    value: options.particleCount || 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: options.color || '#6366f1'
                },
                shape: {
                    type: options.shape || 'circle'
                },
                opacity: {
                    value: options.opacity || 0.5,
                    random: false,
                    anim: {
                        enable: false
                    }
                },
                size: {
                    value: options.size || 3,
                    random: true,
                    anim: {
                        enable: false
                    }
                },
                line_linked: {
                    enable: options.lineLinked !== undefined ? options.lineLinked : true,
                    distance: 150,
                    color: options.lineColor || '#6366f1',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: options.speed || 2,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: options.hoverEffect !== undefined ? options.hoverEffect : true,
                        mode: 'repulse'
                    },
                    onclick: {
                        enable: options.clickEffect !== undefined ? options.clickEffect : true,
                        mode: 'push'
                    },
                    resize: true
                },
                modes: {
                    repulse: {
                        distance: 100,
                        duration: 0.4
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });
    }
};

export default ParticlesEffect;
