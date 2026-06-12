<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        $isEdit = $userId !== null;

        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email' . ($userId ? ',' . $userId : ''),
            'user_type' => 'required|in:F,A',
            'gender'    => 'nullable|in:M,F',
            'password'  => $isEdit ? 'nullable|min:8' : 'required|min:8',
        ];
    }
}
