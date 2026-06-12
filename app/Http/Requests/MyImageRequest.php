<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $isCreate = $this->isMethod('POST');

        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => $isCreate ? 'required|image|max:4096' : 'nullable|mimes:jpeg,png,jpg,webp|image|max:4096',
        ];
    }
}
