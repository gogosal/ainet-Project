<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::where('status', 'pending')
            ->with(['customer.user', 'items'])
            ->orderBy('created_at')
            ->paginate(15);

        return view('employee.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['customer.user', 'items.tshirtImage', 'items.color']);

        return view('employee.orders.show', compact('order'));
    }

    public function close(Order $order): RedirectResponse
    {
        if (! $order->isPending()) {
            return back()->with('error', 'Encomenda não está pendente.');
        }

        $order->update(['status' => 'closed']);

        try {
            app(OrderService::class)->closeOrder($order);
        } catch (\Exception $e) {
            \Log::error('closeOrder failed: ' . $e->getMessage());
        }

        return back()->with('success', "Encomenda #{$order->id} marcada como fechada.");
    }
}
