<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebStorefrontTest extends TestCase
{
    use RefreshDatabase;

    private function product(array $overrides = []): Product
    {
        $slug = $overrides['slug'] ?? 'test-phone';

        $category = Category::create([
            'name' => 'Cat '.$slug,
            'slug' => 'cat-'.$slug,
            'is_active' => true,
        ]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Test Phone',
            'slug' => 'test-phone',
            'short_description' => 'A nice test phone.',
            'description' => 'Full description.',
            'price' => 199.99,
            'stock' => 10,
            'is_featured' => true,
            'is_active' => true,
        ], $overrides));
    }

    private function customer(): User
    {
        return User::create([
            'name' => 'Cust', 'email' => 'c@test.com',
            'password' => Hash::make('password123'), 'role' => 'customer', 'is_active' => true,
        ]);
    }

    public function test_home_page_renders_with_a_base_layout_and_featured_products(): void
    {
        $product = $this->product();

        $this->get('/')
            ->assertOk()
            ->assertSee('ShopEase')
            ->assertSee($product->name);
    }

    public function test_product_list_and_detail_pages_work(): void
    {
        $product = $this->product();

        $this->get('/products')->assertOk()->assertSee($product->name);

        // Guest sees a login prompt instead of the add-to-cart button.
        $this->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('199.99')
            ->assertSee('Login to add to cart');

        // Logged-in customer sees the real add-to-cart action.
        $this->actingAs($this->customer())
            ->get('/products/'.$product->slug)
            ->assertOk()
            ->assertSee('Add to cart');
    }

    public function test_product_list_search_filter_works(): void
    {
        $this->product(['name' => 'Alpha Phone', 'slug' => 'alpha']);
        $this->product(['name' => 'Beta Tablet', 'slug' => 'beta']);

        $this->get('/products?search=Alpha')
            ->assertOk()
            ->assertSee('Alpha Phone')
            ->assertDontSee('Beta Tablet');
    }

    public function test_guests_are_redirected_to_login_from_protected_pages(): void
    {
        $this->get('/cart')->assertRedirect('/login');
        $this->get('/checkout')->assertRedirect('/login');
        $this->get('/my-orders')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_customers_cannot_access_the_admin_panel(): void
    {
        $this->actingAs($this->customer())->get('/admin')->assertForbidden();
    }
}
