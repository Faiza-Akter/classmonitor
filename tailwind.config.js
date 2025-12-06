import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                // Primary Colors
                'primary': '#4F46E5',
                'primary-light': '#6366F1',
                'primary-dark': '#4338CA',
                
                // Secondary Colors
                'secondary': '#38BDF8',
                'secondary-light': '#7DD3FC',
                
                // Accent Colors
                'accent': '#10B981',
                'accent-light': '#34D399',
                
                // Status Colors
                'warning': '#e8ec1dff',
                'error': '#F43F5E',
                'success': '#10B981',
                
                // Neutral Colors
                'dark': '#1E293B',
                'gray-dark': '#334155',
                'gray': '#64748B',
                'gray-light': '#E2E8F0',
                'gray-lighter': '#F1F5F9',
                'light': '#F8FAFC',
                
                // Your original colors preserved as custom
                'brand-indigo': '#4F46E5',
                'brand-indigo-light': '#6366F1',
                'brand-slate': '#1E293B',
                'brand-sky': '#38BDF8',
                'brand-emerald': '#10B981',
                'brand-amber': '#FBBF24',
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'xl': '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
            keyframes: {
                'float': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                'fade-in': {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'pulse-glow': {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.7' },
                },
                'slide-in-right': {
                    '0%': { transform: 'translateX(20px)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                }
            },
            animation: {
                'float-slow': 'float 6s ease-in-out infinite',
                'fade-in': 'fade-in 0.6s ease-out',
                'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
                'slide-in-right': 'slide-in-right 0.5s ease-out',
            },
            boxShadow: {
                'soft': '0 4px 20px rgba(0, 0, 0, 0.08)',
                'medium': '0 10px 40px rgba(0, 0, 0, 0.12)',
                'strong': '0 20px 60px rgba(0, 0, 0, 0.15)',
                'inner-soft': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.05)',
            },
            backgroundImage: {
                'gradient-primary': 'linear-gradient(135deg, #4F46E5 0%, #6366F1 100%)',
                'gradient-secondary': 'linear-gradient(135deg, #38BDF8 0%, #0EA5E9 100%)',
                'gradient-accent': 'linear-gradient(135deg, #10B981 0%, #34D399 100%)',
            }
        },
    },

    plugins: [forms],
};