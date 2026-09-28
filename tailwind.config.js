import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const brandColors = {
    50: '#effaf6',
    100: '#d8f3e8',
    200: '#b3e7d3',
    300: '#83d6b8',
    400: '#4cc29a',
    500: '#32b990',
    600: '#27b08a',
    700: '#20a783',
    800: '#168f72',
    900: '#125e4d',
    950: '#082f29',
};

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
                violet: brandColors,
                indigo: brandColors,
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
