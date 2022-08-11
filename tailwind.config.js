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
            screens: {
                'xs': {'min': '475px', 'max': '639px'},
            },
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            minHeight: {
                '1/2': '50%',
                '4/5': '80%',
                '95p': '95%',
            },
            maxWidth: {
                '50': '50px',
                '90': '90px',
                '100': '100px',
            },
            width: {
                'xs:w-1/2': '100%',
                '20p': '20%',
            },
            dropShadow: {
                'inset-1': '-1px -1px 0px rgba(0, 0, 0, 0.2)',
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
