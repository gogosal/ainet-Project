<div>
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
        <a href="{{ route('orders.index') }}" style="color:#aaa;text-decoration:none;font-size:0.85rem;" onmouseover="this.style.color='#7c6fa0'" onmouseout="this.style.color='#aaa'">← As minhas encomendas</a>
        <span style="color:#e0ddd8;">|</span>
        <h1 style="color:#1a1a1a;font-size:1.3rem;font-weight:700;margin:0;">Encomenda #{{ $order->id }}</h1>
        <x-status-badge :status="$order->status" />
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
        {{-- Items --}}
        <div>
            <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;">
                <div style="padding:1rem 1.5rem;border-bottom:1px solid #e0ddd8;">
                    <h3 style="color:#1a1a1a;font-size:0.95rem;font-weight:600;margin:0;">Artigos encomendados</h3>
                </div>
                @foreach($order->items as $item)
                    <div style="display:flex;align-items:center;gap:1rem;padding:1rem 1.5rem;border-bottom:1px solid #0d0d1a;">
                        <x-tshirt-preview
                            :colorCode="$item->color_code"
                            :imageUrl="$item->tshirtImage?->image_url"
                            size="64px" />
                        <div style="flex:1;">
                            <p style="color:#1a1a1a;font-size:0.9rem;font-weight:500;margin:0 0 0.2rem;">{{ $item->tshirtImage?->name ?? 'Design removido' }}</p>
                            <p style="color:#aaa;font-size:0.8rem;margin:0;">{{ $item->color?->name ?? $item->color_code }} · {{ $item->size }} · {{ $item->qty }}x</p>
                        </div>
                        <div style="text-align:right;">
                            <p style="color:#7c6fa0;font-weight:600;font-size:0.9rem;margin:0;">€{{ number_format($item->sub_total, 2) }}</p>
                            <p style="color:#aaa;font-size:0.75rem;margin:0.15rem 0 0;">€{{ number_format($item->unit_price, 2) }}/un</p>
                        </div>
                    </div>
                @endforeach
                <div style="padding:1rem 1.5rem;display:flex;justify-content:flex-end;align-items:center;gap:0.5rem;">
                    <span style="color:#888;font-size:0.9rem;">Total:</span>
                    <span style="color:#7c6fa0;font-weight:700;font-size:1.15rem;">€{{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>

            @if($order->reason_for_cancellation)
                <div style="margin-top:1rem;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:1rem 1.25rem;">
                    <p style="color:#f87171;font-size:0.82rem;font-weight:500;margin:0 0 0.25rem;">Motivo de anulação:</p>
                    <p style="color:#888;font-size:0.85rem;margin:0;">{{ $order->reason_for_cancellation }}</p>
                </div>
            @endif
        </div>

        {{-- Details --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.25rem;">
                <h4 style="color:#888;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;font-weight:600;margin:0 0 0.75rem;">Detalhes</h4>
                <div style="display:flex;flex-direction:column;gap:0.5rem;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#aaa;font-size:0.82rem;">Data</span>
                        <span style="color:#888;font-size:0.82rem;">{{ $order->date->format('d/m/Y') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#aaa;font-size:0.82rem;">NIF</span>
                        <span style="color:#888;font-size:0.82rem;">{{ $order->nif ?: '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#aaa;font-size:0.82rem;">Pagamento</span>
                        <span style="color:#888;font-size:0.82rem;">{{ $order->payment_type }}</span>
                    </div>
                </div>
            </div>

            <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.25rem;">
                <h4 style="color:#888;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;font-weight:600;margin:0 0 0.5rem;">Morada de entrega</h4>
                <p style="color:#888;font-size:0.85rem;margin:0;line-height:1.5;">{{ $order->address }}</p>
            </div>

            @if($order->notes)
                <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.25rem;">
                    <h4 style="color:#888;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;font-weight:600;margin:0 0 0.5rem;">Notas</h4>
                    <p style="color:#888;font-size:0.85rem;margin:0;">{{ $order->notes }}</p>
                </div>
            @endif

            @if($order->isClosed() && $order->receipt_url)
                <a href="{{ route('orders.receipt', $order->id) }}" target="_blank"
                   style="display:block;text-align:center;background:rgba(124,111,160,.2);color:#7c6fa0;text-decoration:none;border:1px solid rgba(124,111,160,.2);border-radius:8px;padding:0.75rem;font-size:0.875rem;font-weight:600;">
                    📄 Descarregar recibo PDF
                </a>
            @endif
        </div>
    </div>
</div>
