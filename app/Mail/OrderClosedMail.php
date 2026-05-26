<?php
namespace App\Mail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class OrderClosedMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public Order $order) {}
    public function envelope(): Envelope { return new Envelope(subject: 'FunShirt — Encomenda #' . $this->order->id . ' enviada'); }
    public function content(): Content { return new Content(view: 'emails.order-closed'); }
    public function attachments(): array {
        if ($this->order->receipt_url) {
            $path = storage_path('app/private/' . $this->order->receipt_url);
            if (file_exists($path)) {
                return [Attachment::fromPath($path)->as('recibo.pdf')->withMime('application/pdf')];
            }
        }
        return [];
    }
}
