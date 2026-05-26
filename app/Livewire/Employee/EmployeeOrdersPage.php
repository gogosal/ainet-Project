<?php

namespace App\Livewire\Employee;

use App\Models\Order;
use App\Services\OrderService;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeOrdersPage extends Component
{
    use WithPagination;

    public ?int $closingOrderId = null;

    public function closeOrder(int $orderId): void
    {
        $order = Order::where('id', $orderId)->where('status', 'pending')->firstOrFail();
        $order->update(['status' => 'closed']);

        // Generate PDF + send email (G6 - handled by OrderService if it exists, otherwise inline)
        try {
            app(\App\Services\OrderService::class)->closeOrder($order);
        } catch (\Exception $e) {
            \Log::error('closeOrder failed: ' . $e->getMessage());
        }

        $this->dispatch('$refresh');
        session()->flash('success', "Encomenda #{$orderId} marcada como fechada.");
    }

    public function render()
    {
        $orders = Order::where('status', 'pending')
            ->with(['customer.user', 'items'])
            ->orderBy('created_at')
            ->paginate(15);

        return view('livewire.employee.employee-orders-page', compact('orders'))
            ->layout('layouts.employee', ['title' => 'Encomendas Pendentes']);
    }
}
