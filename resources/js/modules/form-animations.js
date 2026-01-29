/**
 * Form Animations and Interactive Effects
 */
import anime from 'animejs';

export const FormAnimations = {
    /**
     * Floating label effect
     */
    floatingLabels() {
        const inputs = document.querySelectorAll('.floating-input');
        
        inputs.forEach(input => {
            const label = input.previousElementSibling;
            
            if (input.value) {
                label.classList.add('active');
            }
            
            input.addEventListener('focus', () => {
                label.classList.add('active');
                anime({
                    targets: label,
                    translateY: [-5, -20],
                    scale: [1, 0.85],
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });
            
            input.addEventListener('blur', () => {
                if (!input.value) {
                    label.classList.remove('active');
                    anime({
                        targets: label,
                        translateY: 0,
                        scale: 1,
                        duration: 300,
                        easing: 'easeOutQuad'
                    });
                }
            });
        });
    },

    /**
     * Input validation animation
     */
    validationAnimation(input, isValid) {
        if (isValid) {
            input.classList.remove('error');
            input.classList.add('success');
            anime({
                targets: input,
                scale: [1, 1.02, 1],
                duration: 400,
                easing: 'easeInOutQuad'
            });
        } else {
            input.classList.remove('success');
            input.classList.add('error');
            anime({
                targets: input,
                translateX: [
                    { value: -10 },
                    { value: 10 },
                    { value: -10 },
                    { value: 10 },
                    { value: 0 }
                ],
                duration: 400,
                easing: 'easeInOutQuad'
            });
        }
    },

    /**
     * Submit button loading animation
     */
    submitButtonLoading(button) {
        const originalText = button.textContent;
        const spinner = document.createElement('span');
        spinner.className = 'inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin';
        
        button.disabled = true;
        button.textContent = '';
        button.appendChild(spinner);
        
        return () => {
            button.disabled = false;
            button.textContent = originalText;
        };
    },

    /**
     * Checkbox animation
     */
    checkboxAnimation() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    anime({
                        targets: this.parentElement,
                        scale: [1, 1.1, 1],
                        duration: 300,
                        easing: 'easeOutElastic(1, .8)'
                    });
                }
            });
        });
    },

    /**
     * Progress stepper animation
     */
    progressStepper(currentStep, totalSteps) {
        const progress = (currentStep / totalSteps) * 100;
        const progressBar = document.querySelector('.progress-bar');
        
        if (progressBar) {
            anime({
                targets: progressBar,
                width: progress + '%',
                duration: 600,
                easing: 'easeOutExpo'
            });
        }
    },

    /**
     * Select dropdown animation
     */
    selectAnimation() {
        const selects = document.querySelectorAll('select');
        
        selects.forEach(select => {
            select.addEventListener('focus', function() {
                anime({
                    targets: this,
                    scale: [1, 1.02],
                    boxShadow: ['0 1px 3px rgba(0,0,0,0.12)', '0 4px 6px rgba(0,0,0,0.15)'],
                    duration: 200,
                    easing: 'easeOutQuad'
                });
            });
            
            select.addEventListener('blur', function() {
                anime({
                    targets: this,
                    scale: 1,
                    boxShadow: '0 1px 3px rgba(0,0,0,0.12)',
                    duration: 200,
                    easing: 'easeOutQuad'
                });
            });
        });
    }
};

export default FormAnimations;
