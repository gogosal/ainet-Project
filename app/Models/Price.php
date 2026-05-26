<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    public $timestamps = false;
    protected $fillable = ['unit_price_catalog', 'unit_price_own', 'unit_price_catalog_discount', 'unit_price_own_discount', 'qty_discount', 'custom'];

    protected function casts(): array
    {
        return [
            'unit_price_catalog' => 'decimal:2',
            'unit_price_own' => 'decimal:2',
            'unit_price_catalog_discount' => 'decimal:2',
            'unit_price_own_discount' => 'decimal:2',
        ];
    }

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'unit_price_catalog' => 15.00,
            'unit_price_own' => 20.00,
            'unit_price_catalog_discount' => 12.00,
            'unit_price_own_discount' => 17.00,
            'qty_discount' => 10,
        ]);
    }

    public function priceForItem(bool $isOwn, int $qty): float
    {
        if ($isOwn) {
            return $qty >= $this->qty_discount
                ? (float)$this->unit_price_own_discount
                : (float)$this->unit_price_own;
        }
        return $qty >= $this->qty_discount
            ? (float)$this->unit_price_catalog_discount
            : (float)$this->unit_price_catalog;
    }
}
