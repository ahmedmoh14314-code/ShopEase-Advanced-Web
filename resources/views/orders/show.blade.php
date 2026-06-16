@extends('layouts.app')
@section('title', $order->order_number.' — ShopEase')

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
        <h2>Delivery details</h2>
        <p>{{ $order->customer_name }}</p>
        <p>{{ $order->customer_email }}</p>
        <p>{{ $order->phone }}</p>
        <p>{{ $order->shipping_address }}</p>
        <p class="muted">Payment: {{ $order->payment_method }}</p>
        @if($order->notes)<p><strong>Notes:</strong> {{ $order->notes }}</p>@endif
    </aside>
</div>
<p style="margin-top:14px"><a href="{{ route('orders.index') }}">&larr; Back to my orders</a></p>
@endsection
