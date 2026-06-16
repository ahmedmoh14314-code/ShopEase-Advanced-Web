@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
<div class="page-head">
    <h1>Categories</h1>
    <a class="btn" href="{{ route('admin.categories.create') }}">+ New category</a>
</div>

<div class="table-wrap">
    <table class="table">
        <thead><tr><th>Name</th><th>Slug</th><th>Products</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->products_count }}</td>
                <td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                <td class="actions">
                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline-form" onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="muted">No categories yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:14px">{{ $categories->links() }}</div>
@endsection
