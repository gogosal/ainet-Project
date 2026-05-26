<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersPage extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function render()
    {
        $orders = Order::where('customer_id', auth()->user()->customer->id)
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->with('items.tshirtImage')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.orders.orders-page', compact('orders'))
            ->layout('layouts.app', ['title' => 'As minhas encomendas']);
    }
}
