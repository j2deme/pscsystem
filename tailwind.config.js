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
            },
        },
    },

    plugins: [forms],

    safelist: [
        'w-6', 'h-6', 'text-green-500', 'dark:text-green-400',
        'bg-red-600', 'text-white', 'rounded-full',
        'max-h-80', 'overflow-y-auto', 'text-sm', 'w-80',
    ],

};
module.exports = {
    important: true,
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    /*theme: {
        screens: {
          'tablet': '640px',
          // => @media (min-width: 640px) { ... }

          'laptop': '1024px',
          // => @media (min-width: 1024px) { ... }

          'desktop': '1280px',
          // => @media (min-width: 1280px) { ... }
        },
      }*/
}
