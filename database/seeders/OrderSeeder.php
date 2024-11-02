<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'user_id' => 1,
                'order_number' => 'ORD-' . Str::random(10),
                'total_amount' => 649.98,
                'status' => 'success',
                'checkout_date' => now(),
                'details' => [
                    [
                        'product_id' => 1,
                        'quantity' => 1,
                        'price' => 599.99
                    ],
                    [
                        'product_id' => 2,
                        'quantity' => 2,
                        'price' => 29.99
                    ]
                ]
            ],
            [
                'user_id' => 2,
                'order_number' => 'ORD-' . Str::random(10),
                'total_amount' => 29.99,
                'status' => 'success',
                'checkout_date' => now()->subDays(1),
                'details' => [
                    [
                        'product_id' => 2,
                        'quantity' => 1,
                        'price' => 29.99
                    ]
                ]
            ],
            [
                'user_id' => 3,
                'order_number' => 'ORD-' . Str::random(10),
                'total_amount' => 49.99,
                'status' => 'processing',
                'checkout_date' => now()->subDays(2),
                'details' => [
                    [
                        'product_id' => 3,
                        'quantity' => 1,
                        'price' => 49.99
                    ]
                ]
            ],
            [
                'user_id' => 4,
                'order_number' => 'ORD-' . Str::random(10),
                'total_amount' => 49.99,
                'status' => 'pending',
                'checkout_date' => now()->subDays(3),
                'details' => [
                    [
                        'product_id' => 3,
                        'quantity' => 1,
                        'price' => 49.99
                    ]
                ]
            ],
            [
                'user_id' => 5,
                'order_number' => 'ORD-' . Str::random(10),
                'total_amount' => 599.99,
                'status' => 'success',
                'checkout_date' => now()->subDays(4),
                'details' => [
                    [
                        'product_id' => 1,
                        'quantity' => 1,
                        'price' => 599.99
                    ]
                ]
            ],
        ];

        foreach ($orders as $orderData) {
            $details = $orderData['details'];
            unset($orderData['details']);

            $order = Order::create($orderData);

            foreach ($details as $detail) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price']
                ]);
            }
        }
    }
}
