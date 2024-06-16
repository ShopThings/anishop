import plugin from "tailwindcss/plugin";

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
        primary: '#3057D3',
        secondary: '#41475B',
        cool: '#EDF3FF',
        awesome: '#EBF2FC',
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
      textShadow: {
        sm: '0 1px 2px var(--tw-shadow-color)',
        DEFAULT: '0 2px 4px var(--tw-shadow-color)',
        lg: '0 8px 16px var(--tw-shadow-color)',
      },
    },
  },
  plugins: [
    plugin(function ({matchUtilities, theme}) {
      matchUtilities(
        {
          'text-shadow': (value) => ({
            textShadow: value,
          }),
        },
        {values: theme('textShadow')}
      )
    }),
    require('@tailwindcss/forms'),
  ],
}
