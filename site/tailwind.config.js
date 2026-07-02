import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                benizia: {
                    // Brand palette derived from the Hotel Benizia logo (wine + gold).
                    // `green` is kept as the primary key for backwards compatibility
                    // with existing utility classes, but now holds the logo wine.
                    green: '#7C0E52',
                    maroon: '#7C0E52',
                    wine: '#560A3A',
                    gold: '#C79A46',
                    cream: '#FAF7F1',
                    charcoal: '#241A20',
                },
            },
            fontFamily: {
                serif: ['"Playfair Display"', 'serif'],
                sans: ['Inter', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
