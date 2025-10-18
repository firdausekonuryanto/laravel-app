<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- PANGGIL VITE HANYA UNTUK JAVASCRIPT --}}
    @vite(['resources/js/app.js'])

    {{-- Bootstrap CSS (via CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>@yield('title', config('app.name', 'Laravel App'))</title>
</head>

<body>
    <main class="py-4">
        @yield('content')
    </main>

    {{-- Bootstrap JS (via CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    {{-- ... di dalam tag <body>, di bawah Bootstrap JS ... --}}

    {{-- BARU: Tambahkan Listener Notifikasi --}}
    <script type="module">
        // Pastikan window.Echo sudah diinisialisasi di resources/js/app.js (atau bootstrap.js)
        if (window.Echo) {
            console.log("Echo Active: Listening for new products.");

            // Channel dan Event Name harus SAMA PERSIS dengan ProductCreated.php
            window.Echo.channel('products-channel')
                .listen('.new-product-added', (e) => {
                    console.log('Event Received:', e.product);

                    let message = `🎉 Produk Baru: ${e.product.name} (Stok: ${e.product.stock}) telah ditambahkan!`;

                    // Tampilkan notifikasi
                    alert(message);
                })
                .error((error) => {
                    console.error("Reverb Listener Error:", error);
                });
        } else {
            console.error("Laravel Echo is not defined.");
        }
    </script>

    {{-- Bootstrap JS (via CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"        
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    @yield('scripts')
</body>

</html>
{{-- Listener JS (Anda bisa pindahkan listener ke sini atau biarkan di app.js) --}}
@yield('scripts')
</body>

</html>
