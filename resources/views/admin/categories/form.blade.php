@extends('layouts.admin')
@section('title', $category->exists ? 'Edit category' : 'New category')

@section('content')
<form class="form-card" method="POST"
      action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf
    @if($category->exists) @method('PUT') @endif

    <div class="form-grid">
        <div class="field">
            <label for="name">Name</label>
            <input id="name" name="name" value="{{ old('name', $category->name) }}" required>
        </div>
        <div class="field">
            <label for="slug">Slug (optional)</label>
            <input id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
        </div>
        <div class="field full">
            <label for="image">Image URL (optional)</label>
            <input id="image" name="image" value="{{ old('image', $category->image) }}">
        </div>
        <div class="field full">
            <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true))> Active</label>
        </div>
    </div>
    <div style="margin-top:14px">
        <button class="btn" type="submit">Save category</button>
        <a class="btn btn-secondary" href="{{ route('admin.categories.index') }}">Cancel</a>
    </div>
</form>
@endsection
