<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function currentCart(Request $request): Cart
    {
        return Cart::firstOrCreate(['user_id' => $request->user()->id]);
    }

    public function create(Request $request)
    {
        $cart = $this->currentCart($request);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
        }

        $subtotal = $cart->items->sum(fn ($item) => (float) $item->price * $item->quantity);

        return view('checkout.create', compact('cart', 'subtotal'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = $this->currentCart($request);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('status', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($cart, $data, $request) {
            $subtotal = 0;
            foreach ($cart->items as $item) {
                $subtotal += (float) $item->price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'phone' => $data['phone'],
                'shipping_address' => $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => 'pending',
                'payment_method' => 'cash_on_delivery',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Product',
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => (float) $item->price * $item->quantity,
                ]);

                if ($item->product) {
                    $item->product->decrement('stock', min($item->quantity, $item->product->stock));
                }
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('status', 'Order placed successfully!');
    }
}
