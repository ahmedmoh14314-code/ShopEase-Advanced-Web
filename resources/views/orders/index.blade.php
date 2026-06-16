@extends('layouts.app')
@section('title', 'My Orders — ShopEase')

@section('content')
<div class="page-head"><h1>My Orders</h1></div>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Order</th><th>Date</th><th>Items</th><th>Status</th><th>Total</th><th></th></tr></thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('M j, Y') }}</td>
                <td>{{ $order->items_count }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                <td>${{ number_format((float) $order->total, 2) }}</td>
                <td><a href="{{ route('orders.show', $order) }}">Details</a></td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">You have not placed any orders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:14px">{{ $orders->links() }}</div>
@endsection
