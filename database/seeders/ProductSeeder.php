<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $categoryIds = DB::table('product_categories')->pluck('id')->toArray();
        $supplierIds = DB::table('suppliers')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();

        $products = [];

        for ($i = 0; $i < 20; $i++) { 
            // Gunakan integer, bukan float
            $price = $faker->numberBetween(5000, 50000);
            $costPrice = (int)($price * $faker->numberBetween(60, 80) / 100);
            $name = $faker->words(3, true);
            
            $products[] = [
                'category_id' => $faker->randomElement($categoryIds),
                'supplier_id' => $faker->randomElement(array_merge($supplierIds, [null, null])), 
                'name' => $name,
                'sku' => strtoupper(substr($name, 0, 3) . $faker->unique()->randomNumber(5)),
                'price' => $price,
                'cost_price' => $costPrice,
                'stock' => $faker->numberBetween(10, 200),
                'unit' => $faker->randomElement(['pcs', 'box', 'kg', 'liter']),
                'created_by' => $faker->randomElement($userIds),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}
