import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// --- Wajib Ditambahkan untuk Reverb ---
import Echo from "laravel-echo";

import Pusher from "pusher-js"; // Import client WebSocket
window.Pusher = Pusher; // Definisikan secara global untuk Echo
// -------------------------------------
// resources/js/bootstrap.js
window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,

    // Pastikan IP dan Port sesuai
    wsHost: "192.168.18.15", // IP Anda (Sama dengan host Laravel)
    wsPort: 8002, // Port Reverb yang baru (8002)
    wssPort: 8002, // Port Reverb yang baru (8002)
    // ... konfigurasi lainnya
});

// Catatan: Hapus atau komentari import "./echo"; jika file echo.js tidak ada.
// import "./echo";
