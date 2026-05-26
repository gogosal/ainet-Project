<?php

namespace App\Livewire\Cart;

use App\Models\Color;
use App\Services\CartService;
use Livewire\Component;

class CartPage extends Component
{
    public array $editColors = [];
    public array $editSizes = [];
    public array $editQtys = [];

    public function mount(): void
    {
        $items = app(CartService::class)->items();
        foreach ($items as $index => $item) {
            $this->editColors[$index] = $item['color_code'];
            $this->editSizes[$index]  = $item['size'];
            $this->editQtys[$index]   = $item['qty'];
        }
    }

    public function updateItem(int $index): void
    {
        $this->validate([
            "editColors.$index" => 'required|exists:colors,code',
            "editSizes.$index"  => 'required|in:XS,S,M,L,XL',
            "editQtys.$index"   => 'required|integer|min:0|max:99',
        ]);

        app(CartService::class)->update(
            $index,
            $this->editColors[$index],
            $this->editSizes[$index],
            (int)$this->editQtys[$index]
        );

        $this->dispatch('cart-updated');
        $this->reinitEdits();
    }

    public function remove(int $index): void
    {
        app(CartService::class)->remove($index);
        $this->dispatch('cart-updated');
        $this->reinitEdits();
    }

    public function clear(): void
    {
        app(CartService::class)->clear();
        $this->dispatch('cart-updated');
        $this->editColors = [];
        $this->editSizes = [];
        $this->editQtys = [];
    }

    private function reinitEdits(): void
    {
        $items = app(CartService::class)->items();
        $this->editColors = [];
        $this->editSizes = [];
        $this->editQtys = [];
        foreach ($items as $index => $item) {
            $this->editColors[$index] = $item['color_code'];
            $this->editSizes[$index]  = $item['size'];
            $this->editQtys[$index]   = $item['qty'];
        }
    }

    public function render()
    {
        $cart = app(CartService::class);
        $items = $cart->enrichedItems();
        $total = $cart->total();
        $colors = Color::orderBy('name')->get();

        return view('livewire.cart.cart-page', compact('items', 'total', 'colors'))
            ->layout('layouts.app', ['title' => 'Carrinho']);
    }
}
