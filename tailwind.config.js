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
        'w-10', 'h-10',
        'bg-white', 'bg-white/80',
        'dark:bg-gray-700',
        'text-gray-800', 'dark:text-white',
        'hover:bg-blue-500',
        'hover:text-white',
        'rounded-full',
        'absolute', 'left-2', 'right-2', 'top-1/2',
        '-translate-y-1/2',
        'z-20', 'flex', 'items-center', 'justify-center',
        'transition',
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
