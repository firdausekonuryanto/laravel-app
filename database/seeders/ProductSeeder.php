<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Laptop Gaming Pro',
            'description' => 'Laptop dengan spesifikasi tinggi untuk gaming dan desain.',
            'price' => 15000000.00,
            'stock' => 15,
        ]);

        Product::create([
            'name' => 'Keyboard Mekanikal',
            'description' => 'Keyboard dengan switch biru, cocok untuk mengetik.',
            'price' => 750000.00,
            'stock' => 50,
        ]);
    }
}