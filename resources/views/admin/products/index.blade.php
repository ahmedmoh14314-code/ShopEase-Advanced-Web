@extends('layouts.admin')
@section('title', 'Products')

@section('content')
<div class="page-head">
    <h1>Products</h1>
    <a class="btn" href="{{ route('admin.products.create') }}">+ New product</a>
</div>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->name }}<div class="muted">{{ $product->slug }}</div></td>
                <td>{{ $product->category?->name }}</td>
                <td>${{ number_format((float) $product->price, 2) }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
                <td class="actions">
                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-form" onsubmit="return confirm('Delete this product?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">No products yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:14px">{{ $products->links() }}</div>
@endsection
