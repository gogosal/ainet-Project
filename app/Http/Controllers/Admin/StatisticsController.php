<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(): View
    {
        $revenueByMonth = Order::where('status', 'closed')
            ->where('date', '>=', now()->subMonths(12))
            ->select(DB::raw("strftime('%Y-%m', date) as month"), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $clientsByMonth = User::where('user_type', 'C')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(DB::raw("strftime('%Y-%m', created_at) as month"), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topImages = DB::table('order_items')
            ->join('tshirt_images', 'order_items.tshirt_image_id', '=', 'tshirt_images.id')
            ->whereNull('tshirt_images.customer_id')
            ->select('tshirt_images.name', DB::raw('SUM(order_items.qty) as total_qty'))
            ->groupBy('tshirt_images.id', 'tshirt_images.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.statistics.index', compact(
            'revenueByMonth', 'ordersByStatus', 'clientsByMonth', 'topImages'
        ));
    }
}
