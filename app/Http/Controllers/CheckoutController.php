<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(): View
    {
        $cart = app(CartService::class);
        $items = $cart->enrichedItems();
        $total = $cart->total();

        $customer = auth()->user()->customer;
        $defaults = [
            'nif'          => $customer?->nif ?? '',
            'address'      => $customer?->address ?? '',
            'payment_type' => $customer?->default_payment_type ?? 'Visa',
            'payment_ref'  => $customer?->default_payment_ref ?? '',
        ];

        return view('checkout.index', compact('items', 'total', 'defaults'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = app(CartService::class);
        $items = $cart->enrichedItems();

        if (empty($items)) {
            return redirect()->route('catalog');
        }

        $total = $cart->total();
        $data = $request->validated();

        $payment = app(PaymentService::class)->process($data['payment_type'], $data['payment_ref'], $total);

        if (! $payment['success']) {
            return back()->withErrors(['payment_ref' => $payment['message']])->withInput();
        }

        $order = DB::transaction(function () use ($items, $total, $data) {
            $customer = auth()->user()->customer;

            $order = Order::create([
                'status'       => 'pending',
                'customer_id'  => $customer->id,
                'date'         => now()->toDateString(),
                'total_price'  => $total,
                'nif'          => $data['nif'],
                'address'      => $data['address'],
                'payment_type' => $data['payment_type'],
                'payment_ref'  => $data['payment_ref'],
                'notes'        => $data['notes'] ?? null,
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

        try {
            \Mail::to(auth()->user()->email)->send(new \App\Mail\OrderPendingMail($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send pending email: ' . $e->getMessage());
        }

        return redirect()->route('orders.show', $order->id);
    }
}
