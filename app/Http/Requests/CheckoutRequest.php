<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isClient();
    }

    public function rules(): array
    {
        $paymentType = $this->input('payment_type');

        $paymentRefRule = match ($paymentType) {
            'Visa'   => 'required|string|regex:/^4[0-9]{15}$/',
            'PayPal' => 'required|email',
            'MB WAY' => 'required|string|regex:/^9[0-9]{8}$/',
            default  => 'required|string',
        };

        return [
            'nif'          => 'required|string|regex:/^[0-9]{9}$/',
            'address'      => 'required|string|min:5',
            'payment_type' => 'required|in:Visa,PayPal,MB WAY',
            'payment_ref'  => $paymentRefRule,
            'notes'        => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'nif.regex'          => 'O NIF deve ter exatamente 9 dígitos.',
            'payment_ref.regex'  => $this->input('payment_type') === 'Visa'
                ? 'Cartão Visa inválido (16 dígitos, começa por 4).'
                : 'Número MB WAY inválido (9 dígitos, começa por 9).',
        ];
    }
}
