import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ['selector', '[data-theme="dark"]'],

    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                gd: {
                    base:       'var(--color-base)',
                    surface:    'var(--color-surface)',
                    'surface-2': 'var(--color-surface-2)',
                    'surface-3': 'var(--color-surface-3)',
                    border:     'var(--color-border)',
                    'border-2': 'var(--color-border-2)',
                    text:       'var(--color-text)',
                    'text-muted': 'var(--color-text-muted)',
                    'text-faint': 'var(--color-text-faint)',
                    accent:     'var(--color-accent)',
                    success:    'var(--color-success)',
                    warning:    'var(--color-warning)',
                    danger:     'var(--color-danger)',
                    purple:     'var(--color-purple)',
                    orange:     'var(--color-orange)',
                },
            },
            borderRadius: {
                sm:   '4px',
                md:   '6px',
                lg:   '8px',
                xl:   '12px',
                full: '9999px',
            },
            spacing: {
                '4.5':  '1.125rem',
                '13':  '3.25rem',
                '18':  '4.5rem',
                '88':  '22rem',
            },
            transitionTimingFunction: {
                'spring': 'cubic-bezier(0.16, 1, 0.3, 1)',
            },
        },
    },

    plugins: [forms],
};
