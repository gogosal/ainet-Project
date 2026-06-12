<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email,' . auth()->id(),
            'gender'               => 'nullable|in:M,F',
            'nif'                  => 'nullable|numeric|digits:9',
            'address'              => 'nullable|string|max:500',
            'default_payment_type' => 'nullable|in:Visa,PayPal,MB WAY',
            'default_payment_ref'  => 'nullable|string|max:255',
            'photo'                => 'nullable|image|max:2048',
        ];
    }
}
