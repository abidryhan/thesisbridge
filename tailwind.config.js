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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['Lora', ...defaultTheme.fontFamily.serif],
                heading: ['Lora', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // True Academic Navy: zero purple/violet pigment. Deep, dignified, scholarly.
                blue: {
                    50: '#f0f6fa',
                    100: '#ddecf5',
                    200: '#c0ddec',
                    300: '#94c6df',
                    400: '#60a7cc',
                    500: '#3a8bb7',
                    600: '#1e3a5f', // Classic University Navy (Primary Action)
                    700: '#162d4a',
                    800: '#112238',
                    900: '#0c1827',
                    950: '#070e17',
                },
                brand: {
                    50: '#f0f6fa',
                    100: '#ddecf5',
                    200: '#c0ddec',
                    300: '#94c6df',
                    400: '#60a7cc',
                    500: '#3a8bb7',
                    600: '#1e3a5f',
                    700: '#162d4a',
                    800: '#112238',
                    900: '#0c1827',
                    950: '#070e17',
                },
                accent: {
                    amber: '#b45309',
                    crimson: '#9f1239',
                    emerald: '#047857',
                },
            },
        },
    },

    plugins: [forms],
};
