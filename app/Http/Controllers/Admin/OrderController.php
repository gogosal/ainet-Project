<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderCancelRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $statusFilter = $request->query('status', 'all');
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');

        $orders = Order::with('customer.user')
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('customer.user', fn($q) => $q->where('name', 'like', "%{$search}%"));
            }))
            ->when($statusFilter !== 'all', fn($q) => $q->where('status', $statusFilter))
            ->when($dateFrom, fn($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('date', '<=', $dateTo))
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        return view('admin.orders.index', compact('orders', 'search', 'statusFilter', 'dateFrom', 'dateTo'));
    }

    public function show(Order $order): View
    {
        $order->load('customer.user', 'items.tshirtImage', 'items.color');

        return view('admin.orders.show', compact('order'));
    }

    public function close(Order $order): RedirectResponse
    {
        if (! $order->isPending()) {
            return back()->with('error', 'Encomenda não está pendente.');
        }

        app(OrderService::class)->closeOrder($order);

        return back()->with('success', "Encomenda #{$order->id} fechada.");
    }

    public function cancel(OrderCancelRequest $request, Order $order): RedirectResponse
    {
        if (! $order->isPending()) {
            return back()->with('error', 'Encomenda não está pendente.');
        }

        $order->update([
            'status'                  => 'canceled',
            'reason_for_cancellation' => $request->validated()['reason'],
        ]);

        try {
            \Mail::to($order->customer->user->email)->send(new \App\Mail\OrderCanceledMail($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao enviar email de cancelamento: ' . $e->getMessage());
        }

        return back()->with('success', "Encomenda #{$order->id} cancelada.");
    }

    public function receipt(Order $order)
    {
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
