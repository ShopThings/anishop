/** @type {import('tailwindcss').Config} */
export default {
    content: {
        relative: true,
        files: [
            "./index.html",
            "./src/**/*.{js,ts,jsx,tsx,vue}",
        ],
    },
    theme: {
        extend: {
            colors: {
                primary: '#3057d3',
                secondary: '#41475b',
                cool: '#f4f7ff',
            },
            animation: {
                wiggle: 'wiggle 1s ease-in-out infinite',
            },
            keyframes: {
                wiggle: {
                    '0%, 100%': {transform: 'rotate(-10deg)'},
                    '50%': {transform: 'rotate(10deg)'},
                }
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}

