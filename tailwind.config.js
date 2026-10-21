import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                ink: {
                    DEFAULT: '#16222E',
                    soft: '#48555F',
                    mute: '#8C97A1',
                },
                primary: {
                    DEFAULT: '#1E5F8C',
                    hover: '#143B57',
                    light: '#E9F1F5',
                },
                sea: '#3B9AB8',
                surface: {
                    DEFAULT: '#FAF7F1',
                    deep: '#F2ECE0',
                },
                line: {
                    DEFAULT: '#E7E0D2',
                    soft: '#F0EADD',
                },
                status: {
                    hadir: '#3F7D58',
                    'hadir-bg': '#EAF3EC',
                    terlambat: '#B8762E',
                    'terlambat-bg': '#FAF1E5',
                    alfa: '#B23A3A',
                    'alfa-bg': '#FAEDED',
                    izin: '#5B6FA8',
                    'izin-bg': '#EEF0F8',
                    cuti: '#8B6BAE',
                    'cuti-bg': '#F3EFF8',
                    sakit: '#A4548A',
                    'sakit-bg': '#F8EFF4',
                    pending: '#B8762E',
                    'pending-bg': '#FAF1E5',
                    approved: '#3F7D58',
                    'approved-bg': '#EAF3EC',
                    rejected: '#B23A3A',
                    'rejected-bg': '#FAEDED',
                },
                navy: {
                    800: '#102A3B',
                    900: '#16374D',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Poppins', 'Inter', 'sans-serif'],
                display: ['Poppins', 'sans-serif'],
                mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
            },
            borderRadius: {
                card: '14px',
                btn: '9px',
                pill: '999px',
            },
            boxShadow: {
                card: '0 1px 2px rgba(22,34,46,.04), 0 10px 28px -16px rgba(22,34,46,.16)',
                'card-hover': '0 8px 24px -8px rgba(22,34,46,.18)',
                sidebar: '4px 0 24px rgba(22,34,46,.15)',
                pop: '0 8px 24px -8px rgba(22,34,46,.18)',
                modal: '0 25px 50px rgba(22,34,46,.12)',
            },
            animation: {
                'fade-in': 'fadeIn 0.6s ease-out forwards',
                'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                'fade-in-down': 'fadeInDown 0.4s ease-out forwards',
                'slide-in-right': 'slideInRight 0.3s ease-out forwards',
                'scale-in': 'scaleIn 0.2s ease-out forwards',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                'shimmer': 'shimmer 1.5s infinite linear',
                'pulse-dot': 'pulseDot 2s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(12px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.6' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                pulseDot: {
                    '0%, 100%': { transform: 'scale(1)', opacity: '1' },
                    '50%': { transform: 'scale(1.4)', opacity: '0.7' },
                },
            },
        },
    },

    plugins: [forms],
};
