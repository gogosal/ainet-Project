<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $rules = [
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|max:4096',
        ];

        if ($this->isMethod('POST')) {
            $rules['code'] = 'required|string|max:20|unique:colors,code';
        }

        return $rules;
    }
}
