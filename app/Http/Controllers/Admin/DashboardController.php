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

        $kpis = [
            [
                'label'  => 'Clientes',
                'value'  => $stats['totalClients'],
                'sub'    => 'contas registadas',
                'accent' => '#7c6fa0',
                'icon'   => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>'
            ],
            [
                'label'  => 'Pendentes',
                'value'  => $stats['pendingOrders'],
                'sub'    => 'a aguardar',
                'accent' => '#d97706',
                'icon'   => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'
            ],
            [
                'label'  => 'Fechadas',
                'value'  => $stats['closedOrders'],
                'sub'    => number_format($stats['totalRevenue'], 2, ',', '.') . ' € receita',
                'accent' => '#16a34a',
                'icon'   => '<polyline points="20 6 9 17 4 12"/>'
            ],
            [
                'label'  => 'Canceladas',
                'value'  => $stats['canceledOrders'],
                'sub'    => 'total canceladas',
                'accent' => '#dc2626',
                'icon'   => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'
            ],
        ];

        $secondaryStats = [
            [
                'label' => 'Colaboradores',
                'value' => $stats['totalStaff'],
                'sub'   => 'Funcionários & Admins',
                'icon'  => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>'
            ],
            [
                'label' => 'Imagens Catálogo',
                'value' => $stats['catalogImages'],
                'sub'   => 'designs públicos',
                'icon'  => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" stroke="none"/><path d="M21 15l-5-5L5 21"/>'
            ],
            [
                'label' => 'Imgs Personalizadas',
                'value' => $stats['customImages'],
                'sub'   => 'enviadas por clientes',
                'icon'  => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>'
            ],
        ];

        $statusMapping = [
            'pending'  => ['label' => 'Pendente',  'color' => '#d97706', 'bg' => 'rgba(217,119,6,.08)',  'border' => 'rgba(217,119,6,.2)'],
            'closed'   => ['label' => 'Fechada',   'color' => '#16a34a', 'bg' => 'rgba(22,163,74,.08)',  'border' => 'rgba(22,163,74,.2)'],
            'canceled' => ['label' => 'Cancelada', 'color' => '#dc2626', 'bg' => 'rgba(220,38,38,.06)',  'border' => 'rgba(220,38,38,.18)'],
        ];

        foreach ($recentOrders as $order) {
            $order->format = $statusMapping[$order->status] ?? [
                'label' => $order->status,
                'color' => '#888',
                'bg' => 'rgba(0,0,0,.04)',
                'border' => 'rgba(0,0,0,.1)'
            ];
        }

        return view('admin.dashboard', compact('kpis', 'secondaryStats', 'recentOrders'));
    }
}
