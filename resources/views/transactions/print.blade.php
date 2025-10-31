<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan #{{ $transaction->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #fff;
            margin: 20px;
        }

        .nota {
            max-width: 480px;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h4 {
            margin: 0;
        }

        table {
            width: 100%;
            font-size: 14px;
        }

        th,
        td {
            padding: 4px;
            text-align: left;
        }

        .total {
            border-top: 1px dashed #000;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="nota">
        <div class="header">
            <h4><strong>Toko Sejahtera</strong></h4>
            <small>Jl. Raya Banyuwangi No. 88</small><br>
            <small>Telp: 0812-3456-7890</small>
            <hr>
        </div>

        <p>
            <strong>No. Nota:</strong> {{ $transaction->id }} <br>
            <strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }} <br>
            <strong>Pelanggan:</strong> {{ $transaction->customer->name ?? '-' }}
        </p>

        <table class="table table-borderless">
            <thead>
                <tr class="border-bottom border-dark">
                    <th>Produk</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->details as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-end">{{ $item->qty }}</td>
                        <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($item->qty * $item->price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total">
                    <td colspan="3" class="text-end">Total</td>
                    <td class="text-end">{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end">Bayar</td>
                    <td class="text-end">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end">Kembali</td>
                    <td class="text-end">
                        {{ number_format($transaction->paid_amount - $transaction->total_price, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="text-center mt-3">
            <small>Terima kasih telah berbelanja!</small><br>
            <small>Barang yang sudah dibeli tidak dapat dikembalikan.</small>
        </div>

        <div class="text-center no-print mt-3">
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer"></i> Print Nota
            </button>
        </div>
    </div>

</body>

</html>
