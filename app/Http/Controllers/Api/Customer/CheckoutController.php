<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Turns the authenticated customer's cart into an order (cash on delivery).
class CheckoutController extends Controller
{
    public function store(CheckoutRequest $request)
    {
        $user = $request->user();

        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        $data = $request->validated();

        // double-check stock before placing the order
        foreach ($cart->items as $line) {
            $product = $line->product;

            if (! $product || ! $product->is_active) {
                return response()->json([
                    'message' => 'A product in your cart is no longer available.',
                ], 422);
            }

            if ($line->quantity > $product->stock) {
                return response()->json([
                    'message' => "Not enough stock for '{$product->name}'.",
                    'product_id' => $product->id,
                    'available_stock' => $product->stock,
                ], 422);
            }
        }

        $order = DB::transaction(function () use ($cart, $data, $user) {
            $subtotal = 0;
            foreach ($cart->items as $line) {
                $subtotal += $line->price * $line->quantity;
            }

            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => $this->generateOrderNumber(),
                'customer_name'    => $data['customer_name'],
                'customer_email'   => $data['customer_email'],
                'phone'            => $data['phone'],
                'shipping_address' => $data['shipping_address'],
                'notes'            => $data['notes'] ?? null,
                'subtotal'         => $subtotal,
                'total'            => $subtotal,
                'status'           => 'pending',
                'payment_method'   => 'cash_on_delivery',
            ]);

            foreach ($cart->items as $line) {
                $product = $line->product;
                $lineTotal = $line->price * $line->quantity;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'quantity'     => $line->quantity,
                    'price'        => $line->price,
                    'total'        => $lineTotal,
                ]);

                // reduce stock
                $product->decrement('stock', $line->quantity);
            }

            $cart->items()->delete();

            return $order;
        });

        $order->load('items');

        return response()->json([
            'message' => 'Order placed successfully.',
            'order'   => $order,
        ], 201);
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
    }
}
