@extends('layouts.admin')
@section('title', $product->exists ? 'Edit product' : 'New product')

@section('content')
<form class="form-card" method="POST"
      action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if($product->exists) @method('PUT') @endif

    <div class="form-grid">
        <div class="field">
            <label for="name">Name</label>
            <input id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="field">
            <label for="slug">Slug (optional)</label>
            <input id="slug" name="slug" value="{{ old('slug', $product->slug) }}">
        </div>
        <div class="field">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="subcategory_id">Subcategory (optional)</label>
            <select id="subcategory_id" name="subcategory_id">
                <option value="">None</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" @selected((string) old('subcategory_id', $product->subcategory_id) === (string) $subcategory->id)>{{ $subcategory->category?->name }} / {{ $subcategory->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="price">Price</label>
            <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="field">
            <label for="stock">Stock</label>
            <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required>
        </div>
        <div class="field full">
            <label for="image">Image URL (optional)</label>
            <input id="image" name="image" value="{{ old('image', $product->image) }}">
        </div>
        <div class="field full">
            <label for="short_description">Short description</label>
            <input id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}">
        </div>
        <div class="field full">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="field">
            <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured</label>
        </div>
        <div class="field">
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))> Active</label>
        </div>
    </div>
    <div style="margin-top:14px">
        <button class="btn" type="submit">Save product</button>
        <a class="btn btn-secondary" href="{{ route('admin.products.index') }}">Cancel</a>
    </div>
</form>
@endsection
