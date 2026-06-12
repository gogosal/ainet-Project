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
        $isEdit = $this->route('category') !== null;

        return [
            'name'  => 'required|string|max:255',
            'image' => $isEdit ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ];
    }
}
