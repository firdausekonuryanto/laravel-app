@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Transaction Detail</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Invoice: <strong>{{ $transaction->invoice_number }}</strong></h5>
                <p><strong>Customer:</strong> {{ $transaction->customer->name ?? '-' }}</p>
                <p><strong>Cashier (User):</strong> {{ $transaction->user->name ?? '-' }}</p>
                <p><strong>Payment Method:</strong> {{ $transaction->paymentMethod->name ?? '-' }}</p>
                <p><strong>Total Quantity:</strong> {{ $transaction->total_qty }}</p>
                <p><strong>Total Price:</strong> Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                <p><strong>Discount:</strong> Rp{{ number_format($transaction->discount, 0, ',', '.') }}</p>
                <p><strong>Tax:</strong> Rp{{ number_format($transaction->tax, 0, ',', '.') }}</p>
                <p><strong>Grand Total:</strong>
                    <span class="text-success fw-bold">
                        Rp{{ number_format($transaction->grand_total, 0, ',', '.') }}
                    </span>
                </p>

                {{-- 🟢 Tambahan baru --}}
                <p><strong>Amount Paid:</strong>
                    Rp{{ number_format($transaction->paid_amount ?? 0, 0, ',', '.') }}
                </p>
                <p><strong>Change (Kembalian):</strong>
                    <span class="text-primary">
                        Rp{{ number_format($transaction->change_amount ?? 0, 0, ',', '.') }}
                    </span>
                </p>

                <p><strong>Status:</strong>
                    <span class="badge bg-success">{{ strtoupper($transaction->status) }}</span>
                </p>
                <p><strong>Date:</strong> {{ $transaction->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <h4>Purchased Products</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $i => $detail)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $detail->product_name }}</td>
                        <td>Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No products found for this transaction.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-3">← Back to List</a>
    </div>
@endsection
