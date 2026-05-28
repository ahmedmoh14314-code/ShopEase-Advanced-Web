<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

// Admin/manager CRUD for products, including image upload and stock control.
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'subcategory'])
            ->latest()
            ->get();

        return response()->json([
            'products' => $products,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_active']   = $request->boolean('is_active', true);

        $product = Product::create($data);
        $product->load(['category', 'subcategory']);

        return response()->json([
            'message' => 'Product created.',
            'product' => $product,
        ], 201);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'subcategory']);

        return response()->json([
            'product' => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // replace old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image']);
        }

        $data['is_featured'] = $request->boolean('is_featured', $product->is_featured);
        $data['is_active']   = $request->boolean('is_active', $product->is_active);

        $product->update($data);
        $product->load(['category', 'subcategory']);

        return response()->json([
            'message' => 'Product updated.',
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted.']);
    }
}
