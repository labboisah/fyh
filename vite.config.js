import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/welcome.css', 
                'resources/css/guest.css',
                // js 
                'resources/js/app.js',
                'resources/js/datatable.js',
                'resources/js/charts.js',
                'resources/js/address.js',
            ],
            refresh: true,
        }),
    ],
});
