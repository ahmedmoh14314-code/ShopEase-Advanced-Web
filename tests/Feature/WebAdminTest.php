<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'admin@test.com',
            'password' => Hash::make('password123'), 'role' => 'admin', 'is_active' => true,
        ]);
    }

    private function category(): Category
    {
        return Category::create(['name' => 'Phones', 'slug' => 'phones', 'is_active' => true]);
    }

    public function test_admin_can_create_update_and_delete_a_category(): void
    {
        $admin = $this->admin();

        // create (slug intentionally omitted — regression guard for the blank-slug bug)
        $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Laptops', 'is_active' => '1',
        ])->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Laptops', 'slug' => 'laptops']);

        $category = Category::where('name', 'Laptops')->first();

        $this->actingAs($admin)->put('/admin/categories/'.$category->id, [
            'name' => 'Gaming Laptops', 'is_active' => '1',
        ])->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Gaming Laptops']);

        $this->actingAs($admin)->delete('/admin/categories/'.$category->id);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_create_update_and_delete_a_product(): void
    {
        $admin = $this->admin();
        $category = $this->category();

        $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'New Phone', 'price' => '299.99', 'stock' => '8', 'is_active' => '1',
        ])->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['name' => 'New Phone', 'slug' => 'new-phone', 'stock' => 8]);

        $product = Product::where('name', 'New Phone')->first();

        $this->actingAs($admin)->put('/admin/products/'.$product->id, [
            'category_id' => $category->id,
            'name' => 'New Phone', 'price' => '349.99', 'stock' => '2', 'is_active' => '1',
        ])->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 2]);

        $this->actingAs($admin)->delete('/admin/products/'.$product->id);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_approve_reject_and_complete_an_order(): void
    {
        $admin = $this->admin();
        $order = Order::create([
            'user_id' => $admin->id, 'order_number' => 'ORD-A1',
            'customer_name' => 'C', 'customer_email' => 'c@test.com',
            'phone' => '5', 'shipping_address' => 'x',
            'subtotal' => 50, 'total' => 50, 'status' => 'pending',
            'payment_method' => 'cash_on_delivery',
        ]);

        $this->actingAs($admin)->put('/admin/orders/'.$order->id.'/status', ['action' => 'approve']);
        $this->assertEquals('processing', $order->fresh()->status);

        $this->actingAs($admin)->put('/admin/orders/'.$order->id.'/status', ['action' => 'completed']);
        $this->assertEquals('delivered', $order->fresh()->status);

        $this->actingAs($admin)->put('/admin/orders/'.$order->id.'/status', ['action' => 'reject']);
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_admin_pages_list_records(): void
    {
        $admin = $this->admin();
        $this->category();

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Dashboard');
        $this->actingAs($admin)->get('/admin/categories')->assertOk()->assertSee('Phones');
        $this->actingAs($admin)->get('/admin/products')->assertOk();
        $this->actingAs($admin)->get('/admin/orders')->assertOk();
    }
}
