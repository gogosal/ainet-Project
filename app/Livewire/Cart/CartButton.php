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
                    style="position:relative;display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:transparent;border:1px solid #e0ddd8;border-radius:999px;color:#888;cursor:pointer;transition:all .15s;"
                    onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'"
                    onmouseout="this.style.borderColor='#e0ddd8';this.style.color='#888'">
                <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                @if($count > 0)
                <span style="position:absolute;top:-5px;right:-5px;background:#1a1a1a;color:#f5f4f1;border-radius:50%;width:16px;height:16px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;">
                    {{ $count > 9 ? '9+' : $count }}
                </span>
                @endif
            </button>
        </div>
        BLADE;
    }
}
