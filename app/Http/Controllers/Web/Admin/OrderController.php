<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /** Admin action => resulting order status. */
    private const ACTIONS = [
        'approve' => 'processing',
        'reject' => 'cancelled',
        'completed' => 'delivered',
    ];

    public function index()
    {
        $orders = Order::withCount('items')->latest()->paginate(12);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items', 'user');

        return view('admin.orders.show', [
            'order' => $order,
            'actions' => array_keys(self::ACTIONS),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'action' => ['required', 'in:approve,reject,completed'],
        ]);

        $order->update(['status' => self::ACTIONS[$data['action']]]);

        return redirect()->route('admin.orders.show', $order)
            ->with('status', 'Order marked as '.$data['action'].'.');
    }
}
