<?php

namespace App\Livewire\VirtualTryOn;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use App\Services\CartService;
use Livewire\Component;

class VirtualTryOnPage extends Component
{
    public ?int $selectedImageId = null;
    public string $selectedColor = '';
    public string $selectedSize = 'M';
    public int $qty = 1;
    public string $cartMessage = '';

    public function mount(?int $design = null): void
    {
        $first = TshirtImage::whereNull('customer_id')->first();
        $this->selectedImageId = $design ?? $first?->id;
        $this->selectedColor = Color::first()?->code ?? 'white';
    }

    public function selectDesign(int $id): void
    {
        $this->selectedImageId = $id;
        $this->cartMessage = '';
    }

    public function selectColor(string $code): void
    {
        $this->selectedColor = $code;
    }

    public function addToCart(): void
    {
        if (!$this->selectedImageId) {
            return;
        }

        $this->validate([
            'selectedColor' => 'required|exists:colors,code',
            'selectedSize'  => 'required|in:XS,S,M,L,XL',
            'qty'           => 'required|integer|min:1|max:99',
        ]);

        app(CartService::class)->add(
            $this->selectedImageId,
            $this->selectedColor,
            $this->selectedSize,
            $this->qty
        );

        $this->dispatch('cart-updated');
        $this->cartMessage = 'Adicionado ao carrinho!';
    }

    public function render()
    {
        $designs = TshirtImage::whereNull('customer_id')
            ->with('category')
            ->orderBy('name')
            ->get();

        $colors = Color::orderBy('name')->get();
        $prices = Price::current();
        $selectedImage = $this->selectedImageId
            ? TshirtImage::find($this->selectedImageId)
            : null;

        return view('livewire.virtual-try-on.virtual-try-on-page', compact(
            'designs', 'colors', 'prices', 'selectedImage'
        ))->layout('layouts.app', ['title' => 'Provador 3D']);
    }
}
