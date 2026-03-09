import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 'resources/js/app.js',
                'resources/css/style.css',
                'resources/css/berhasil.css',
                'resources/css/berhasil2.css',
                'resources/css/cssAdmin/dashboard.css',
                'resources/css/cssAdmin/pemeriksaan.css',
                'resources/css/cssAdmin/data_lansia.css',
                'resources/css/cssAdmin/data_petugas.css',
                'resources/css/cssAdmin/tambah_data_petugas.css',
                //resources js
                'resources/js/jsAdmin/dashboard.js',
                'resources/js/jsAdmin/pemeriksaan.js',
                'resources/js/jsAdmin/data_lansia.js',

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
