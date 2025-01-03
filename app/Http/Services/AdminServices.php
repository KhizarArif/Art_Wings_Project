<?php

namespace App\Http\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AdminServices
{
    public function index()
    {
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');
 
        return view('admin.dashboards', compact( 'totalOrders', 'totalProducts', 'totalUsers', 'totalRevenue'));
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }

}
