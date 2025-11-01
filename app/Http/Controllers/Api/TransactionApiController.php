<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Events\TransactionCreated;

class TransactionApiController extends Controller
{
    // Versi Sederhana Tanpa Map (Output JSON akan memiliki objek 'customer' dan 'payment_method')
    public function index()
    {
        $transactions = Transactions::with([
            'customer:id,name',
            'paymentMethod:id,name',
            'user:id,name',
            // 👇 Batasi kolom pada relasi details dan product
            'details' => function ($q) {
                $q->select(['id', 'transaction_id', 'product_id', 'quantity', 'price', 'subtotal'])->with(['product:id,name']);
            },
        ])
            ->select(['id', 'invoice_number', 'customer_id', 'payment_method_id', 'user_id', 'total_qty', 'grand_total', 'paid_amount', 'change_amount', 'status', 'total_price', 'discount', 'created_at'])
            ->orderByDesc('created_at')
            ->paginate(20);
            // ->limit(2)
            // ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    public function create()
    {
        $customers = DB::table('customers')->select('id', 'name')->get();
        $users = DB::table('users')->select('id', 'name')->get();
        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();
        $products = DB::table('products')->select('id', 'name', 'price')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'customers' => $customers,
                'users' => $users,
                'paymentMethods' => $paymentMethods,
                'products' => $products,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);
        /** @var \App\Models\Transactions|null $transaction */

        $transaction = null;

        DB::transaction(function () use ($request, &$transaction) {
            $totalPrice = 0;
            $totalQty = 0;
            $details = [];

            // Hitung total harga & qty
            foreach ($request->products as $item) {
                $product = DB::table('products')->find($item['product_id']);
                if (!$product) {
                    continue;
                }

                $subtotal = $product->price * $item['quantity'];

                $details[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $totalPrice += $subtotal;
                $totalQty += $item['quantity'];
            }

            $discount = $request->discount ?? 0;
            $tax = 0;
            $grandTotal = max(0, $totalPrice - $discount + $tax);

            $paidAmount = $request->paid_amount ?? $grandTotal;
            $changeAmount = max(0, $paidAmount - $grandTotal);

            // ✅ Gunakan model Eloquent
            $transaction = Transactions::create([
                'invoice_number' => 'INV/' . now()->format('Ym') . '/' . Str::upper(Str::random(8)),
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'payment_method_id' => $request->payment_method_id,
                'total_qty' => $totalQty,
                'total_price' => $totalPrice,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'status' => 'paid',
            ]);

            // Simpan detail produk
            foreach ($details as $detail) {
                $detail['transaction_id'] = $transaction->id;
                DB::table('transaction_details')->insert($detail);
            }
        });

        // Ambil detail produk untuk response
        $details = DB::table('transaction_details')->join('products', 'transaction_details.product_id', '=', 'products.id')->select('transaction_details.*', 'products.name as product_name')->where('transaction_id', $transaction->id)->get();

        // ✅ Kirim notifikasi realtime (Eloquent model dikirim)
        event(new TransactionCreated($transaction));

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
            'data' => [
                'transaction' => $transaction,
                'details' => $details,
            ],
        ]);
    }
    public function _store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        $transactionId = null;

        DB::transaction(function () use ($request, &$transactionId) {
            $totalPrice = 0;
            $totalQty = 0;
            $details = [];

            // Hitung total harga & qty
            foreach ($request->products as $item) {
                $product = DB::table('products')->find($item['product_id']);
                if (!$product) {
                    continue;
                }

                $subtotal = $product->price * $item['quantity'];

                $details[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $totalPrice += $subtotal;
                $totalQty += $item['quantity'];
            }

            $discount = $request->discount ?? 0;
            $tax = 0;
            $grandTotal = max(0, $totalPrice - $discount + $tax);

            $paidAmount = $request->paid_amount ?? $grandTotal;
            $changeAmount = max(0, $paidAmount - $grandTotal);

            // Simpan transaksi utama
            $transactionId = DB::table('transactions')->insertGetId([
                'invoice_number' => 'INV/' . now()->format('Ym') . '/' . Str::upper(Str::random(8)),
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'payment_method_id' => $request->payment_method_id,
                'total_qty' => $totalQty,
                'total_price' => $totalPrice,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan detail produk
            foreach ($details as $detail) {
                $detail['transaction_id'] = $transactionId;
                DB::table('transaction_details')->insert($detail);
            }
        });

        // Ambil data transaksi lengkap untuk dikirim ke client
        $transaction = DB::table('transactions')->where('id', $transactionId)->first();

        $details = DB::table('transaction_details')->join('products', 'transaction_details.product_id', '=', 'products.id')->select('transaction_details.*', 'products.name as product_name')->where('transaction_id', $transactionId)->get();

        // Kirim notifikasi realtime
        event(new TransactionCreated($transaction));

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
            'data' => [
                'transaction' => $transaction,
                'details' => $details,
            ],
        ]);
    }
}
