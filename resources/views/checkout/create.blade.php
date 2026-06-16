@extends('layouts.app')
@section('title', 'Checkout — ShopEase')

@section('content')
<div class="page-head"><h1>Checkout</h1></div>

<div class="detail-grid">
    <form class="form-card" method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="customer_name">Full name</label>
                <input id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" required>
            </div>
            <div class="field">
                <label for="customer_email">Email</label>
                <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email) }}" required>
            </div>
            <div class="field full">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
            </div>
            <div class="field full">
                <label for="shipping_address">Shipping address</label>
                <textarea id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
            </div>
            <div class="field full">
                <label for="notes">Notes (optional)</label>
                <textarea id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
            </div>
            <div class="field full"><strong>Payment:</strong> Cash on delivery</div>
        </div>
        <button class="btn" type="submit" style="margin-top:12px">Place order</button>
    </form>

    <aside class="card summary">
        <h2>Order summary</h2>
        @foreach($cart->items as $item)
            <p>{{ $item->product->name ?? 'Product' }} &times; {{ $item->quantity }}
               <strong style="float:right">${{ number_format((float) $item->price * $item->quantity, 2) }}</strong></p>
        @endforeach
        <hr>
        <h3>Total <span style="float:right">${{ number_format($subtotal, 2) }}</span></h3>
    </aside>
</div>
@endsection
