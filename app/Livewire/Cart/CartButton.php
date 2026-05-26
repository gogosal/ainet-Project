<?php

namespace App\Livewire\Cart;

use App\Services\CartService;
use Livewire\Component;

class CartButton extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->count = app(CartService::class)->count();
    }

    #[\Livewire\Attributes\On('cart-updated')]
    public function refreshCount(): void
    {
        $this->count = app(CartService::class)->count();
    }

    public function openCart(): void
    {
        $this->dispatch('open-cart');
    }

    public function render()
    {
        return <<<'BLADE'
        <div>
            <button wire:click="openCart"
                    style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:50%;background:#1a1a2e;border:1px solid #1e1e30;color:#94a3b8;cursor:pointer;transition:all .2s;"
                    onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'"
                    onmouseout="this.style.borderColor='#1e1e30';this.style.color='#94a3b8'">
                <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @if($count > 0)
                <span style="position:absolute;top:-4px;right:-4px;background:#7c3aed;color:white;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;">
                    {{ $count > 9 ? '9+' : $count }}
                </span>
                @endif
            </button>
        </div>
        BLADE;
    }
}
