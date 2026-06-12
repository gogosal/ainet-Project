<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'unit_price_catalog'          => 'required|numeric|min:0',
            'unit_price_own'              => 'required|numeric|min:0',
            'unit_price_catalog_discount' => 'required|numeric|min:0',
            'unit_price_own_discount'     => 'required|numeric|min:0',
            'qty_discount'                => 'required|integer|min:1',
        ];
    }
}
