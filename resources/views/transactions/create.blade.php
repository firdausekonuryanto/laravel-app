@extends('layouts.app')

@section('title', 'Create Transaction')

@section('content')
    <div class="container mt-4">
        <h3>Create Transaction</h3>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
            @csrf

            {{-- 🟢 Customer --}}
            <div class="mb-3">
                <label>Customer</label>
                <select name="customer_id" class="form-control">
                    <option value="">-- Optional --</option>
                    @foreach ($customers as $c)
                        <option value="{{ $c->id }}" {{ Str::lower($c->name) == 'pelanggan umum' ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 🟢 User --}}
            <div class="mb-3">
                <label>User</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Select User --</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}"
                            {{ Str::lower($u->name) == 'administrator toko' ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 🟢 Payment Method --}}
            <div class="mb-3">
                <label>Payment Method</label>
                <select name="payment_method_id" class="form-control" required>
                    <option value="">-- Select Payment Method --</option>
                    @foreach ($paymentMethods as $pm)
                        <option value="{{ $pm->id }}" {{ Str::lower($pm->name) == 'cash' ? 'selected' : '' }}>
                            {{ $pm->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr>
            <h5>Products</h5>

            <div id="product-list">
                <div class="product-item mb-3">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label>Product</label>
                            <select name="products[0][product_id]" class="form-control product-select" required>
                                <option value="">-- Select Product --</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}">
                                        {{ $p->name }} (Rp{{ number_format($p->price) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Qty</label>
                            <input type="number" name="products[0][quantity]" class="form-control qty-input"
                                placeholder="Qty" min="1" required>
                        </div>

                        <div class="col-md-3">
                            <label>Subtotal</label>
                            <input type="text" class="form-control subtotal" readonly value="0">
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-product mt-4">X</button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="add-product" class="btn btn-secondary mb-3">+ Add Product</button>

            <div class="mb-3">
                <label>Discount (Rp)</label>
                <input type="number" name="discount" id="discount" class="form-control" placeholder="0" value="0">
            </div>

            <div class="mb-3">
                <label>Total Bayar (Rp)</label>
                <input type="text" id="grand-total" class="form-control" readonly value="0">
            </div>

            <div class="mb-3">
                <label>Uang Dibayar (Rp)</label>
                <input type="number" name="paid_amount" id="paid-amount" class="form-control"
                    placeholder="Masukkan uang dibayar">
            </div>

            <div class="mb-3">
                <label>Kembalian (Rp)</label>
                <input type="text" id="change-amount" class="form-control" readonly value="0">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let index = 1;

            // 🔹 Tambah produk
            document.getElementById('add-product').addEventListener('click', function() {
                const productList = document.getElementById('product-list');
                const newItem = document.querySelector('.product-item').cloneNode(true);

                newItem.querySelectorAll('select, input').forEach(el => {
                    el.name = el.name.replace(/\d+/, index);
                    el.value = '';
                });
                newItem.querySelector('.subtotal').value = 0;
                productList.appendChild(newItem);
                index++;
            });

            // 🔹 Hapus produk
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-product')) {
                    if (document.querySelectorAll('.product-item').length > 1) {
                        e.target.closest('.product-item').remove();
                        calculateTotal();
                    }
                }
            });

            // 🔹 Kalkulasi otomatis subtotal dan total
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('qty-input') || e.target.classList.contains(
                        'product-select')) {
                    const item = e.target.closest('.product-item');
                    const productSelect = item.querySelector('.product-select');
                    const qtyInput = item.querySelector('.qty-input');
                    const subtotalInput = item.querySelector('.subtotal');

                    const price = parseFloat(productSelect.options[productSelect.selectedIndex]?.dataset
                        .price || 0);
                    const qty = parseInt(qtyInput.value || 0);
                    const subtotal = price * qty;

                    subtotalInput.value = subtotal.toLocaleString('id-ID');
                    calculateTotal();
                }

                if (e.target.id === 'discount') {
                    calculateTotal();
                }
            });

            // 🔹 Event ketika user mengisi jumlah uang dibayar
            document.getElementById('paid-amount').addEventListener('input', function() {
                console.log("cek");
                calculateChange();
            });

            function calculateTotal() {
                let grandTotal = 0;
                document.querySelectorAll('.subtotal').forEach(el => {
                    // Ambil angka murni (hapus titik pemisah ribuan)
                    const val = parseFloat(el.value.replace(/\./g, '').replace(/,/g, '')) || 0;
                    grandTotal += val;
                });

                const discount = parseFloat(document.getElementById('discount').value || 0);
                grandTotal = Math.max(0, grandTotal - discount);

                document.getElementById('grand-total').value = grandTotal.toLocaleString('id-ID');

                // 🔹 Update kembalian juga
                calculateChange();
            }

            function calculateChange() {
                const paidRaw = document.getElementById('paid-amount').value;
                const paid = parseFloat(paidRaw.replace(/\./g, '').replace(/,/g, '')) || 0;

                const grandTotalText = document.getElementById('grand-total').value;
                const grandTotal = parseFloat(grandTotalText.replace(/\./g, '').replace(/,/g, '')) || 0;
                console.log("paid : ", paid);
                console.log("grandTotal : ", grandTotal);

                const change = paid - grandTotal;
                console.log("change : ", change);
                document.getElementById('change-amount').value = (change > 0 ? change : 0).toLocaleString('id-ID');
            }
        });
    </script>


@endsection
