/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{js,jsx,ts,tsx}",
    "./*.{js,jsx,ts,tsx}",
    "./ProductDetailPage.jsx",
  ],
  theme: {
    extend: {
      colors: {
        'kgm-green': {
          50: '#f0f9f4',
          100: '#d8f3dc',
          200: '#a8d5b5',
          300: '#74b88d',
          400: '#52b788',
          500: '#40916c',
          600: '#2d6a4f',
          700: '#1e4d35',
          800: '#1a3a2a',
          900: '#0d2818',
        },
      },
    },
  },
  plugins: [],
}
