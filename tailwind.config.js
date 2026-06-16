/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                nusa: {
                    light: '#E0F2F1', // Diambil dari TealLight
                    DEFAULT: '#008B81', // Diambil dari TealPrimary
                    dark: '#00736B', // Diambil dari TealDark
                }
            }
        }
    },
    plugins: [],
}