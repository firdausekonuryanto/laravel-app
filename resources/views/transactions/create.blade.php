@extends('layouts.app')

@section('title', 'Create Transaction')

@section('content')
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }

        table.dataTable {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #333;
        }

        table.dataTable thead th {
            background-color: #222;
            color: #fff;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #1b1b1b;
        }

        table.dataTable tbody tr:nth-child(odd) {
            background-color: #141414;
        }

        table.dataTable tbody tr:hover {
            background-color: #2a2a2a;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            background-color: #1b1b1b;
            color: #e0e0e0;
            border: 1px solid #333;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #2a2a2a;
            color: #e0e0e0 !important;
            border: 1px solid #333;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #007bff !important;
            color: #fff !important;
        }

        .btn {
            border: none;
        }

        .btn-info {
            background-color: #007bff;
            color: white;
        }

        .btn-warning {
            background-color: #f39c12;
            color: white;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }
    </style>
    <div class="container-fluid mt-4 text-light">
        <h3 class="mb-4">🧾 Create Transaction</h3>

        <div class="row">
            {{-- =================== PRODUK LIST =================== --}}
            <div class="col-md-8">
                <div class="card bg-dark">
                    <div class="card-header">
                        <h5 class="mb-0 text-light">📦 Daftar Produk</h5>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>SKU</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                    <th width="150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- =================== KERANJANG =================== --}}
            <div class="col-md-4">
                <form action="{{ route('transactions.store') }}" method="POST" id="checkout-form" class="card bg-dark p-3">
                    @csrf
                    <h5 class="mb-3 text-light">🛍️ Keranjang</h5>

                    <table class="table table-dark table-sm align-middle" id="cart-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th style="width: 15%">Qty</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cart-body">
                            <tr>
                                <td colspan="4" class="text-center text-secondary">Belum ada produk</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <label class="form-label">Customer</label>
                        <select id="customer_id" name="customer_id" class="form-control border-0">
                            <option value="">-- Optional --</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}"
                                    {{ Str::lower($c->name) == 'pelanggan umum' ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select id="payment_method_id" name="payment_method_id" class="form-control border-0" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($paymentMethods as $pm)
                                <option value="{{ $pm->id }}" {{ Str::lower($pm->name) == 'cash' ? 'selected' : '' }}>
                                    {{ $pm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Diskon (Rp)</label>
                        <input type="number" name="discount" id="discount"
                            class="form-control bg-dark text-white border-0" value="0">
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Total Bayar (Rp)</label>
                        <input type="text" id="grand-total" class="form-control bg-dark text-white border-0" readonly
                            value="0">
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Uang Dibayar (Rp)</label>
                        <input type="number" name="paid_amount" id="paid-amount"
                            class="form-control bg-dark text-white border-0">
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Kembalian (Rp)</label>
                        <input type="text" id="change-amount" class="form-control bg-dark text-white border-0" readonly
                            value="0">
                    </div>

                    <button type="button" id="btnSaveTransaction" class="btn btn-primary w-100 mt-4 py-2">
                        💾 Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            // === DataTable Inisialisasi ===
            const table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transactions.dataProduct') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sku',
                        name: 'products.sku'
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'category_name',
                        name: 'product_categories.name'
                    },
                    {
                        data: 'price',
                        name: 'products.price'
                    },
                    {
                        data: 'stock',
                        name: 'products.stock',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'unit',
                        name: 'products.unit'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json"
                },
                createdRow: function(row) {
                    $(row).addClass('bg-dark text-white');
                }
            });

            // === Variabel dan Elemen DOM ===
            const CART_KEY = 'pos_cart';
            let cart = JSON.parse(localStorage.getItem(CART_KEY)) || [];

            const cartBody = $('#cart-body');
            const grandTotalInput = $('#grand-total');
            const paidInput = $('#paid-amount');
            const changeInput = $('#change-amount');
            const discountInput = $('#discount');

            // === Fungsi Utilitas ===
            const saveCart = () => localStorage.setItem(CART_KEY, JSON.stringify(cart));

            const formatRupiah = (num) =>
                'Rp' + (num || 0).toLocaleString('id-ID');

            const calculateTotal = () => {
                let total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
                const discount = parseFloat(discountInput.val()) || 0;
                total = Math.max(0, total - discount);
                grandTotalInput.val(total.toLocaleString('id-ID'));
                calculateChange();
            };

            const calculateChange = () => {
                const paid = parseFloat(paidInput.val() || 0);
                const total = parseFloat(grandTotalInput.val().replace(/\./g, '').replace(/,/g, '')) || 0;
                const change = paid - total;
                changeInput.val((change > 0 ? change : 0).toLocaleString('id-ID'));
            };

            const renderCart = () => {
                cartBody.empty();

                if (cart.length === 0) {
                    cartBody.html(
                        `<tr><td colspan="4" class="text-center text-secondary">Belum ada produk</td></tr>`);
                    grandTotalInput.val(0);
                    return;
                }

                cart.forEach(item => {
                    const subtotal = item.price * item.qty;
                    cartBody.append(`
                <tr>
                    <td>${item.name}</td>
                    <td>
                        <input type="number" min="1"
                            class="form-control form-control-sm bg-dark text-white border-0 qty-input"
                            data-id="${item.id}" value="${item.qty}">
                    </td>
                    <td>${formatRupiah(subtotal)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-item" data-id="${item.id}">
                            🗑
                        </button>
                    </td>
                </tr>
            `);
                });

                calculateTotal();
            };

            const addToCart = (id, name, price) => {
                const existing = cart.find(item => item.id == id);
                if (existing) existing.qty += 1;
                else cart.push({
                    id,
                    name,
                    price,
                    qty: 1
                });

                saveCart();
                renderCart();
                showToast(`${name} ditambahkan ke keranjang ✅`);
            };

            const showToast = (message) => {
                const toast = $(`<div class="position-fixed bottom-0 end-0 m-3 p-3 bg-success text-white rounded shadow">
            ${message}
        </div>`);
                $('body').append(toast);
                setTimeout(() => toast.fadeOut(500, () => toast.remove()), 2000);
            };

            // === Event Listener ===
            // Tambah ke keranjang
            $(document).on('click', '.add-to-cart', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const price = parseFloat($(this).data('price'));
                addToCart(id, name, price);
            });

            // Hapus item dari keranjang
            $(document).on('click', '.remove-item', function() {
                const id = $(this).data('id');
                cart = cart.filter(item => item.id != id);
                saveCart();
                renderCart();
            });

            // Ubah qty
            $(document).on('input', '.qty-input', function() {
                const id = $(this).data('id');
                const item = cart.find(i => i.id == id);
                if (item) {
                    item.qty = parseInt($(this).val()) || 1;
                    saveCart();
                    renderCart();
                }
            });

            // Ubah diskon / bayar
            discountInput.on('input', calculateTotal);
            paidInput.on('input', calculateChange);

            // Simpan transaksi
            $('#btnSaveTransaction').on('click', function() {
                if (cart.length === 0) {
                    alert('Keranjang masih kosong!');
                    return;
                }

                const data = {
                    user_id: 1, // sementara hardcoded
                    payment_method_id: $('[name="payment_method_id"]').val(),
                    customer_id: $('[name="customer_id"]').val(),
                    discount: discountInput.val() || 0,
                    paid_amount: paidInput.val() || 0,
                    products: cart.map(item => ({
                        product_id: item.id,
                        quantity: item.qty
                    }))
                };

                console.log('🟢 Data dikirim:', data);

                fetch('/transactions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify(data)
                    })
                    .then(async (res) => {
                        // Deteksi jika response bukan JSON (biasanya error HTML dari Laravel)
                        const text = await res.text();
                        try {
                            return JSON.parse(text);
                        } catch {
                            console.error('❌ Response bukan JSON:', text);
                            throw new Error('Server error');
                        }
                    })
                    .then(result => {
                        if (result.success) {
                            alert('✅ Transaksi berhasil disimpan!');
                            localStorage.removeItem(CART_KEY);
                            window.location.href = `/transactions/${result.transaction_id}`;
                        } else {
                            alert('⚠️ Gagal menyimpan transaksi: ' + (result.message ||
                                'Unknown error'));
                        }
                    })
                    .catch(err => {
                        console.error('🔥 Error:', err);
                        alert('Terjadi kesalahan pada server.');
                    });
            });


            // === Render awal ===
            renderCart();
        });
    </script>
@endpush
