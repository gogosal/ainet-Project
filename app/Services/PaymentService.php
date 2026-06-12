<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentService
{
    public function process(string $type, string $reference, float $value): array
    {
        $url = config('services.payments.url');

        $response = Http::post($url, [
            'type' => $type,
            'reference' => $reference,
            'value' => round($value, 2),
        ]);

        if ($response->successful() && $response->status() === 201) {
            return ['success' => true];
        }

        if ($response->status() === 422) {
            $errorDetails = $response->json('message') ?? 'Pagamento rejeitado pela entidade externa.';
            return ['success' => false, 'message' => $errorDetails];
        }

        return [
            'success' => false,
            'message' => 'Erro de comunicação com a plataforma de pagamentos. Tente mais tarde.'
        ];
    }
}
