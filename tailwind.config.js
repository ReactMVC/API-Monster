/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./**/*.{html,js,php}"],
    theme: {
        extend: {},
    },
    plugins: [],
}

// npx tailwindcss -i ./public/assets/core.css -o ./public/assets/tailwind.min.css --minify