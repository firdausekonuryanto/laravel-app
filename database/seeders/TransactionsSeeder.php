<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

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
                $grandTotal = max(0, ($totalPrice - $discount) + $tax); 

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
    }
}