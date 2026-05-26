<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;

class OrderDetailPage extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        // Ensure client can only see their own orders
        if (auth()->user()->customer->id !== $order->customer_id) {
            abort(403);
        }
        $this->order = $order->load(['items.tshirtImage', 'items.color', 'customer.user']);
    }

    public function render()
    {
        return view('livewire.orders.order-detail-page')
            ->layout('layouts.app', ['title' => 'Encomenda #' . $this->order->id]);
    }
}
