<div style="padding:1.5rem;">

    {{-- Back link --}}
    <div style="margin-bottom:1.25rem;">
        <a href="{{ route('admin.orders') }}" style="color:#888;font-size:0.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;">
            ← Voltar às encomendas
        </a>
    </div>

    {{-- Flash --}}
    @if (session()->has('success'))
        <div style="background:rgba(34,197,94,.08);border:1px solid rgba(22,163,74,.25);color:#16a34a;padding:0.75rem;border-radius:1px;margin-bottom:1rem;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Order Header --}}
    <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="color:#1a1a1a;font-size:1.4rem;font-weight:700;margin:0 0 0.25rem;">Encomenda #{{ $order->id }}</h1>
                <p style="color:#888;font-size:0.85rem;margin:0;">
                    {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                {{-- Status badge --}}
                @if ($order->status === 'pending')
                    <span style="background:rgba(234,179,8,.1);color:#fbbf24;border:1px solid rgba(234,179,8,.2);border-radius:1px;padding:0.3rem 0.85rem;font-size:0.8rem;font-weight:600;">Pendente</span>
                @elseif ($order->status === 'closed')
                    <span style="background:rgba(34,197,94,.08);color:#16a34a;border:1px solid rgba(22,163,74,.25);border-radius:1px;padding:0.3rem 0.85rem;font-size:0.8rem;font-weight:600;">Fechada</span>
                @elseif ($order->status === 'canceled')
                    <span style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2);border-radius:1px;padding:0.3rem 0.85rem;font-size:0.8rem;font-weight:600;">Cancelada</span>
                @endif

                {{-- Actions for pending --}}
                @if ($order->isPending())
                    <button
                        wire:click="closeOrder"
                        wire:confirm="Fechar esta encomenda?"
                        style="background:rgba(34,197,94,.12);color:#16a34a;border:1px solid rgba(34,197,94,.3);border-radius:1px;padding:0.45rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;"
                    >Fechar encomenda</button>
                    <button
                        wire:click="$set('showCancelModal', true)"
                        style="background:rgba(239,68,68,.12);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:1px;padding:0.45rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;"
                    >Cancelar encomenda</button>
                @endif

                {{-- Receipt link --}}
                @if ($order->receipt_url)
                    <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank"
                       style="background:rgba(124,111,160,.12);color:#7c6fa0;border:1px solid rgba(124,111,160,.3);border-radius:1px;padding:0.45rem 1rem;font-size:0.85rem;font-weight:600;text-decoration:none;">
                        Recibo PDF
                    </a>
                @endif
            </div>
        </div>

        {{-- Client info --}}
        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #e0ddd8;display:flex;gap:2rem;flex-wrap:wrap;">
            <div>
                <p style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Cliente</p>
                <p style="color:#1a1a1a;font-size:0.9rem;margin:0;">{{ $order->customer?->user?->name ?? '—' }}</p>
            </div>
            <div>
                <p style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Email</p>
                <p style="color:#1a1a1a;font-size:0.9rem;margin:0;">{{ $order->customer?->user?->email ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Two column layout --}}
    <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;">

        {{-- Left: Items table --}}
        <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #e0ddd8;">
                <h2 style="color:#1a1a1a;font-size:1rem;font-weight:600;margin:0;">Itens da encomenda</h2>
            </div>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f9f8f6;">
                        <th style="padding:0.65rem 1rem;text-align:left;color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Imagem</th>
                        <th style="padding:0.65rem 1rem;text-align:left;color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Cor</th>
                        <th style="padding:0.65rem 1rem;text-align:left;color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Tam.</th>
                        <th style="padding:0.65rem 1rem;text-align:right;color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Qtd.</th>
                        <th style="padding:0.65rem 1rem;text-align:right;color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Preço unit.</th>
                        <th style="padding:0.65rem 1rem;text-align:right;color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e0ddd8;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($order->items as $item)
                        <tr>
                            <td style="padding:0.65rem 1rem;border-bottom:1px solid #e0ddd8;color:#1a1a1a;font-size:0.85rem;">
                                {{ $item->tshirtImage?->name ?? '—' }}
                            </td>
                            <td style="padding:0.65rem 1rem;border-bottom:1px solid #e0ddd8;font-size:0.85rem;">
                                <div style="display:flex;align-items:center;gap:0.4rem;">
                                    @if ($item->color_code)
                                        <span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:{{ $item->color_code }};border:1px solid rgba(255,255,255,.15);flex-shrink:0;"></span>
                                    @endif
                                    <span style="color:#888;">{{ $item->color?->name ?? $item->color_code ?? '—' }}</span>
                                </div>
                            </td>
                            <td style="padding:0.65rem 1rem;border-bottom:1px solid #e0ddd8;color:#888;font-size:0.85rem;">{{ $item->size ?? '—' }}</td>
                            <td style="padding:0.65rem 1rem;border-bottom:1px solid #e0ddd8;color:#1a1a1a;font-size:0.85rem;text-align:right;">{{ $item->qty }}</td>
                            <td style="padding:0.65rem 1rem;border-bottom:1px solid #e0ddd8;color:#888;font-size:0.85rem;text-align:right;">{{ number_format($item->unit_price, 2, ',', '.') }}€</td>
                            <td style="padding:0.65rem 1rem;border-bottom:1px solid #e0ddd8;color:#1a1a1a;font-size:0.85rem;font-weight:600;text-align:right;">{{ number_format($item->sub_total, 2, ',', '.') }}€</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:1.5rem;text-align:center;color:#888;font-size:0.85rem;">Sem itens.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Right: Order summary --}}
        <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.5rem;">
            <h2 style="color:#1a1a1a;font-size:1rem;font-weight:600;margin:0 0 1.25rem;">Resumo</h2>

            <div style="display:flex;flex-direction:column;gap:0.85rem;">
                <div>
                    <p style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Morada</p>
                    <p style="color:#1a1a1a;font-size:0.85rem;margin:0;">{{ $order->address ?? '—' }}</p>
                </div>
                <div>
                    <p style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">NIF</p>
                    <p style="color:#1a1a1a;font-size:0.85rem;margin:0;">{{ $order->nif ?? '—' }}</p>
                </div>
                <div>
                    <p style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Forma de pagamento</p>
                    <p style="color:#1a1a1a;font-size:0.85rem;margin:0;">{{ $order->payment_type ?? '—' }}</p>
                </div>
                @if ($order->payment_ref)
                    <div>
                        <p style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Referência de pagamento</p>
                        <p style="color:#1a1a1a;font-size:0.85rem;margin:0;">{{ $order->payment_ref }}</p>
                    </div>
                @endif
                @if ($order->notes)
                    <div>
                        <p style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Notas</p>
                        <p style="color:#1a1a1a;font-size:0.85rem;margin:0;">{{ $order->notes }}</p>
                    </div>
                @endif

                <div style="border-top:1px solid #e0ddd8;padding-top:0.85rem;margin-top:0.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <p style="color:#888;font-size:0.85rem;margin:0;">Total</p>
                        <p style="color:#1a1a1a;font-size:1.1rem;font-weight:700;margin:0;">{{ number_format($order->total_price, 2, ',', '.') }}€</p>
                    </div>
                </div>

                @if ($order->isCanceled() && $order->reason_for_cancellation)
                    <div style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.2);border-radius:1px;padding:0.85rem;margin-top:0.25rem;">
                        <p style="color:#f87171;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.3rem;">Motivo do cancelamento</p>
                        <p style="color:#fca5a5;font-size:0.85rem;margin:0;">{{ $order->reason_for_cancellation }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Cancel Modal --}}
    @if ($showCancelModal)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:2rem;width:100%;max-width:480px;">
                <h2 style="color:#1a1a1a;font-size:1.1rem;font-weight:700;margin:0 0 0.5rem;">Cancelar encomenda #{{ $order->id }}</h2>
                <p style="color:#888;font-size:0.85rem;margin:0 0 1.25rem;">Indique o motivo do cancelamento.</p>

                <textarea
                    wire:model="cancelReason"
                    placeholder="Motivo do cancelamento..."
                    rows="4"
                    style="width:100%;box-sizing:border-box;background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.65rem 0.75rem;font-size:0.9rem;outline:none;resize:vertical;"
                ></textarea>
                @error('cancelReason')
                    <p style="color:#f87171;font-size:0.8rem;margin:0.4rem 0 0;">{{ $message }}</p>
                @enderror

                <div style="display:flex;justify-content:flex-end;gap:0.75rem;margin-top:1.25rem;">
                    <button
                        wire:click="$set('showCancelModal', false)"
                        style="background:#ffffff;color:#888;border:1px solid #e0ddd8;border-radius:1px;padding:0.5rem 1.25rem;cursor:pointer;font-size:0.875rem;font-weight:600;"
                    >Fechar</button>
                    <button
                        wire:click="cancelOrder"
                        style="background:#dc2626;color:white;border:none;border-radius:1px;padding:0.5rem 1.25rem;cursor:pointer;font-size:0.875rem;font-weight:600;"
                    >Confirmar cancelamento</button>
                </div>
            </div>
        </div>
    @endif

</div>
