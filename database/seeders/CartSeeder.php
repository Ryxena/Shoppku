<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $carts = [
            [
                'user_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
            ],
            [
                'user_id' => 1,
                'product_id' => 3,
                'quantity' => 1,
            ],
            [
                'user_id' => 2,
                'product_id' => 2,
                'quantity' => 3,
            ],
            [
                'user_id' => 3,
                'product_id' => 1,
                'quantity' => 1,
            ],
            [
                'user_id' => 4,
                'product_id' => 3,
                'quantity' => 2,
            ],
        ];

        foreach ($carts as $cart) {
            Cart::create($cart);
        }

    }
}
