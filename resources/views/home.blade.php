@extends('layouts.app')
@section('title', 'ShopEase — Home')

@section('content')
<section class="hero">
    <h1>Smart shopping made simple.</h1>
    <p>Browse our catalog of quality products across many categories — all in one straightforward store.</p>
    <a href="{{ route('products.index') }}" class="btn btn-light">Browse products</a>
</section>

@if($categories->isNotEmpty())
<div class="section-head"><h2>Categories</h2><a href="{{ route('products.index') }}">View all</a></div>
<section class="grid grid-4">
    @foreach($categories as $category)
        <a class="card category-card" href="{{ route('products.index', ['category' => $category->slug]) }}">
            <h3>{{ $category->name }}</h3>
            <p class="muted">{{ $category->products_count }} products</p>
        </a>
    @endforeach
</section>
@endif

<div class="section-head"><h2>Featured products</h2><a href="{{ route('products.index') }}">See more</a></div>
<section class="grid grid-4">
    @forelse($featuredProducts as $product)
        @include('partials.product-card', ['product' => $product])
    @empty
        <p class="muted">No featured products yet.</p>
    @endforelse
</section>
@endsection
