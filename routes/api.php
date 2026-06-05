<?php

use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\Admin\SubcategoryController as AdminSubcategoryController;
use App\Http\Controllers\Api\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Api\Admin\InfoPageController as AdminInfoPageController;
use App\Http\Controllers\Api\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\InfoPageController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\Customer\CheckoutController;
use App\Http\Controllers\Api\Customer\OrderController;
use App\Http\Controllers\Api\Public\CategoryController;
use App\Http\Controllers\Api\Public\ProductController;
use Illuminate\Support\Facades\Route;

// public auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// public storefront
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{slug}', [CategoryController::class, 'show']);

Route::get('products/featured', [ProductController::class, 'featured']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{slug}', [ProductController::class, 'show']);
Route::get('products/{slug}/reviews', [ReviewController::class, 'index']);

Route::post('contact', [ContactController::class, 'store']);

// public help content
Route::get('faqs', [FaqController::class, 'index']);
Route::get('pages', [InfoPageController::class, 'index']);
Route::get('pages/{slug}', [InfoPageController::class, 'show']);

// authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // cart
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/items', [CartController::class, 'store']);
    Route::delete('cart/clear', [CartController::class, 'clear']);
    Route::put('cart/items/{cartItem}', [CartController::class, 'update']);
    Route::delete('cart/items/{cartItem}', [CartController::class, 'destroy']);

    // checkout
    Route::post('checkout', [CheckoutController::class, 'store']);

    // product reviews (submit)
    Route::post('products/{slug}/reviews', [ReviewController::class, 'store']);

    // my orders
    Route::get('my-orders', [OrderController::class, 'index']);
    Route::get('my-orders/{order}', [OrderController::class, 'show']);

    // admin + manager
    Route::middleware('role:admin,manager')->prefix('admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index']);

        // catalog
        Route::apiResource('categories', AdminCategoryController::class);
        Route::apiResource('subcategories', AdminSubcategoryController::class);
        Route::apiResource('products', AdminProductController::class);

        // orders
        Route::get('orders', [AdminOrderController::class, 'index']);
        Route::get('orders/{order}', [AdminOrderController::class, 'show']);
        Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus']);

        // FAQ management
        Route::apiResource('faqs', AdminFaqController::class);

        // review moderation
        Route::get('reviews', [AdminReviewController::class, 'index']);
        Route::put('reviews/{review}/approve', [AdminReviewController::class, 'approve']);
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy']);
    });

    // admin only
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::apiResource('users', AdminUserController::class)->except(['store']);

        Route::get('messages', [AdminContactMessageController::class, 'index']);
        Route::get('messages/{contactMessage}', [AdminContactMessageController::class, 'show']);
        Route::put('messages/{contactMessage}/read', [AdminContactMessageController::class, 'markRead']);
        Route::delete('messages/{contactMessage}', [AdminContactMessageController::class, 'destroy']);

        Route::get('settings', [AdminSettingController::class, 'index']);
        Route::put('settings', [AdminSettingController::class, 'update']);

        // informational static pages
        Route::apiResource('pages', AdminInfoPageController::class);
    });
});
