<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\InfoPage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\User;

// Admin/manager dashboard totals plus latest orders and messages.
class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_users'         => User::count(),
            'total_categories'    => Category::count(),
            'total_subcategories' => Subcategory::count(),
            'total_products'      => Product::count(),
            'total_orders'        => Order::count(),
            'pending_orders'      => Order::where('status', 'pending')->count(),
            'total_revenue'       => (float) Order::where('status', 'delivered')->sum('total'),
            'pending_reviews'     => Review::where('is_approved', false)->count(),
            'total_faqs'          => Faq::count(),
            'total_pages'         => InfoPage::count(),
            'latest_orders'       => Order::with('user')->latest()->limit(5)->get(),
            'latest_messages'     => ContactMessage::latest()->limit(5)->get(),
        ]);
    }
}
