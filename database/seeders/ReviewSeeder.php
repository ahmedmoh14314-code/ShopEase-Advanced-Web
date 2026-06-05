<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->orderBy('id')->take(4)->get();
        $products = Product::orderBy('id')->take(6)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        // One review per product from a rotating customer, so (product, user)
        // pairs stay unique. A couple are left pending for the moderation queue.
        $samples = [
            ['rating' => 5, 'comment' => 'Excellent product, exactly as described. Highly recommend!', 'is_approved' => true],
            ['rating' => 4, 'comment' => 'Good quality for the price. Happy with my purchase.', 'is_approved' => true],
            ['rating' => 5, 'comment' => 'Fast delivery and the item works perfectly.', 'is_approved' => true],
            ['rating' => 3, 'comment' => "It's okay, does the job but nothing special.", 'is_approved' => true],
            ['rating' => 2, 'comment' => 'Not quite what I expected from the photos.', 'is_approved' => false],
            ['rating' => 4, 'comment' => 'Pretty satisfied overall, would buy again.', 'is_approved' => false],
        ];

        foreach ($products as $index => $product) {
            $sample = $samples[$index % count($samples)];
            $customer = $customers[$index % $customers->count()];

            Review::create([
                'product_id'  => $product->id,
                'user_id'     => $customer->id,
                'rating'      => $sample['rating'],
                'comment'     => $sample['comment'],
                'is_approved' => $sample['is_approved'],
            ]);
        }
    }
}
