const colors = require('tailwindcss/colors')

module.exports = {
    presets: [
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    content: [
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Http/Livewire/**/*Table.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php'
    ],
    theme: {
        extend: {
            colors: {
                primary: colors.purple,
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms")({
            strategy: 'class',
        }),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
}
