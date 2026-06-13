<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class OrderService
{
    public function closeOrder(Order $order): void
    {
        $order->load(['items.tshirtImage', 'items.color', 'customer.user']);

        $pdfPath = $this->generateReceipt($order);

        $order->update([
            'status'      => 'closed',
            'receipt_url' => $pdfPath,
        ]);

        try {
            \Mail::to($order->customer->user->email)
                ->send(new \App\Mail\OrderClosedMail($order));
        } catch (\Exception $e) {
            Log::error("Failed to send closed email for order #{$order->id}: " . $e->getMessage());
        }
    }

    public function generateReceipt(Order $order): string
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '120');

        $order->loadMissing(['items.tshirtImage', 'items.color', 'customer.user']);

        $preparedItems = $this->prepareItemsForPdf($order);

        $pdf = \PDF::loadView('pdf.receipt', [
            'order' => $order,
            'preparedItems' => $preparedItems
        ]);

        $filename = "receipt_{$order->id}.pdf";
        $path = "pdf_receipts/{$filename}";

        \Illuminate\Support\Facades\Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    private function prepareItemsForPdf(Order $order): \Illuminate\Support\Collection
    {
        return $order->items->map(function ($item) {
            $imgPath = $this->resolveImagePath($item);
            $imgSrc = null;

            if ($imgPath !== null && file_exists($imgPath)) {
                $manager = ImageManager::usingDriver(Driver::class);
                $image = $manager->decode($imgPath);

                $image->scale(width: 80);

                $jpeg = $image->encodeUsingFormat(Format::JPEG, quality: 60);
                $imgSrc = 'data:image/jpeg;base64,' . base64_encode((string) $jpeg);

                unset($image);
                unset($manager);
            }

            return [
                'item' => $item,
                'hasImage' => $imgSrc !== null,
                'imgSrc' => $imgSrc,
            ];
        });
    }

    private function resolveImagePath($item): ?string
    {
        if (! $item->tshirtImage) {
            return null;
        }

        $url = $item->tshirtImage->image_url;

        if (str_starts_with($url, 'tshirt_images_private')) {
            return storage_path('app/private/' . $url);
        }

        if (str_contains($url, '/')) {
            return storage_path('app/public/' . $url);
        }

        return storage_path('app/public/tshirt_images/' . basename($url));
    }
}
