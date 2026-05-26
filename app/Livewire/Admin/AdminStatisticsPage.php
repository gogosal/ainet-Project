<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminStatisticsPage extends Component
{
    public function render()
    {
        // Revenue by month (last 12 months)
        $revenueByMonth = Order::where('status', 'closed')
            ->where('date', '>=', now()->subMonths(12))
            ->select(DB::raw("strftime('%Y-%m', date) as month"), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // New clients by month (last 6 months)
        $clientsByMonth = User::where('user_type', 'C')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(DB::raw("strftime('%Y-%m', created_at) as month"), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top 5 catalog images by order count
        $topImages = DB::table('order_items')
            ->join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->whereNull('tshirt_images.customer_id')
            ->select('tshirt_images.name', DB::raw('SUM(order_items.qty) as total_qty'))
            ->groupBy('tshirt_images.id', 'tshirt_images.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('livewire.admin.admin-statistics-page', compact(
            'revenueByMonth', 'ordersByStatus', 'clientsByMonth', 'topImages'
        ))->layout('layouts.admin', ['title' => 'Estatísticas']);
    }
}
