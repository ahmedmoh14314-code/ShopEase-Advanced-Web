@extends('layouts.admin')
@section('title', 'Order '.$order->order_number)

@section('content')
<div class="page-head">
    <div>
        <h1>{{ $order->order_number }}</h1>
        <p class="muted">{{ $order->created_at->format('F j, Y g:i A') }}</p>
    </div>
    <span class="badge badge-{{ $order->status }}">{{ $order->status }}</span>
</div>

<div class="detail-grid">
    <section class="card" style="padding:16px">
        <h2>Items</h2>
        @foreach($order->items as $item)
            <p>{{ $item->product_name }} &times; {{ $item->quantity }}
               <strong style="float:right">${{ number_format((float) $item->total, 2) }}</strong></p>
        @endforeach
        <hr>
        <h3>Total <span style="float:right">${{ number_format((float) $order->total, 2) }}</span></h3>
    </section>

    <aside class="card" style="padding:16px">
        <h2>Customer</h2>
        <p>{{ $order->customer_name }}</p>
        <p>{{ $order->customer_email }}</p>
        <p>{{ $order->phone }}</p>
        <p>{{ $order->shipping_address }}</p>

        <h2 style="margin-top:18px">Actions</h2>
        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="actions">
            @csrf @method('PUT')
            <button class="btn btn-sm" name="action" value="approve" type="submit">Approve</button>
            <button class="btn btn-sm btn-danger" name="action" value="reject" type="submit">Reject</button>
            <button class="btn btn-sm btn-secondary" name="action" value="completed" type="submit">Completed</button>
        </form>
    </aside>
</div>
<p style="margin-top:14px"><a href="{{ route('admin.orders.index') }}">&larr; Back to orders</a></p>
@endsection
