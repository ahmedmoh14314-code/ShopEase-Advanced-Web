<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer@shopease.com')->first();
        if (! $customer) {
            return;
        }

        $items = [
            'smart-watch-series-8' => 1,
            'wireless-headphones'  => 1,
            'plush-teddy-bear'     => 2,
        ];

        $products = Product::whereIn('slug', array_keys($items))->get();
        if ($products->isEmpty()) {
            return;
        }

        $cart = Cart::firstOrCreate(['user_id' => $customer->id]);

        foreach ($products as $product) {
            $qty = $items[$product->slug] ?? 1;
            CartItem::updateOrCreate(
                [
                    'cart_id'    => $cart->id,
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => $qty,
                    'price'    => $product->price,
                ]
            );
        }
    }
}
