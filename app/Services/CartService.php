<?php

namespace App\Services;

use App\Models\Price;
use App\Models\TshirtImage;
use App\Models\Color;

class CartService
{
    public function items(): array
    {
        return session('cart', []);
    }

    public function count(): int
    {
        return array_sum(array_column($this->items(), 'qty'));
    }

    public function add(int $imageId, string $colorCode, string $size, int $qty): void
    {
        $cart = $this->items();
        foreach ($cart as &$item) {
            if ($item['tshirt_image_id'] === $imageId && $item['color_code'] === $colorCode && $item['size'] === $size) {
                $item['qty'] += $qty;
                session(['cart' => $cart]);
                return;
            }
        }
        $cart[] = compact('imageId', 'colorCode', 'size', 'qty') + [
            'tshirt_image_id' => $imageId,
            'color_code' => $colorCode,
            'size' => $size,
            'qty' => $qty,
        ];
        session(['cart' => $cart]);
    }

    public function update(int $index, string $colorCode, string $size, int $qty): void
    {
        $cart = $this->items();
        if (!isset($cart[$index])) return;
        if ($qty <= 0) {
            $this->remove($index);
            return;
        }
        $cart[$index]['color_code'] = $colorCode;
        $cart[$index]['size'] = $size;
        $cart[$index]['qty'] = $qty;
        session(['cart' => $cart]);
    }

    public function remove(int $index): void
    {
        $cart = $this->items();
        array_splice($cart, $index, 1);
        session(['cart' => array_values($cart)]);
    }

    public function clear(): void
    {
        session()->forget('cart');
    }

    public function enrichedItems(): array
    {
        $items = $this->items();
        if (empty($items)) return [];

        $prices = Price::current();
        $imageIds = array_column($items, 'tshirt_image_id');
        $images = TshirtImage::whereIn('id', $imageIds)->get()->keyBy('id');
        $colors = Color::all()->keyBy('code');

        $enriched = [];
        foreach ($items as $index => $item) {
            $image = $images[$item['tshirt_image_id']] ?? null;
            $color = $colors[$item['color_code']] ?? null;
            $isOwn = $image && !is_null($image->customer_id);
            $unitPrice = $prices->priceForItem($isOwn, $item['qty']);
            $enriched[] = [
                'index' => $index,
                'tshirt_image_id' => $item['tshirt_image_id'],
                'color_code' => $item['color_code'],
                'size' => $item['size'],
                'qty' => $item['qty'],
                'image' => $image,
                'color' => $color,
                'unit_price' => $unitPrice,
                'sub_total' => round($unitPrice * $item['qty'], 2),
                'is_own' => $isOwn,
                'has_discount' => $item['qty'] >= $prices->qty_discount,
            ];
        }
        return $enriched;
    }

    public function total(): float
    {
        return array_sum(array_column($this->enrichedItems(), 'sub_total'));
    }
}
