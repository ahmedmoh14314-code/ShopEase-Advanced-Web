@extends('layouts.app')
@section('title', 'Shopping Cart — ShopEase')

@section('content')
<div class="page-head"><h1>Shopping Cart</h1><a href="{{ route('products.index') }}">Continue shopping</a></div>

@forelse($cart->items as $item)
    @php
        $p = $item->product;
        $img = $p && $p->image
            ? (\Illuminate\Support\Str::startsWith($p->image, 'http') ? $p->image : asset('storage/'.$p->image))
            : 'https://placehold.co/200x140?text=Product';
    @endphp
    <article class="card cart-line" style="margin-bottom:10px">
        <img src="{{ $img }}" alt="{{ $p->name ?? 'Product' }}">
        <div>
            <strong>{{ $p->name ?? 'Product' }}</strong>
            <div class="muted">${{ number_format((float) $item->price, 2) }} each</div>
        </div>
        <form method="POST" action="{{ route('cart.update', $item) }}" class="inline-form">
            @csrf @method('PATCH')
            <input type="number" name="quantity" min="1" value="{{ $item->quantity }}" style="width:70px;padding:6px;border:1px solid var(--line);border-radius:6px">
            <button class="btn btn-sm btn-secondary" type="submit">Update</button>
        </form>
        <strong>${{ number_format((float) $item->price * $item->quantity, 2) }}</strong>
        <form method="POST" action="{{ route('cart.destroy', $item) }}" class="inline-form">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" type="submit">Remove</button>
        </form>
    </article>
@empty
    <div class="card" style="padding:18px"><p class="muted">Your cart is empty.</p></div>
@endforelse

@if($cart->items->isNotEmpty())
<aside class="card summary" style="margin-top:14px;max-width:320px">
    <p class="muted">Subtotal</p>
    <h2>${{ number_format($subtotal, 2) }}</h2>
    <a class="btn" href="{{ route('checkout.create') }}">Proceed to checkout</a>
</aside>
@endif
@endsection
