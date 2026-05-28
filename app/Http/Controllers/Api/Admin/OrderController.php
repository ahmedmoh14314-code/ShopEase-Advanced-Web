<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;

// Admin/manager order listing and status updates.
class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(15);

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order->load(['items', 'user']);

        return response()->json([
            'order' => $order,
        ]);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $order->update(['status' => $request->validated()['status']]);

        return response()->json([
            'message' => 'Order status updated.',
            'order'   => $order,
        ]);
    }
}
