<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class OrderCanceledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->load(['customer.user', 'items.tshirtImage', 'items.color']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'FunShirt — Encomenda #' . $this->order->id . ' anulada',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-canceled',
            with: [
                'items' => $this->prepareItems(),
            ],
        );
    }

    private function prepareItems(): Collection
    {
        ini_set('memory_limit', '512M');

        return $this->order->items->map(function ($item) {
            $imgPath = $this->resolveImagePath($item);
            $imgSrc  = null;

            if ($imgPath !== null && file_exists($imgPath)) {

                $manager = ImageManager::usingDriver(Driver::class);

                $image = $manager->decode($imgPath);

                $image->scale(width: 120);

                $jpeg = $image->encodeUsingFormat(Format::JPEG, quality: 60);

                $imgSrc = 'data:image/jpeg;base64,' . base64_encode((string) $jpeg);

                unset($image);
                unset($manager);
            }

            return [
                'item'     => $item,
                'hasImage' => $imgSrc !== null,
                'imgSrc'   => $imgSrc,
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
