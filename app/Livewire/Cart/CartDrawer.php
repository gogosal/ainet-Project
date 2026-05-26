<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use App\Models\Color;
use Livewire\Component;

class CartDrawer extends Component
{
    public bool $open = false;

    #[\Livewire\Attributes\On('open-cart')]
    public function openDrawer(): void
    {
        $this->open = true;
    }

    #[\Livewire\Attributes\On('cart-updated')]
    public function refresh(): void
    {
        // Reactive refresh triggers re-render
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function remove(int $index): void
    {
        app(CartService::class)->remove($index);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $cart = app(CartService::class);
        $items = $cart->enrichedItems();
        $total = $cart->total();

        return view('livewire.cart.cart-drawer', compact('items', 'total'));
    }
}
