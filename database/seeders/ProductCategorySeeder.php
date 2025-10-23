<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [];
        $faker = \Faker\Factory::create('id_ID');

        $starterCategories = ['Makanan Ringan', 'Minuman Dingin', 'Peralatan Mandi', 'Alat Tulis', 'Pembersih Rumah', 'Kopi & Teh', 'Rokok & Korek', 'Obat-obatan', 'Frozen Food', 'Bumbu Dapur'];

        foreach ($starterCategories as $name) {
            $categories[] = [
                'name' => $name,
                'description' => $faker->sentence(5),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        for ($i = 0; $i < 5; $i++) {
            $categories[] = [
                'name' => $faker->unique()->word() . ' Lainnya',
                'description' => $faker->sentence(5),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('product_categories')->insert($categories);
    }
}
