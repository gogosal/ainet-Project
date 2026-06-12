<?php

namespace App\Livewire\Catalog;

use App\Models\Category;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use App\Services\CartService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class CatalogPage extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $categoryId = null;
    public bool $showModal = false;
    public ?int $selectedImageId = null;
    public string $selectedColor = '';
    public string $selectedSize = 'M';
    public int $qty = 1;
    public $unitPrice = 10.00;
    public $discountPrice = 8.50;
    public $qtyThreshold = 5;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function incrementQty()
    {
        if ($this->qty < 99) $this->qty++;
    }

    public function decrementQty()
    {
        if ($this->qty > 1) $this->qty--;
    }

    public function openModal(int $imageId): void
    {
        $this->selectedImageId = $imageId;
        $this->selectedColor = Color::first()?->code ?? '';
        $this->selectedSize = 'M';
        $this->qty = 1;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedImageId = null;
    }

    public function addToCart(): void
    {
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
        $this->closeModal();
        session()->flash('cart_success', 'Produto adicionado ao carrinho!');
    }

    public function render()
    {
        // 1. Descobrir se há um cliente logado e qual é o seu ID
        $customerId = Auth::check() ? Auth::user()->customer?->id : null;

        $images = TshirtImage::query()
            // 2. A MUDANÇA ESTÁ AQUI: Mostrar as públicas (NULL) e as do Cliente (se estiver logado)
            ->where(function ($query) use ($customerId) {
                $query->whereNull('customer_id'); // Imagens públicas da loja

                if ($customerId) {
                    $query->orWhere('customer_id', $customerId); // + as tuas imagens privadas
                }
            })
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            }))
            ->when($this->categoryId === -1, fn($q) => $q->whereNull('category_id'))
            ->when($this->categoryId > 0, fn($q) => $q->where('category_id', $this->categoryId))
            ->with('category')
            ->orderBy('name')
            ->paginate(12);

        $categories = Category::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();
        $prices = Price::current();
        $selectedImage = $this->selectedImageId
            ? TshirtImage::find($this->selectedImageId)
            : null;

        return view('livewire.catalog.catalog-page', compact(
            'images',
            'categories',
            'colors',
            'prices',
            'selectedImage'
        ))->layout('layouts.app', ['title' => 'Catálogo']);
    }
}
