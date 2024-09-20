import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { createVuetify } from 'vuetify';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/main.js'],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        sourcemap: true
    },
});
