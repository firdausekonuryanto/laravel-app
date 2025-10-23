<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [['name' => 'Cash', 'description' => 'Pembayaran tunai/uang fisik.'], ['name' => 'Debit Card', 'description' => 'Pembayaran menggunakan kartu debit.'], ['name' => 'Credit Card', 'description' => 'Pembayaran menggunakan kartu kredit.'], ['name' => 'QRIS', 'description' => 'Pembayaran melalui QR Code statis/dinamis.'], ['name' => 'Transfer Bank', 'description' => 'Pembayaran melalui transfer antar bank.']];

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 5; $i++) {
            $methods[] = [
                'name' => 'E-Wallet ' . ($i + 1),
                'description' => $faker->sentence(4),
            ];
        }

        $data = array_map(function ($item) {
            return array_merge($item, ['created_at' => now(), 'updated_at' => now()]);
        }, $methods);

        DB::table('payment_methods')->insert($data);
    }
}
