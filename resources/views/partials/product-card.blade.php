@php
    $img = $product->image
        ? (\Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/'.$product->image))
        : 'https://placehold.co/600x400?text=Product';
@endphp
<a href="{{ route('products.show', $product->slug) }}" class="card product-card">
    <img src="{{ $img }}" alt="{{ $product->name }}">
    <div class="body">
        <span class="muted">{{ $product->category?->name }}</span>
        <h3>{{ $product->name }}</h3>
        <span class="price">${{ number_format((float) $product->price, 2) }}</span>
    </div>
</a>
