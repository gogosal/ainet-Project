<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PriceRequest;
use App\Models\Price;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PriceController extends Controller
{
    public function edit(): View
    {
        $price = Price::current();

        return view('admin.prices.edit', compact('price'));
    }

    public function update(PriceRequest $request): RedirectResponse
    {
        Price::current()->update($request->validated());

        return back()->with('success', 'Preços atualizados.');
    }
}
