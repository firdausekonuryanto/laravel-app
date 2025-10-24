<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Transactions;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = DB::table('transactions')->join('users', 'transactions.user_id', '=', 'users.id')->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')->leftJoin('payment_methods', 'transactions.payment_method_id', '=', 'payment_methods.id')->select('transactions.*', 'users.name as user_name', 'customers.name as customer_name', 'payment_methods.name as payment_method_name')->orderBy('transactions.id', 'desc')->get();

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data relasi untuk dropdown
        $customers = DB::table('customers')->select('id', 'name')->get();
        $users = DB::table('users')->select('id', 'name')->get();
        $paymentMethods = DB::table('payment_methods')->select('id', 'name')->get();
        $products = DB::table('products')->select('id', 'name', 'price')->get();

        return view('transactions.create', compact('customers', 'users', 'paymentMethods', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required',
        'payment_method_id' => 'required',
        'products' => 'required|array|min:1',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    $transactionId = null; // definisikan di luar agar bisa diakses setelah transaction

    DB::transaction(function () use ($request, &$transactionId) {
        $totalPrice = 0;
        $totalQty = 0;
        $details = [];

        // Hitung total dan siapkan detail
        foreach ($request->products as $item) {
            $product = DB::table('products')->find($item['product_id']);
            if (!$product) continue;

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

        // Ambil nilai uang dibayar dari form
        $paidAmount = $request->paid_amount ?? $grandTotal;
        $changeAmount = max(0, $paidAmount - $grandTotal);

        $transactionId = DB::table('transactions')->insertGetId([
            'invoice_number' => 'INV/' . now()->format('Ym') . '/' . Str::random(8),
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

    return redirect()->route('transactions.show', $transactionId)
                     ->with('success', 'Transaction created successfully!');
}

    /**
     * Display the specified resource.
     */
    public function show(Transactions $transaction)
    {
        $details = DB::table('transaction_details')->join('products', 'transaction_details.product_id', '=', 'products.id')->where('transaction_id', $transaction->id)->select('transaction_details.*', 'products.name as product_name')->get();

        return view('transactions.show', compact('transaction', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transactions $transaction)
    {
        $customers = DB::table('customers')->get();
        $users = DB::table('users')->get();
        $paymentMethods = DB::table('payment_methods')->get();

        return view('transactions.edit', compact('transaction', 'customers', 'users', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transactions $transaction)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'total_qty' => 'required|numeric',
            'total_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'grand_total' => 'required|numeric',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transactions $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully!');
    }

    /**
     * Generate dummy transactions (seeder-style function)
     */
    public function run()
    {
        DB::transaction(function () {
            $faker = Faker::create('id_ID');

            $customerIds = DB::table('customers')->pluck('id')->toArray();
            $userIds = DB::table('users')->pluck('id')->toArray();
            $paymentMethodIds = DB::table('payment_methods')->pluck('id')->toArray();
            $products = DB::table('products')->get();

            $allTransactionDetails = [];
            $numberOfTransactions = 20;

            for ($i = 0; $i < $numberOfTransactions; $i++) {
                $totalPrice = 0;
                $totalQty = 0;
                $createdAt = now()->subDays($faker->numberBetween(1, 60));

                if ($products->isEmpty()) {
                    continue;
                }

                $selectedProducts = $products->random($faker->numberBetween(1, min(5, $products->count())));

                $currentTransactionDetails = [];
                foreach ($selectedProducts as $product) {
                    $quantity = $faker->numberBetween(1, 10);
                    $subtotal = $quantity * $product->price;

                    $currentTransactionDetails[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ];

                    $totalPrice += $subtotal;
                    $totalQty += $quantity;
                }

                $discount = $faker->randomElement([0, 5000, 10000, 0, 0]);
                $tax = 0;
                $grandTotal = max(0, $totalPrice - $discount + $tax);

                $transactionData = [
                    'invoice_number' => 'INV/' . now()->format('Ym') . '/' . Str::random(8),
                    'customer_id' => $faker->randomElement(array_merge($customerIds, [null])),
                    'user_id' => $faker->randomElement($userIds),
                    'payment_method_id' => $faker->randomElement($paymentMethodIds),
                    'total_qty' => $totalQty,
                    'total_price' => $totalPrice,
                    'discount' => $discount,
                    'tax' => $tax,
                    'grand_total' => $grandTotal,
                    'paid_amount' => $grandTotal,
                    'change_amount' => 0,
                    'status' => 'paid',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];

                $transactionId = DB::table('transactions')->insertGetId($transactionData);

                foreach ($currentTransactionDetails as $detail) {
                    $detail['transaction_id'] = $transactionId;
                    $allTransactionDetails[] = $detail;
                }
            }

            if (!empty($allTransactionDetails)) {
                DB::table('transaction_details')->insert($allTransactionDetails);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Dummy transactions generated successfully!');
    }
}
