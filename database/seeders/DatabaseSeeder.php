<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        \App\Models\User::factory()->create([
//            'name' => 'Derar',
//            'email' => 'derar.dev@gmail.com',
//            'email_verified_at' => now(),
//            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//            'remember_token' => Str::random(10),
//        ]);
//        \App\Models\Branch::factory(2)->create();

//        \App\Models\Currency::factory(2)->create();
//        \App\Models\Quantity::factory(10)->create();
//        \App\Models\Category::factory(10)->create();
//        \App\Models\Trader::factory(10)->create();
        \App\Models\Product::factory(30)->create();
        \App\Models\ProductVariant::factory(100)->create();

//        \App\Models\WalletOperation::factory(1000)->create();
//        \App\Models\Expense::factory(50)->create();


    }
}
