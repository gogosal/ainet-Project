<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Price;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        Price::create([
            'unit_price_catalog' => 15.00,
            'unit_price_own' => 20.00,
            'unit_price_catalog_discount' => 12.00,
            'unit_price_own_discount' => 17.00,
            'qty_discount' => 10,
        ]);
    }
}
