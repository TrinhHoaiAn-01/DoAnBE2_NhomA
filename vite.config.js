import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/bootstrap-5.3.8-dist/css/bootstrap.min.css',
                'resources/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js'
            ],
            refresh: true,
        }),
    ],
});
