<?php

namespace Tests\Feature;

use App\Models\InfoPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InfoPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_list_returns_only_published_pages(): void
    {
        InfoPage::factory()->create(['title' => 'About', 'is_published' => true]);
        InfoPage::factory()->unpublished()->create(['title' => 'Draft']);

        $response = $this->getJson('/api/pages');

        $response->assertOk();
        $response->assertJsonCount(1, 'pages');
        $this->assertSame('About', $response->json('pages.0.title'));
    }

    public function test_public_can_view_published_page_by_slug(): void
    {
        InfoPage::factory()->create(['slug' => 'about-us', 'is_published' => true]);

        $this->getJson('/api/pages/about-us')
            ->assertOk()
            ->assertJsonPath('page.slug', 'about-us');
    }

    public function test_public_cannot_view_unpublished_page(): void
    {
        InfoPage::factory()->unpublished()->create(['slug' => 'secret']);

        $this->getJson('/api/pages/secret')->assertNotFound();
    }

    public function test_admin_can_create_page(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));

        $response = $this->postJson('/api/admin/pages', [
            'title'   => 'Returns Policy',
            'slug'    => 'returns-policy',
            'content' => 'You can return items within 14 days.',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('info_pages', ['slug' => 'returns-policy', 'is_published' => true]);
    }

    public function test_admin_can_update_and_delete_page(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
        $page = InfoPage::factory()->create(['slug' => 'about-us']);

        $this->putJson("/api/admin/pages/{$page->id}", [
            'title'        => 'About ShopEase',
            'slug'         => 'about-us',
            'content'      => 'Updated content.',
            'is_published' => false,
        ])->assertOk();

        $this->assertDatabaseHas('info_pages', ['id' => $page->id, 'title' => 'About ShopEase', 'is_published' => false]);

        $this->deleteJson("/api/admin/pages/{$page->id}")->assertOk();
        $this->assertDatabaseMissing('info_pages', ['id' => $page->id]);
    }

    public function test_manager_cannot_manage_pages(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'manager']));

        $this->postJson('/api/admin/pages', [
            'title'   => 'x',
            'slug'    => 'x',
            'content' => 'y',
        ])->assertForbidden();
    }

    public function test_customer_cannot_manage_pages(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'customer']));

        $this->getJson('/api/admin/pages')->assertForbidden();
    }

    public function test_slug_must_be_unique_on_create(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'admin']));
        InfoPage::factory()->create(['slug' => 'about-us']);

        $this->postJson('/api/admin/pages', [
            'title'   => 'Another',
            'slug'    => 'about-us',
            'content' => 'dup',
        ])->assertStatus(422)->assertJsonValidationErrors(['slug']);
    }
}
