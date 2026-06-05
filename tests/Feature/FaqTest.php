<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_endpoint_returns_only_active_faqs_ordered(): void
    {
        Faq::factory()->create(['question' => 'Z question', 'sort_order' => 5, 'is_active' => true]);
        Faq::factory()->create(['question' => 'A question', 'sort_order' => 1, 'is_active' => true]);
        Faq::factory()->inactive()->create(['question' => 'Hidden', 'sort_order' => 2]);

        $response = $this->getJson('/api/faqs');

        $response->assertOk();
        $response->assertJsonCount(2, 'faqs');
        $this->assertSame('A question', $response->json('faqs.0.question'));
        $this->assertSame('Z question', $response->json('faqs.1.question'));
    }

    public function test_admin_can_create_faq(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $response = $this->postJson('/api/admin/faqs', [
            'question' => 'How do I pay?',
            'answer'   => 'Cash on delivery.',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('faqs', ['question' => 'How do I pay?', 'sort_order' => 0, 'is_active' => true]);
    }

    public function test_manager_can_create_faq(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'manager']));

        $this->postJson('/api/admin/faqs', [
            'question' => 'Shipping time?',
            'answer'   => '2-5 business days.',
        ])->assertCreated();
    }

    public function test_customer_cannot_manage_faqs(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'customer']));

        $this->postJson('/api/admin/faqs', [
            'question' => 'x',
            'answer'   => 'y',
        ])->assertForbidden();
    }

    public function test_guest_cannot_manage_faqs(): void
    {
        $this->postJson('/api/admin/faqs', [
            'question' => 'x',
            'answer'   => 'y',
        ])->assertUnauthorized();
    }

    public function test_admin_can_update_and_delete_faq(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
        $faq = Faq::factory()->create();

        $this->putJson("/api/admin/faqs/{$faq->id}", [
            'question'   => 'Updated?',
            'answer'     => 'Updated answer.',
            'is_active'  => false,
        ])->assertOk();

        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'question' => 'Updated?', 'is_active' => false]);

        $this->deleteJson("/api/admin/faqs/{$faq->id}")->assertOk();
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_create_faq_requires_question_and_answer(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $this->postJson('/api/admin/faqs', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['question', 'answer']);
    }
}
