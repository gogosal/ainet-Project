<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartItemRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Price;
use App\Models\TshirtImage;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $categoryId = $request->query('category');

        $images = TshirtImage::query()
            ->whereNull('customer_id')
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('description', 'like', '%'.$search.'%');
            }))
            ->when($categoryId === '-1', fn ($q) => $q->whereNull('category_id'))
            ->when($categoryId > 0, fn ($q) => $q->where('category_id', $categoryId))
            ->with('category')
            ->orderBy('name')
            ->paginate(12)
            ->appends($request->query());

        $categories = Category::orderBy('name')->get();
        $colors = Color::orderBy('name')->get();
        $prices = Price::current();

        return view('catalog.index', compact('images', 'categories', 'colors', 'prices', 'search', 'categoryId'));
    }

    public function addToCart(CartItemRequest $request): RedirectResponse
    {
        app(CartService::class)->add(
            $request->integer('tshirt_image_id'),
            $request->string('color_code'),
            $request->string('size'),
            $request->integer('qty'),
        );

        return back()->with('cart_success', 'Produto adicionado ao carrinho!');
    }
}
