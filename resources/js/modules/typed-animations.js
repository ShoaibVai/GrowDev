/**
 * Typed.js Integration
 * For typing animation effects
 */
import Typed from 'typed.js';

export const TypedAnimations = {
    /**
     * Create a typing animation
     */
    init(element, options = {}) {
        return new Typed(element, {
            strings: options.strings || ['Welcome to GrowDev'],
            typeSpeed: options.typeSpeed || 50,
            backSpeed: options.backSpeed || 30,
            backDelay: options.backDelay || 1000,
            loop: options.loop || false,
            showCursor: options.showCursor !== undefined ? options.showCursor : true,
            cursorChar: options.cursorChar || '|',
            autoInsertCss: true,
            onComplete: options.onComplete || null
        });
    }
};

export default TypedAnimations;
