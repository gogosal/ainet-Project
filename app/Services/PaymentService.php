<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentService
{
    private const API_URL = 'https://ainet-payments-api.vercel.app/api/payments';

    public function process(string $type, string $reference, float $value): array
    {
        try {
            $response = Http::timeout(10)->post(self::API_URL, [
                'type'      => $type,
                'reference' => $reference,
                'value'     => round($value, 2),
            ]);

            if ($response->status() === 201) {
                return ['success' => true];
            }

            $body = $response->json();
            $message = $body['message'] ?? 'Pagamento recusado pela plataforma.';

            return ['success' => false, 'message' => $message];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erro ao comunicar com a plataforma de pagamentos.'];
        }
    }
}
