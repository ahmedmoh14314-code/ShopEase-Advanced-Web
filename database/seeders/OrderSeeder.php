<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all()->keyBy('email');
        $products = Product::all()->keyBy('slug');

        $orders = [
            [
                'order_number' => 'ORD-1001',
                'user_email'   => 'customer@shopease.com',
                'status'       => 'delivered',
                'city'         => 'Kadikoy',
                'date'         => '2026-05-12 10:00:00',
                'items'        => [
                    ['slug' => 'smart-watch-series-8', 'qty' => 1],
                    ['slug' => 'hydrating-face-cream', 'qty' => 1],
                    ['slug' => 'plush-teddy-bear',     'qty' => 1],
                ],
            ],
            [
                'order_number' => 'ORD-1002',
                'user_email'   => 'sarah@example.com',
                'status'       => 'processing',
                'city'         => 'Besiktas',
                'date'         => '2026-05-14 10:00:00',
                'items'        => [
                    ['slug' => 'wireless-headphones', 'qty' => 1],
                    ['slug' => 'coffee-maker',        'qty' => 1],
                ],
            ],
            [
                'order_number' => 'ORD-1003',
                'user_email'   => 'michael@example.com',
                'status'       => 'pending',
                'city'         => 'Sisli',
                'date'         => '2026-05-15 10:00:00',
                'items'        => [
                    ['slug' => 'iphone-15-pro',      'qty' => 1],
                    ['slug' => 'running-shoes-flex', 'qty' => 2],
                ],
            ],
            [
                'order_number' => 'ORD-1004',
                'user_email'   => 'emily@example.com',
                'status'       => 'shipped',
                'city'         => 'Uskudar',
                'date'         => '2026-05-13 10:00:00',
                'items'        => [
                    ['slug' => 'minimalist-sofa',      'qty' => 1],
                    ['slug' => 'hydrating-face-cream', 'qty' => 1],
                ],
            ],
            [
                'order_number' => 'ORD-1005',
                'user_email'   => 'customer@shopease.com',
                'status'       => 'cancelled',
                'city'         => 'Fatih',
                'date'         => '2026-05-10 10:00:00',
                'items'        => [
                    ['slug' => 'robot-vacuum', 'qty' => 1],
                ],
            ],
            [
                'order_number' => 'ORD-1006',
                'user_email'   => 'sarah@example.com',
                'status'       => 'delivered',
                'city'         => 'Bakirkoy',
                'date'         => '2026-05-08 10:00:00',
                'items'        => [
                    ['slug' => 'makeup-essentials-set', 'qty' => 2],
                    ['slug' => 'atomic-habits-journal', 'qty' => 3],
                ],
            ],
            [
                'order_number' => 'ORD-1007',
                'user_email'   => 'michael@example.com',
                'status'       => 'processing',
                'city'         => 'Taksim',
                'date'         => '2026-05-16 10:00:00',
                'items'        => [
                    ['slug' => 'adjustable-dumbbell-set', 'qty' => 1],
                    ['slug' => 'urban-travel-backpack',   'qty' => 2],
                ],
            ],
            [
                'order_number' => 'ORD-1008',
                'user_email'   => 'emily@example.com',
                'status'       => 'delivered',
                'city'         => 'Atasehir',
                'date'         => '2026-05-06 10:00:00',
                'items'        => [
                    ['slug' => 'white-classic-sneakers', 'qty' => 1],
                    ['slug' => 'summer-floral-dress',    'qty' => 1],
                ],
            ],
        ];

        foreach ($orders as $o) {
            $user = $users[$o['user_email']] ?? null;
            if (! $user) {
                continue;
            }

            $lines = [];
            $subtotal = 0;

            foreach ($o['items'] as $item) {
                $product = $products[$item['slug']] ?? null;
                if (! $product) {
                    continue;
                }
                $lineTotal = (float) $product->price * $item['qty'];
                $subtotal += $lineTotal;
                $lines[] = [
                    'product' => $product,
                    'qty'     => $item['qty'],
                    'total'   => $lineTotal,
                ];
            }

            if (empty($lines)) {
                continue;
            }

            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => $o['order_number'],
                'customer_name'    => $user->name,
                'customer_email'   => $user->email,
                'phone'            => $user->phone ?? '+90 555 000 0000',
                'shipping_address' => $o['city'].', Istanbul, Turkey',
                'notes'            => null,
                'subtotal'         => $subtotal,
                'total'            => $subtotal,
                'status'           => $o['status'],
                'payment_method'   => 'cash_on_delivery',
            ]);

            $order->created_at = $o['date'];
            $order->updated_at = $o['date'];
            $order->save();

            foreach ($lines as $line) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $line['product']->id,
                    'product_name' => $line['product']->name,
                    'quantity'     => $line['qty'],
                    'price'        => $line['product']->price,
                    'total'        => $line['total'],
                ]);
            }
        }
    }
}
