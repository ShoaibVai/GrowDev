/**
 * Scroll Animations Module using AOS (Animate On Scroll)
 */
import AOS from 'aos';
import 'aos/dist/aos.css';

export const ScrollAnimations = {
    /**
     * Initialize AOS
     */
    init(options = {}) {
        AOS.init({
            duration: options.duration || 800,
            easing: options.easing || 'ease-out-quad',
            once: options.once !== undefined ? options.once : true,
            offset: options.offset || 100,
            delay: options.delay || 0,
            anchorPlacement: options.anchorPlacement || 'top-bottom'
        });
    },

    /**
     * Refresh AOS (useful after dynamic content is loaded)
     */
    refresh() {
        AOS.refresh();
    },

    /**
     * Add scroll animations to elements
     */
    addAnimations(elements, animationType = 'fade-up') {
        elements.forEach((element, index) => {
            element.setAttribute('data-aos', animationType);
            element.setAttribute('data-aos-delay', index * 100);
        });
        this.refresh();
    }
};

export default ScrollAnimations;
