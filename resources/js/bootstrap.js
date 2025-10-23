import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// --- Wajib Ditambahkan untuk Reverb ---
import Echo from "laravel-echo";

import Pusher from "pusher-js"; // Import client WebSocket
window.Pusher = Pusher; // Definisikan secara global untuk Echo
// -------------------------------------
// resources/js/bootstrap.js
// resources/js/bootstrap.js

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,

    // --- PERUBAHAN KRITIS INI WAJIB DITAMBAHKAN ---
    forceTLS: false,
    // ---------------------------------------------
});
