<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tshirt_image_id' => 'required|exists:tshirt_images,id',
            'color_code'      => 'required|exists:colors,code',
            'size'            => 'required|in:XS,S,M,L,XL',
            'qty'             => 'required|integer|min:1|max:99',
            'side'            => 'nullable|in:front,back',
        ];
    }
}
