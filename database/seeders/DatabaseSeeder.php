<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $users = [];

        $users[] = [
            'username' => 'admin',
            'password' => Hash::make('password'),
            'name' => 'Administrator Toko',
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        for ($i = 0; $i < 9; $i++) {
            $name = $faker->name;
            $role = $faker->randomElement(['kasir', 'manajer']);
            $users[] = [
                'username' => strtolower(Str::slug($name, '')) . $faker->unique()->randomNumber(3),
                'password' => Hash::make('password'),
                'name' => $name,
                'role' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insert($users);
        $userIds = DB::table('users')->pluck('id')->toArray();

        $this->call([ProductCategorySeeder::class, SupplierSeeder::class, CustomersSeeder::class, PaymentMethodsSeeder::class, ProductSeeder::class, TransactionsSeeder::class]);
        $this->call(RolePermissionSeeder::class);

    }
}
