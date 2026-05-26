<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Services\OrderService;

class AdminOrderDetailPage extends Component
{
    public Order $order;

    public bool $showCancelModal = false;
    public string $cancelReason = '';

    public function mount(Order $order): void
    {
        $this->order = $order->load('customer.user', 'items.tshirtImage', 'items.color');
    }

    public function closeOrder(): void
    {
        if (!$this->order->isPending()) return;
        app(OrderService::class)->closeOrder($this->order);
        $this->order->refresh();
        session()->flash('success', 'Encomenda fechada com sucesso.');
    }

    public function cancelOrder(): void
    {
        $this->validate(['cancelReason' => 'required|string|max:500']);
        if (!$this->order->isPending()) {
            $this->showCancelModal = false;
            return;
        }
        $this->order->update(['status' => 'canceled', 'reason_for_cancellation' => $this->cancelReason]);
        try {
            \Mail::to($this->order->customer->user->email)->send(new \App\Mail\OrderCanceledMail($this->order));
        } catch (\Exception $e) {}
        $this->order->refresh();
        $this->showCancelModal = false;
        session()->flash('success', 'Encomenda cancelada.');
    }

    public function render()
    {
        return view('livewire.admin.admin-order-detail-page')
            ->layout('layouts.admin', ['title' => 'Encomenda #' . $this->order->id]);
    }
}
