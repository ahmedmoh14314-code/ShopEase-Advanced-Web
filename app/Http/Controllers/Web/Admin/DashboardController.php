<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'Products' => Product::count(),
            'Categories' => Category::count(),
            'Orders' => Order::count(),
            'Customers' => User::where('role', 'customer')->count(),
        ];

        $latestOrders = Order::latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'latestOrders'));
    }
}
