<?php

namespace App\Livewire\Checkout;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CheckoutPage extends Component
{
    public string $nif = '';
    public string $address = '';
    public string $paymentType = 'Visa';
    public string $paymentRef = '';
    public string $notes = '';
    public string $paymentError = '';

    public function mount(): void
    {
        $customer = auth()->user()->customer;
        if ($customer) {
            $this->nif         = $customer->nif ?? '';
            $this->address     = $customer->address ?? '';
            $this->paymentType = $customer->default_payment_type ?? 'Visa';
            $this->paymentRef  = $customer->default_payment_ref ?? '';
        }
    }

    public function updatedPaymentType(): void
    {
        $this->paymentRef = '';
        $this->paymentError = '';
    }

    protected function paymentRefRules(): string
    {
        return match ($this->paymentType) {
            'Visa'    => 'required|string|regex:/^4[0-9]{15}$/',
            'PayPal'  => 'required|email',
            'MB WAY'  => 'required|string|regex:/^9[0-9]{8}$/',
            default   => 'required|string',
        };
    }

    public function submit(): mixed
    {
        $this->paymentError = '';

        $this->validate([
            'nif'        => 'required|string|regex:/^[0-9]{9}$/',
            'address'    => 'required|string|min:5',
            'paymentType' => 'required|in:Visa,PayPal,MB WAY',
            'paymentRef' => $this->paymentRefRules(),
        ], [
            'nif.required'       => 'O NIF é obrigatório.',
            'nif.regex'          => 'O NIF deve ter exatamente 9 dígitos.',
            'address.required'   => 'A morada é obrigatória.',
            'paymentRef.required' => 'A referência de pagamento é obrigatória.',
            'paymentRef.regex'   => $this->paymentType === 'Visa'
                ? 'Cartão Visa inválido (16 dígitos, começa por 4).'
                : ($this->paymentType === 'MB WAY' ? 'Número MB WAY inválido (9 dígitos, começa por 9).' : 'Email PayPal inválido.'),
            'paymentRef.email'   => 'Email PayPal inválido.',
        ]);

        $cart = app(CartService::class);
        $items = $cart->enrichedItems();

        if (empty($items)) {
            $this->paymentError = 'O carrinho está vazio.';
            return null;
        }

        $total = $cart->total();

        $payment = app(PaymentService::class)->process($this->paymentType, $this->paymentRef, $total);

        if (!$payment['success']) {
            $this->paymentError = $payment['message'];
            return null;
        }

        $order = DB::transaction(function () use ($items, $total) {
            $customer = auth()->user()->customer;

            $order = Order::create([
                'status'       => 'pending',
                'customer_id'  => $customer->id,
                'date'         => now()->toDateString(),
                'total_price'  => $total,
                'nif'          => $this->nif,
                'address'      => $this->address,
                'payment_type' => $this->paymentType,
                'payment_ref'  => $this->paymentRef,
                'notes'        => $this->notes ?: null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'        => $order->id,
                    'tshirt_image_id' => $item['tshirt_image_id'],
                    'color_code'      => $item['color_code'],
                    'size'            => $item['size'],
                    'qty'             => $item['qty'],
                    'unit_price'      => $item['unit_price'],
                    'sub_total'       => $item['sub_total'],
                    'custom'          => ['side' => $item['side'] ?? 'front'],
                ]);
            }

            return $order;
        });

        app(CartService::class)->clear();
        $this->dispatch('cart-updated');

        try {
            \Mail::to(auth()->user()->email)->send(new \App\Mail\OrderPendingMail($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send pending email: ' . $e->getMessage());
        }

        return $this->redirect(route('orders.show', $order->id), navigate: true);
    }

    public function render()
    {
        $cart = app(CartService::class);
        $items = $cart->enrichedItems();
        $total = $cart->total();

        return view('livewire.checkout.checkout-page', compact('items', 'total'))
            ->layout('layouts.app', ['title' => 'Finalizar compra']);
    }
}
