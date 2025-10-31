<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionsSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::transaction(function () {
            $faker = Faker::create('id_ID');

            $customerIds = DB::table('customers')->pluck('id')->toArray();
            $userIds = DB::table('users')->pluck('id')->toArray();
            $paymentMethodIds = DB::table('payment_methods')->pluck('id')->toArray();
            $products = DB::table('products')->get();

            if ($products->isEmpty()) {
                $this->command->warn('⚠️ Tidak ada produk di tabel products. Seeder dihentikan.');
                return;
            }

            $allTransactionDetails = [];

            // Loop setiap hari di tahun 2025
            $startDate = Carbon::create(2025, 1, 1);
            $endDate = Carbon::create(2025, 12, 31);

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // Jumlah transaksi per hari (1–3 transaksi)
                $transactionsPerDay = $faker->numberBetween(1, 3);

                for ($t = 0; $t < $transactionsPerDay; $t++) {
                    $totalPrice = 0;
                    $totalQty = 0;
                    $createdAt = $date->copy()->setTime(
                        $faker->numberBetween(8, 20),  // jam acak 08:00–20:00
                        $faker->numberBetween(0, 59)
                    );

                    $selectedProducts = $products->random($faker->numberBetween(1, min(5, $products->count())));
                    $currentTransactionDetails = [];

                    foreach ($selectedProducts as $product) {
                        $quantity = $faker->numberBetween(1, 5);
                        $price = (int) $product->price;
                        $subtotal = $quantity * $price;

                        $currentTransactionDetails[] = [
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'price' => $price,
                            'subtotal' => $subtotal,
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ];

                        $totalPrice += $subtotal;
                        $totalQty += $quantity;
                    }

                    $discount = $faker->randomElement([0, 2000, 5000, 10000]);
                    $tax = 0;
                    $grandTotal = max(0, ($totalPrice - $discount) + $tax);
                    $paidAmount = $grandTotal;
                    $changeAmount = 0;

                    $transactionData = [
                        'invoice_number' => 'INV/' . $date->format('Ym') . '/' . Str::upper(Str::random(8)),
                        'customer_id' => $faker->randomElement(array_merge($customerIds, [null])),
                        'user_id' => $faker->randomElement($userIds),
                        'payment_method_id' => $faker->randomElement($paymentMethodIds),
                        'total_qty' => $totalQty,
                        'total_price' => $totalPrice,
                        'discount' => $discount,
                        'tax' => $tax,
                        'grand_total' => $grandTotal,
                        'paid_amount' => $paidAmount,
                        'change_amount' => $changeAmount,
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
            }

            if (!empty($allTransactionDetails)) {
                DB::table('transaction_details')->insert($allTransactionDetails);
            }

            $this->command->info('✅ Transaksi tahun 2025 berhasil di-generate untuk setiap hari!');
        });
    }
}
