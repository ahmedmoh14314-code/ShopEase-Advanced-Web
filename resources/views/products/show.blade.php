@extends('layouts.app')
@section('title', $product->name.' — ShopEase')

@section('content')
@php
    $img = $product->image
        ? (\Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/'.$product->image))
        : 'https://placehold.co/800x600?text=Product';
@endphp
<p><a href="{{ route('products.index') }}">&larr; Back to products</a></p>

<section class="detail-grid">
    <img src="{{ $img }}" alt="{{ $product->name }}">
    <div>
        <p class="muted">{{ $product->category?->name }}@if($product->subcategory) / {{ $product->subcategory->name }}@endif</p>
        <h1>{{ $product->name }}</h1>
        <p class="price" style="font-size:24px">${{ number_format((float) $product->price, 2) }}</p>
        <p>{{ $product->description ?: $product->short_description }}</p>
        <p class="{{ $product->stock > 0 ? 'stock-ok' : 'stock-out' }}">
            {{ $product->stock > 0 ? $product->stock.' in stock' : 'Out of stock' }}
        </p>

        @auth
            <form method="POST" action="{{ route('cart.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="field" style="max-width:140px">
                    <label for="quantity">Quantity</label>
                    <input id="quantity" type="number" name="quantity" min="1" max="{{ max($product->stock, 1) }}" value="1">
                </div>
                <button class="btn" type="submit" @disabled($product->stock < 1)>Add to cart</button>
            </form>
        @else
            <a class="btn" href="{{ route('login') }}">Login to add to cart</a>
        @endauth
    </div>
</section>

@if($relatedProducts->isNotEmpty())
<div class="section-head"><h2>Related products</h2></div>
<section class="grid grid-4">
    @foreach($relatedProducts as $related)
        @include('partials.product-card', ['product' => $related])
    @endforeach
</section>
@endif
@endsection
