<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCartItemRequest;
use App\Http\Requests\Customer\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

// Authenticated customer cart: view, add, update, remove, and clear items.
class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user()->id);

        $cart->load(['items.product.category', 'items.product.subcategory']);

        return response()->json([
            'cart' => $cart,
        ]);
    }

    public function store(StoreCartItemRequest $request)
    {
        $data = $request->validated();

        $product = Product::where('id', $data['product_id'])
            ->where('is_active', true)
            ->first();

        if (! $product) {
            return response()->json(['message' => 'Product is not available.'], 404);
        }

        $cart = $this->getOrCreateCart($request->user()->id);

        // if it's already there just bump the quantity
        $existing = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        $newQty = $existing ? $existing->quantity + $data['quantity'] : $data['quantity'];

        if ($newQty > $product->stock) {
            return response()->json([
                'message' => 'Not enough stock for this product.',
                'available_stock' => $product->stock,
            ], 422);
        }

        if ($existing) {
            $existing->update(['quantity' => $newQty]);
            $item = $existing;
        } else {
            $item = CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $newQty,
                'price'      => $product->price,
            ]);
        }

        $item->load('product');

        return response()->json([
            'message' => 'Product added to cart.',
            'item'    => $item,
        ], 201);
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        $this->ensureOwnership($cartItem, $request);

        $quantity = $request->validated()['quantity'];

        $product = $cartItem->product;

        if (! $product || ! $product->is_active) {
            return response()->json(['message' => 'Product is no longer available.'], 422);
        }

        if ($quantity > $product->stock) {
            return response()->json([
                'message' => 'Not enough stock for this product.',
                'available_stock' => $product->stock,
            ], 422);
        }

        $cartItem->update(['quantity' => $quantity]);
        $cartItem->load('product');

        return response()->json([
            'message' => 'Cart item updated.',
            'item'    => $cartItem,
        ]);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $this->ensureOwnership($cartItem, $request);

        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed.']);
    }

    public function clear(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user()->id);
        $cart->items()->delete();

        return response()->json(['message' => 'Cart cleared.']);
    }

    private function getOrCreateCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    private function ensureOwnership(CartItem $cartItem, Request $request): void
    {
        if ($cartItem->cart->user_id !== $request->user()->id) {
            abort(403, 'You do not have access to this cart item.');
        }
    }
}
