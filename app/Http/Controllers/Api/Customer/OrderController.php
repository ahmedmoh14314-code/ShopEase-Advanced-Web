<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

// Authenticated customer order history and order details (owner only).
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function show(Request $request, Order $order)
    {
        // only the owner can see their own order
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'You do not have access to this order.');
        }

        $order->load('items');

        return response()->json([
            'order' => $order,
        ]);
    }
}
