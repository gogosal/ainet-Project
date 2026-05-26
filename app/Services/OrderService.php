<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function closeOrder(Order $order): void
    {
        $order->load(['items.tshirtImage', 'items.color', 'customer.user']);

        // Generate PDF receipt
        $pdfPath = $this->generateReceipt($order);

        // Update order
        $order->update([
            'status'      => 'closed',
            'receipt_url' => $pdfPath,
        ]);

        // Send email with receipt
        try {
            \Mail::to($order->customer->user->email)
                ->send(new \App\Mail\OrderClosedMail($order));
        } catch (\Exception $e) {
            Log::error("Failed to send closed email for order #{$order->id}: " . $e->getMessage());
        }
    }

    public function generateReceipt(Order $order): string
    {
        $order->loadMissing(['items.tshirtImage', 'items.color', 'customer.user']);

        $pdf = \PDF::loadView('pdf.receipt', ['order' => $order]);

        $filename = "receipt_{$order->id}.pdf";
        $path = "pdf_receipts/{$filename}";

        \Illuminate\Support\Facades\Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }
}
