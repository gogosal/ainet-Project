<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Services\OrderService;

class AdminOrdersPage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    // Cancel modal
    public bool $showCancelModal = false;
    public ?int $cancelOrderId = null;
    public string $cancelReason = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function closeOrder(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        if (!$order->isPending()) return;
        app(OrderService::class)->closeOrder($order);
        session()->flash('success', "Encomenda #{$orderId} fechada.");
    }

    public function openCancelModal(int $orderId): void
    {
        $this->cancelOrderId = $orderId;
        $this->cancelReason = '';
        $this->showCancelModal = true;
    }

    public function cancelOrder(): void
    {
        $this->validate(['cancelReason' => 'required|string|max:500']);
        $order = Order::findOrFail($this->cancelOrderId);
        if (!$order->isPending()) {
            $this->showCancelModal = false;
            return;
        }
        $order->update(['status' => 'canceled', 'reason_for_cancellation' => $this->cancelReason]);
        // Send cancellation email
        try {
            \Mail::to($order->customer->user->email)->send(new \App\Mail\OrderCanceledMail($order));
        } catch (\Exception $e) {
        }
        $this->showCancelModal = false;
        $this->cancelOrderId = null;
        session()->flash('success', "Encomenda #{$order->id} cancelada.");
    }

    public function render()
    {
        $orders = Order::with('customer.user')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('id', 'like', "%{$this->search}%")
                    ->orWhereHas('customer.user', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            }))
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.admin-orders-page', compact('orders'))
            ->layout('layouts.admin', ['title' => 'Encomendas']);
    }

}
