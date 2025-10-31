<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Transactions;
use App\Models\Transaction; // Ubah 'Transactions' jika model Anda bernama 'Transaction'
use Yajra\DataTables\DataTables; // <--- TAMBAHKAN INI

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cukup tampilkan view, data akan diambil oleh getData()
        return view('transactions.index');
    }

    // --- TAMBAHKAN FUNGSI getData INI ---
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('transactions')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
                ->leftJoin('payment_methods', 'transactions.payment_method_id', '=', 'payment_methods.id')
                ->select(
                    'transactions.id', // Pastikan ID ada untuk tombol aksi
                    'transactions.invoice_number',
                    'customers.name as customer_name',
                    'users.name as user_name',
                    'transactions.total_qty',
                    'transactions.grand_total',
                    'transactions.status',
                )
                ->orderBy('transactions.id', 'desc');

            return Datatables::of($data)
                ->addIndexColumn() // Menambahkan kolom penomoran (opsional)
                ->editColumn('grand_total', function ($row) {
                    // Format grand_total menjadi mata uang
                    return number_format($row->grand_total, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    // Membuat tombol aksi (Show, Edit, Delete)
                    $showUrl = route('transactions.show', $row->id);
                    $editUrl = route('transactions.edit', $row->id);
                    $deleteUrl = route('transactions.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    $btn = "<a href='{$showUrl}' class='btn btn-info btn-sm'>Show</a> ";
                    $btn .= "<a href='{$editUrl}' class='btn btn-warning btn-sm'>Edit</a> ";
                    $btn .= "<form action='{$deleteUrl}' method='POST' style='display:inline-block'>
                                {$csrf}
                                {$method}
                                <button class='btn btn-danger btn-sm' onclick=\"return confirm('Delete this transaction?')\">Delete</button>
                            </form>";

                    return $btn;
                })
                ->rawColumns(['action']) // Pastikan kolom 'action' diizinkan mengandung HTML
                ->make(true);
        }
    }

    public function getDataProduct(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('products')->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')->select('products.id', 'products.sku', 'products.name', 'product_categories.name as category_name', 'products.price', 'products.stock', 'products.unit')->orderBy('products.id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('price', function ($row) {
                    return 'Rp' . number_format($row->price, 0, ',', '.');
                })
                ->editColumn('stock', function ($row) {
                    $badgeClass = $row->stock > 10 ? 'bg-success' : 'bg-danger';
                    return "<span class='badge {$badgeClass}'>{$row->stock}</span>";
                })
                ->addColumn('action', function ($row) {
                    // Tombol tambah ke keranjang
                    return "
                    <button
                        type='button'
                        class='btn btn-success btn-sm add-to-cart'
                        data-id='{$row->id}'
                        data-name='{$row->name}'
                        data-price='{$row->price}'>
                        ➕ Add to Cart
                    </button>
                ";
                })
                ->rawColumns(['action', 'stock'])
                ->make(true);
        }
    }

    public function create()
    {
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
        logger($request->all());

        $request->validate([
            'user_id' => 'required',
            'payment_method_id' => 'required',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $transactionId = null;

        DB::transaction(function () use ($request, &$transactionId) {
            $totalPrice = 0;
            $totalQty = 0;
            $details = [];

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

            foreach ($details as $detail) {
                $detail['transaction_id'] = $transactionId;
                DB::table('transaction_details')->insert($detail);
            }
        });

        // ✅ kirim response JSON supaya JS tahu transaksi sukses
        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully!',
            'transaction_id' => $transactionId,
        ]);
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

    public function print($id)
    {
        $transaction = Transactions::with(['customer', 'details.product'])->findOrFail($id);

        return view('transactions.print', compact('transaction'));
    }
}
