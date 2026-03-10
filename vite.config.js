import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/style.css',
                'resources/css/berhasil.css',
                'resources/css/cssAdmin/dashboard.css',
                'resources/css/cssAdmin/pemeriksaan.css',
                'resources/css/cssAdmin/data_lansia.css',
                'resources/css/cssAdmin/jadwal_posyandu.css',
                // resources js
                'resources/js/jsADMIN/dashboard.js',
                'resources/js/jsADMIN/pemeriksaan.js',
                'resources/js/jsADMIN/data_lansia.js',
                'resources/js/jsADMIN/jadwal_posyandu.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
