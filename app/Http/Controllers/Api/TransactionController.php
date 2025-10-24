<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = DB::table('transactions as t')
            ->leftJoin('customers as c', 't.customer_id', '=', 'c.id')
            ->leftJoin('payment_methods as p', 't.payment_method_id', '=', 'p.id')
            ->select(
                't.id',
                't.invoice_number',
                'c.name as customer_name',
                'p.name as payment_method',
                't.total_qty',
                't.grand_total',
                't.paid_amount',
                't.change_amount',
                't.status',
                't.created_at'
            )
            ->orderByDesc('t.created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0'
        ]);

        return DB::transaction(function () use ($request) {
            $totalPrice = 0;
            $totalQty = 0;

            foreach ($request->items as $item) {
                $product = DB::table('products')->find($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                $totalPrice += $subtotal;
                $totalQty += $item['quantity'];
            }

            $discount = 0;
            $tax = 0;
            $grandTotal = ($totalPrice - $discount) + $tax;
            $change = $request->paid_amount - $grandTotal;

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
                'paid_amount' => $request->paid_amount,
                'change_amount' => $change,
                'status' => $request->paid_amount >= $grandTotal ? 'paid' : 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $details = [];
            foreach ($request->items as $item) {
                $product = DB::table('products')->find($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                $details[] = [
                    'transaction_id' => $transactionId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Update stok
                DB::table('products')
                    ->where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            DB::table('transaction_details')->insert($details);

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => [
                    'transaction_id' => $transactionId,
                    'grand_total' => $grandTotal,
                    'change' => $change
                ]
            ], 201);
        });
    }
}
