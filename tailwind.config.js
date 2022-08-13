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
            maxHeight: {
                '50s': '50vh',
                '60s': '60vh',
                '70s': '70vh',
            },
            maxWidth: {
                '50': '50px',
                '90': '90px',
                '100': '100px',
                '120': '120px',
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
