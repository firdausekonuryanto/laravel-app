import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.js"],
            refresh: true,
        }),
    ],
    // --- PENAMBAHAN WAJIB UNTUK MENGHILANGKAN CORS ERROR ---
    server: {
        host: "192.168.18.15", // Pastikan host server Vite ini benar
        hmr: {
            host: "192.168.18.15", // Untuk Hot Module Reloading
            protocol: "ws",
        },
        cors: true, // Mengaktifkan CORS headers
    },
    // --------------------------------------------------------
});
