import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "cos-blue": "#175cd3",
                "cos-red": "#d62828",
                "cos-yellow": "#fcbf49",
                "cos-light-yellow": "#fad991",
                "cos-dark-yellow": "#dea12a",
                "cos-orange": "#f77f00",
                "cos-cream": "#eae2b7",
            },
        },
    },
    plugins: [],
};
