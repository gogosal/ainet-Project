<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', '');

        $orders = Order::where('customer_id', auth()->user()->customer->id)
            ->when($statusFilter, fn($q) => $q->where('status', $statusFilter))
            ->with('items.tshirtImage')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query())
            ->withQueryString();

        return view('orders.index', compact('orders', 'statusFilter'));
    }

    public function show(Order $order): View
    {
        if (auth()->user()->customer->id !== $order->customer_id) {
            abort(403);
        }

        $order->load(['items.tshirtImage', 'items.color', 'customer.user']);

        return view('orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        $user = auth()->user();

        if ($user->isClient()) {
            if (! $user->customer || $order->customer_id !== $user->customer->id) {
                abort(403);
            }
        }

        if (! $order->receipt_url) {
            abort(404);
        }

        $path = storage_path('app/private/' . $order->receipt_url);
        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }
}
