import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    //     server: {
    //     host: "0.0.0.0",
    //     origin: 'http://192.168.100.13:5173', 
    //     hmr: {
    //         host: "192.168.100.13",
    //     },
    // },
});
