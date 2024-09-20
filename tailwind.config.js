const plugin = require('tailwindcss/plugin');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./vendor/tales-from-a-dev/flowbite-bundle/templates/**/*.html.twig",
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./src/Twig/Components/**/*.php"
  ],
  theme: {
    extend: {
        animation: {
            'fade-in' : 'fadeIn .5s ease-out;'
        },
        keyframes: {
            fadeIn: {
                '0%': { opacity: 0 },
                '100%': { opacity: 1 }
            }
        }
    }
    },
    plugins: [
        plugin(function({addVariant}){
            addVariant('turbo-frame','turbo-frame[src] &');
            /* call this modal, and activate it whenever we are inside a dialog element */
            addVariant('modal','dialog &');
      })
  ]
}
