<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;

// Public list of approved product reviews and customer review submission.
class ReviewController extends Controller
{
    public function index(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $reviews = $product->reviews()
            ->where('is_approved', true)
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    public function store(StoreReviewRequest $request, string $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $alreadyReviewed = Review::where('product_id', $product->id)
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'message' => 'You have already reviewed this product.',
            ], 422);
        }

        $data = $request->validated();

        $review = Review::create([
            'product_id'  => $product->id,
            'user_id'     => $request->user()->id,
            'rating'      => $data['rating'],
            'comment'     => $data['comment'] ?? null,
            'is_approved' => false,
        ]);

        return response()->json([
            'message' => 'Thanks! Your review has been submitted and is awaiting approval.',
            'review'  => $review,
        ], 201);
    }
}
