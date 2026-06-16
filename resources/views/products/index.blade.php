@extends('layouts.app')
@section('title', 'Products — ShopEase')

@section('content')
<div class="page-head"><h1>Products</h1></div>

<form class="filters" method="get" action="{{ route('products.index') }}">
    <div class="field">
        <label for="search">Search</label>
        <input id="search" name="search" value="{{ request('search') }}" placeholder="Product name...">
    </div>
    <div class="field">
        <label for="category">Category</label>
        <select id="category" name="category">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="field">
        <label for="min_price">Min price</label>
        <input id="min_price" type="number" step="0.01" min="0" name="min_price" value="{{ request('min_price') }}">
    </div>
    <div class="field">
        <label for="max_price">Max price</label>
        <input id="max_price" type="number" step="0.01" min="0" name="max_price" value="{{ request('max_price') }}">
    </div>
    <button class="btn" type="submit">Filter</button>
</form>

<section class="grid grid-4">
    @forelse($products as $product)
        @include('partials.product-card', ['product' => $product])
    @empty
        <p class="muted">No products matched your search.</p>
    @endforelse
</section>

<div style="margin-top:18px">{{ $products->links() }}</div>
@endsection
