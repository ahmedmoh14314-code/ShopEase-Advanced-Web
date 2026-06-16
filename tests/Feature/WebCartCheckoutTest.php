<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebCartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        return User::create([
            'name' => 'Buyer', 'email' => 'buyer@test.com',
            'password' => Hash::make('password123'), 'role' => 'customer', 'is_active' => true,
        ]);
    }

    private function product(): Product
    {
        $category = Category::create(['name' => 'Phones', 'slug' => 'phones', 'is_active' => true]);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Test Phone', 'slug' => 'test-phone',
            'price' => 100, 'stock' => 10, 'is_active' => true,
        ]);
    }

    public function test_customer_can_add_a_product_to_the_cart_and_see_it_listed(): void
    {
        $customer = $this->customer();
        $product = $this->product();

        $this->actingAs($customer)
            ->post('/cart', ['product_id' => $product->id, 'quantity' => 2])
            ->assertRedirect('/cart');

        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 2]);

        $this->actingAs($customer)->get('/cart')->assertOk()->assertSee($product->name);
    }

    public function test_customer_can_delete_an_item_from_the_cart(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $this->actingAs($customer)->post('/cart', ['product_id' => $product->id, 'quantity' => 1]);

        $item = Cart::where('user_id', $customer->id)->first()->items()->first();

        $this->actingAs($customer)->delete('/cart/'.$item->id)->assertRedirect('/cart');
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_checkout_creates_an_order_reduces_stock_and_clears_the_cart(): void
    {
        $customer = $this->customer();
        $product = $this->product();
        $this->actingAs($customer)->post('/cart', ['product_id' => $product->id, 'quantity' => 3]);

        $response = $this->actingAs($customer)->post('/checkout', [
            'customer_name' => 'Buyer',
            'customer_email' => 'buyer@test.com',
            'phone' => '555000',
            'shipping_address' => '1 Test Street',
        ]);

        $order = Order::where('user_id', $customer->id)->firstOrFail();
        $response->assertRedirect('/my-orders/'.$order->id);

        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'quantity' => 3]);
        $this->assertEquals(7, $product->fresh()->stock);          // 10 - 3
        $this->assertSame(0, Cart::where('user_id', $customer->id)->first()->items()->count());
    }

    public function test_customer_cannot_view_another_users_order(): void
    {
        $owner = $this->customer();
        $other = User::create([
            'name' => 'Other', 'email' => 'other@test.com',
            'password' => Hash::make('password123'), 'role' => 'customer', 'is_active' => true,
        ]);

        $order = Order::create([
            'user_id' => $owner->id, 'order_number' => 'ORD-TEST1',
            'customer_name' => 'Buyer', 'customer_email' => 'buyer@test.com',
            'phone' => '555', 'shipping_address' => 'x',
            'subtotal' => 100, 'total' => 100, 'status' => 'pending',
            'payment_method' => 'cash_on_delivery',
        ]);

        $this->actingAs($owner)->get('/my-orders/'.$order->id)->assertOk();
        $this->actingAs($other)->get('/my-orders/'.$order->id)->assertForbidden();
    }
}
