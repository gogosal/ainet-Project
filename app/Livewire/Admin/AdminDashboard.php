<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Order;
use App\Models\TshirtImage;

class AdminDashboard extends Component
{
    public function render()
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

        return view('livewire.admin.admin-dashboard', compact('stats', 'recentOrders'))
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
