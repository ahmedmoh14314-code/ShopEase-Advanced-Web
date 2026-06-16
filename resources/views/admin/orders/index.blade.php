@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="page-head"><h1>Orders</h1></div>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Status</th><th>Total</th><th></th></tr></thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->items_count }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                <td>${{ number_format((float) $order->total, 2) }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}">Manage</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">No orders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:14px">{{ $orders->links() }}</div>
@endsection
