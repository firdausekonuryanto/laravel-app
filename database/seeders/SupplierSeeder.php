<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [];
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            $suppliers[] = [
                'name' => 'PT ' . $faker->company,
                'contact' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('suppliers')->insert($suppliers);
    }
}
