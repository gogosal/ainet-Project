<div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="color:#1a1a1a;font-size:1.6rem;font-weight:700;margin:0 0 0.25rem;">As minhas encomendas</h1>
            <p style="color:#aaa;font-size:0.9rem;margin:0;">Histórico de compras e estado das encomendas</p>
        </div>
        <div style="display:flex;gap:0.5rem;">
            @foreach(['' => 'Todas', 'pending' => 'Pendentes', 'closed' => 'Fechadas', 'canceled' => 'Anuladas'] as $val => $label)
                <button wire:click="$set('statusFilter', '{{ $val }}')"
                        style="background:{{ $statusFilter === $val ? 'rgba(124,111,160,.2)' : '#ffffff' }};color:{{ $statusFilter === $val ? '#7c6fa0' : '#aaa' }};border:1px solid {{ $statusFilter === $val ? '#7c6fa0' : '#e0ddd8' }};border-radius:2px;padding:0.3rem 0.9rem;font-size:0.8rem;cursor:pointer;">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    @if($orders->isEmpty())
        <div style="text-align:center;padding:4rem 2rem;background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;">
            <p style="color:#888;font-size:1rem;margin:0 0 1rem;">Ainda não tens encomendas.</p>
            <a href="{{ route('catalog') }}" style="background:#7c6fa0;color:white;text-decoration:none;border-radius:8px;padding:0.65rem 1.5rem;font-size:0.9rem;font-weight:600;">Ver catálogo →</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order->id) }}"
                   style="display:block;background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.25rem 1.5rem;text-decoration:none;transition:border-color .2s;"
                   onmouseover="this.style.borderColor='#7c6fa0'" onmouseout="this.style.borderColor='#e0ddd8'">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.4rem;">
                                <span style="color:#1a1a1a;font-weight:600;font-size:0.95rem;">#{{ $order->id }}</span>
                                <x-status-badge :status="$order->status" />
                            </div>
                            <p style="color:#aaa;font-size:0.82rem;margin:0;">
                                {{ $order->date->format('d/m/Y') }} · {{ $order->items->count() }} artigo{{ $order->items->count() !== 1 ? 's' : '' }}
                            </p>
                        </div>
                        <div style="display:flex;align-items:center;gap:1rem;">
                            <span style="color:#7c6fa0;font-weight:700;font-size:1.05rem;">€{{ number_format($order->total_price, 2) }}</span>
                            @if($order->isClosed() && $order->receipt_url)
                                <a href="{{ route('orders.receipt', $order->id) }}" target="_blank"
                                   style="background:rgba(124,111,160,.2);color:#7c6fa0;text-decoration:none;border:1px solid rgba(124,111,160,.2);border-radius:6px;padding:0.3rem 0.75rem;font-size:0.78rem;font-weight:500;"
                                   onclick="event.stopPropagation()">
                                    📄 Recibo
                                </a>
                            @endif
                            <svg style="width:16px;height:16px;color:#aaa" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:1.5rem;">{{ $orders->links() }}</div>
    @endif
</div>
