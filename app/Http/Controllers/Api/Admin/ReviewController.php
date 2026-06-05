<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user:id,name', 'product:id,name,slug']);

        if ($status = $request->query('status')) {
            if ($status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($productId = $request->query('product_id')) {
            $query->where('product_id', $productId);
        }

        $reviews = $query->latest()->paginate(15);

        return response()->json($reviews);
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return response()->json([
            'message' => 'Review approved.',
            'review'  => $review,
        ]);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(['message' => 'Review deleted.']);
    }
}
