<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = app(CartService::class);
        $items = $cart->enrichedItems();
        $total = $cart->total();
        $colors = Color::orderBy('name')->get();

        return view('cart.index', compact('items', 'total', 'colors'));
    }

    public function update(Request $request, int $index): RedirectResponse
    {
        $validated = $request->validate([
            'color_code' => 'required|exists:colors,code',
            'size'       => 'required|in:XS,S,M,L,XL',
            'qty'        => 'required|integer|min:1|max:99',
        ]);

        app(CartService::class)->update($index, $validated['color_code'], $validated['size'], (int) $validated['qty']);

        return back();
    }

    public function destroy(int $index): RedirectResponse
    {
        app(CartService::class)->remove($index);

        return back();
    }

    public function clear(): RedirectResponse
    {
        app(CartService::class)->clear();

        return back();
    }
}
