import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/account-manager-style.css',
                'resources/css/product-style.css',
                'resources/css/orders-style.css',
            ],
            refresh: true,
        }),
    ],
});