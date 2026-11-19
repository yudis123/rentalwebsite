const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./application/views/**/*.php', './application/helpers/*.php', './assets/js/app.js'],
  theme: {
    extend: {
      colors: {
        // Warna aksen brand. Ganti `colors.amber` untuk mengubah seluruh aksen situs.
        brand: colors.amber,
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        card: '0 1px 2px rgb(15 23 42 / 0.04), 0 8px 24px -8px rgb(15 23 42 / 0.10)',
        'card-hover': '0 2px 4px rgb(15 23 42 / 0.05), 0 24px 48px -16px rgb(15 23 42 / 0.22)',
      },
    },
  },
  plugins: [],
};
