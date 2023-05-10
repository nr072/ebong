/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./resources/**/*.{html,blade.php}"],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
