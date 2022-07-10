const defaultTheme = require('tailwindcss/defaultTheme');


/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            minHeight: {
                '1/2': '50%',
                '4/5': '80%',
                '95p': '95%',
            }
        },
    },
    variants: {
        extend: {
            animation: ({after}) => after(['motion-safe', 'motion-reduce']),
        }
    },
    plugins: [
        require('flowbite/plugin'),
        require('@tailwindcss/forms'),
    ],
};
