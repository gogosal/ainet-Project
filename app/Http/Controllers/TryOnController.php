<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemRequest;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TryOnController extends Controller
{
    public function index(Request $request): View
    {
        $designs = TshirtImage::whereNull('customer_id')
            ->with('category')
            ->orderBy('name')
            ->get();

        $colors = Color::orderBy('name')->get();
        $prices = Price::current();

        $selectedImageId = $request->query('design');
        $selectedColor = $request->query('color', '');
        $selectedSize = in_array($request->query('size'), ['XS', 'S', 'M', 'L', 'XL'])
            ? $request->query('size')
            : 'M';
        $selectedSide = in_array($request->query('side'), ['front', 'back'])
            ? $request->query('side')
            : 'front';

        if (empty($selectedColor)) {
            $selectedColor = Color::where('code', 'fafafa')->first()?->code
                ?? Color::where('name', 'Branco')->first()?->code
                ?? 'fafafa';
        }

        $selectedImage = $selectedImageId
            ? TshirtImage::whereNull('customer_id')->find($selectedImageId)
            : null;

        return view('try-on.index', compact(
            'designs', 'colors', 'prices', 'selectedImage',
            'selectedImageId', 'selectedColor', 'selectedSize', 'selectedSide'
        ));
    }

    public function addToCart(CartItemRequest $request): RedirectResponse
    {
        app(CartService::class)->add(
            $request->integer('tshirt_image_id'),
            $request->string('color_code'),
            $request->string('size'),
            $request->integer('qty'),
            $request->string('side', 'front'),
        );

        return back()->with('cart_success', 'Adicionado ao carrinho!');
    }
}
