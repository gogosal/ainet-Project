<?php

namespace App\Livewire\VirtualTryOn;

use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\Url;

class VirtualTryOnPage extends Component
{
    public ?int $selectedImageId = null;

    #[Url(as: 'color')]
    public string $selectedColor = '';

    #[Url(as: 'size')]
    public string $selectedSize = 'M';

    #[Url(as: 'side')]
    public string $selectedSide = 'front';

    public int $qty = 1;
    public string $cartMessage = '';

    public function mount(): void
    {
        // Read design from URL once on arrival (e.g. clicking "3D" in catalog).
        // Not persisted via #[Url] so refreshing always starts with no design.
        if ($this->selectedImageId === null) {
            $d = request()->query('design');
            if ($d && ctype_digit((string)$d)) {
                $this->selectedImageId = (int)$d;
            }
        }

        if (empty($this->selectedColor)) {
            $this->selectedColor = Color::where('code', 'fafafa')->first()?->code
                ?? Color::where('name', 'Branco')->first()?->code
                ?? 'fafafa';
        }
        if (!in_array($this->selectedSize, ['XS','S','M','L','XL'])) {
            $this->selectedSize = 'M';
        }
        if (!in_array($this->selectedSide, ['front','back'])) {
            $this->selectedSide = 'front';
        }
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

    public function selectSide(string $side): void
    {
        $this->selectedSide = in_array($side, ['front', 'back']) ? $side : 'front';
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
            $this->qty,
            $this->selectedSide
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
