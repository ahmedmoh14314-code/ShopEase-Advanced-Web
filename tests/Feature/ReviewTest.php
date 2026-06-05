<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_sees_only_approved_reviews_for_a_product(): void
    {
        $product = Product::factory()->create();
        Review::factory()->approved()->for($product)->create();
        Review::factory()->for($product)->create(); // pending

        $response = $this->getJson("/api/products/{$product->slug}/reviews");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    public function test_customer_can_submit_a_review_as_pending(): void
    {
        $product = Product::factory()->create();
        Sanctum::actingAs(User::factory()->create(['role' => 'customer']));

        $response = $this->postJson("/api/products/{$product->slug}/reviews", [
            'rating'  => 5,
            'comment' => 'Great product!',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('reviews', [
            'product_id'  => $product->id,
            'rating'      => 5,
            'is_approved' => false,
        ]);
    }

    public function test_customer_cannot_review_same_product_twice(): void
    {
        $product = Product::factory()->create();
        $customer = User::factory()->create(['role' => 'customer']);
        Review::factory()->for($product)->for($customer)->create();

        Sanctum::actingAs($customer);

        $this->postJson("/api/products/{$product->slug}/reviews", ['rating' => 4])
            ->assertStatus(422);
    }

    public function test_guest_cannot_submit_a_review(): void
    {
        $product = Product::factory()->create();

        $this->postJson("/api/products/{$product->slug}/reviews", ['rating' => 4])
            ->assertUnauthorized();
    }

    public function test_rating_must_be_between_1_and_5(): void
    {
        $product = Product::factory()->create();
        Sanctum::actingAs(User::factory()->create(['role' => 'customer']));

        $this->postJson("/api/products/{$product->slug}/reviews", ['rating' => 9])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['rating']);
    }

    public function test_admin_can_approve_a_review(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
        $review = Review::factory()->create(['is_approved' => false]);

        $this->putJson("/api/admin/reviews/{$review->id}/approve")->assertOk();

        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'is_approved' => true]);
    }

    public function test_manager_can_moderate_reviews(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'manager']));
        $review = Review::factory()->create();

        $this->getJson('/api/admin/reviews')->assertOk();
        $this->deleteJson("/api/admin/reviews/{$review->id}")->assertOk();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_admin_reviews_can_be_filtered_by_status(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
        Review::factory()->approved()->create();
        Review::factory()->create(); // pending

        $this->getJson('/api/admin/reviews?status=pending')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_customer_cannot_access_admin_reviews(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'customer']));

        $this->getJson('/api/admin/reviews')->assertForbidden();
    }

    public function test_product_show_includes_approved_rating_average_and_count(): void
    {
        $product = Product::factory()->create();
        Review::factory()->approved()->for($product)->create(['rating' => 4]);
        Review::factory()->approved()->for($product)->create(['rating' => 5]);
        Review::factory()->for($product)->create(['rating' => 1]); // pending, excluded

        $response = $this->getJson("/api/products/{$product->slug}");

        $response->assertOk();
        $response->assertJsonPath('product.reviews_count', 2);
        $this->assertEquals(4.5, $response->json('product.reviews_avg_rating'));
    }
}
