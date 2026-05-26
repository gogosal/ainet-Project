<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Price;

class AdminPricesPage extends Component
{
    public float $unitPriceCatalog = 0;
    public float $unitPriceOwn = 0;
    public float $unitPriceCatalogDiscount = 0;
    public float $unitPriceOwnDiscount = 0;
    public int $qtyDiscount = 10;

    public function mount(): void
    {
        $price = Price::current();
        $this->unitPriceCatalog = (float)$price->unit_price_catalog;
        $this->unitPriceOwn = (float)$price->unit_price_own;
        $this->unitPriceCatalogDiscount = (float)$price->unit_price_catalog_discount;
        $this->unitPriceOwnDiscount = (float)$price->unit_price_own_discount;
        $this->qtyDiscount = (int)$price->qty_discount;
    }

    public function save(): void
    {
        $this->validate([
            'unitPriceCatalog' => 'required|numeric|min:0',
            'unitPriceOwn' => 'required|numeric|min:0',
            'unitPriceCatalogDiscount' => 'required|numeric|min:0',
            'unitPriceOwnDiscount' => 'required|numeric|min:0',
            'qtyDiscount' => 'required|integer|min:1',
        ]);
        Price::current()->update([
            'unit_price_catalog' => $this->unitPriceCatalog,
            'unit_price_own' => $this->unitPriceOwn,
            'unit_price_catalog_discount' => $this->unitPriceCatalogDiscount,
            'unit_price_own_discount' => $this->unitPriceOwnDiscount,
            'qty_discount' => $this->qtyDiscount,
        ]);
        session()->flash('success', 'Preços atualizados.');
    }

    public function render()
    {
        return view('livewire.admin.admin-prices-page')
            ->layout('layouts.admin', ['title' => 'Preços']);
    }
}
