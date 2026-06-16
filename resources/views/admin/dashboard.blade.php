@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<section class="stats">
    @foreach($stats as $label => $value)
        <article class="card stat">
            <span class="muted">{{ $label }}</span>
            <strong>{{ $value }}</strong>
        </article>
    @endforeach
</section>

<div class="section-head"><h2>Latest orders</h2><a href="{{ route('admin.orders.index') }}">Manage orders</a></div>
<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Order</th><th>Customer</th><th>Status</th><th>Total</th><th></th></tr></thead>
        <tbody>
        @forelse($latestOrders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                <td>${{ number_format((float) $order->total, 2) }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}">Manage</a></td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">No orders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
