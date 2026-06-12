<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'totalClients'   => User::where('user_type', 'C')->count(),
            'totalStaff'     => User::whereIn('user_type', ['F', 'A'])->count(),
            'pendingOrders'  => Order::where('status', 'pending')->count(),
            'closedOrders'   => Order::where('status', 'closed')->count(),
            'canceledOrders' => Order::where('status', 'canceled')->count(),
            'totalRevenue'   => Order::where('status', 'closed')->sum('total_price'),
            'catalogImages'  => TshirtImage::whereNull('customer_id')->count(),
            'customImages'   => TshirtImage::whereNotNull('customer_id')->count(),
        ];

        $recentOrders = Order::with('customer.user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
