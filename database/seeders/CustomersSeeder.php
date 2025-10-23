<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [];
        $faker = \Faker\Factory::create('id_ID');

        $customers[] = [
            'name' => 'Pelanggan Umum',
            'phone' => null,
            'email' => null,
            'address' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        for ($i = 0; $i < 10; $i++) {
            $customers[] = [
                'name' => $faker->name,
                'phone' => $faker->unique()->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('customers')->insert($customers);
    }
}
