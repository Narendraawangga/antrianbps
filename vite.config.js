import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [

        laravel({

            input: [

                // =========================================
                // CSS & JS UTAMA
                // =========================================
                'resources/css/app.css',
                'resources/js/app.js',


                // =========================================
                // LAYOUT
                // =========================================
                'resources/css/layouts/navbar-admin.css',

                'resources/css/layouts/sidebar-admin.css',
                'resources/css/layouts/sidebar-petugas.css',

                'resources/css/layouts/petugas.css',
                'resources/css/layouts/admin.css',


                // =========================================
                // DASHBOARD ADMIN
                // =========================================
                'resources/css/admin/dashboard.css',
                'resources/js/admin/dashboard.js',
                'resources/css/admin/jadwal.css',
                'resources/css/admin/antrean.css',


                // =========================================
                // DASHBOARD PETUGAS
                // =========================================
                'resources/css/petugas/dashboard.css',
                'resources/js/petugas/dashboard.js',


                // =========================================
                // DISPLAY ANTREAN
                // =========================================
                'resources/css/display/index.css',
                'resources/js/display/index.js',

            ],

            refresh: true,

            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],

        }),

        tailwindcss(),
    ],

    server: {
        watch: {
            ignored: [
                '**/storage/framework/views/**',
            ],
        },
    },
});