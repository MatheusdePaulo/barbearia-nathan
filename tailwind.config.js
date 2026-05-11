import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/View/ComponentViews/*.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Definindo a cor que combinámos
                'dark-vanilla': '#DEC7A6',
                'brand-bg': '#F8F5EF',
            },
            fontFamily: {
                // Definindo as fontes do Figma
                'barlow': ['Barlow', 'sans-serif'],
                'work-sans': ['Work Sans', 'sans-serif'],
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
            },
            fontWeight: {
                'extrabold': '800',
            }
        },
    },

    plugins: [forms],
};
