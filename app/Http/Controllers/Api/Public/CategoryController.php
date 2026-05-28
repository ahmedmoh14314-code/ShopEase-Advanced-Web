<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

// Public storefront category browsing with active subcategories and products.
class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with(['subcategories' => function ($q) {
                $q->where('is_active', true)->orderBy('name');
            }])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $category = Category::with(['subcategories' => function ($q) {
                $q->where('is_active', true)->orderBy('name');
            }])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::with(['category', 'subcategory'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }
}
