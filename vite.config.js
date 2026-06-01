import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/register.css",
                "resources/css/berhasil.css",
                "resources/css/sidebar.css",
                "resources/css/cssAdmin/dashboard.css",
                "resources/css/cssAdmin/pemeriksaan.css",
                "resources/css/cssAdmin/data_lansia.css",
                "resources/css/cssAdmin/data_petugas.css",
                "resources/css/cssAdmin/tambah_data_petugas.css",
                "resources/css/cssAdmin/skrining_utama.css",
                "resources/css/cssAdmin/pengaturan.css",
                "resources/css/cssAdmin/monitoring.css",
                "resources/css/cssAdmin/obat.css",
                "resources/css/welcome.css",
                //resources js
                "resources/js/register.js",
                "resources/js/jsADMIN/dashboard.js",
                "resources/js/jsADMIN/pemeriksaan.js",
                "resources/js/jsADMIN/data_lansia.js",
                "resources/js/jsADMIN/monitoring.js",
                "resources/js/jsADMIN/obat.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
