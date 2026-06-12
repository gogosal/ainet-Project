<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'image' => $this->isMethod('POST') ? 'required|image|max:2048' : 'nullable|image|max:2048',
        ];
    }
}
