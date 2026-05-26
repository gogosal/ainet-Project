<div style="padding:1.5rem;">

    {{-- Flash --}}
    @if (session()->has('success'))
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#4ade80;padding:0.75rem;border-radius:6px;margin-bottom:1rem;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="color:#e2e8f0;font-size:1.5rem;font-weight:700;margin:0;">Encomendas</h1>
            <p style="color:#94a3b8;font-size:0.85rem;margin:0.25rem 0 0;">Gestão de encomendas</p>
        </div>
    </div>

    {{-- Search + Filters --}}
    <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Pesquisar por ID ou nome do cliente..."
                style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:300px;box-sizing:border-box;font-size:0.9rem;outline:none;"
            />
            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                @foreach(['all' => 'Todas', 'pending' => 'Pendentes', 'closed' => 'Fechadas', 'canceled' => 'Canceladas'] as $value => $label)
                    <button
                        wire:click="$set('statusFilter','{{ $value }}')"
                        style="border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;border:1px solid #1e1e30;{{ $statusFilter === $value ? 'background:#7c3aed;color:white;border-color:#7c3aed;' : 'background:#1a1a2e;color:#94a3b8;' }}"
                    >{{ $label }}</button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#0d0d1a;">
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">#</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Cliente</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Data</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Total</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Pagamento</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Estado</th>
                    <th style="padding:0.75rem 1rem;text-align:left;color:#94a3b8;font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #1e1e30;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#a78bfa;font-size:0.85rem;font-weight:600;">
                            <a href="{{ route('admin.orders.show', $order->id) }}" style="color:#a78bfa;text-decoration:none;">#{{ $order->id }}</a>
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#e2e8f0;font-size:0.85rem;">
                            {{ $order->customer?->user?->name ?? '—' }}
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#94a3b8;font-size:0.85rem;">
                            {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#e2e8f0;font-size:0.85rem;font-weight:600;">
                            {{ number_format($order->total_price, 2, ',', '.') }}€
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;color:#94a3b8;font-size:0.85rem;">
                            {{ $order->payment_type ?? '—' }}
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;font-size:0.85rem;">
                            @if ($order->status === 'pending')
                                <span style="background:rgba(234,179,8,.1);color:#fbbf24;border:1px solid rgba(234,179,8,.2);border-radius:20px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Pendente</span>
                            @elseif ($order->status === 'closed')
                                <span style="background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.2);border-radius:20px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Fechada</span>
                            @elseif ($order->status === 'canceled')
                                <span style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2);border-radius:20px;padding:0.2rem 0.65rem;font-size:0.75rem;font-weight:600;">Cancelada</span>
                            @else
                                <span style="color:#94a3b8;">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;border-bottom:1px solid #1e1e30;font-size:0.85rem;">
                            <div style="display:flex;gap:0.4rem;flex-wrap:wrap;align-items:center;">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   style="background:#1a1a2e;color:#a78bfa;border:1px solid #7c3aed;border-radius:6px;padding:0.3rem 0.65rem;font-size:0.78rem;font-weight:600;text-decoration:none;">
                                    Ver
                                </a>
                                @if ($order->isPending())
                                    <button
                                        wire:click="closeOrder({{ $order->id }})"
                                        wire:confirm="Fechar a encomenda #{{ $order->id }}?"
                                        style="background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.25);border-radius:6px;padding:0.3rem 0.65rem;cursor:pointer;font-size:0.78rem;font-weight:600;"
                                    >Fechar</button>
                                    <button
                                        wire:click="openCancelModal({{ $order->id }})"
                                        style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.25);border-radius:6px;padding:0.3rem 0.65rem;cursor:pointer;font-size:0.78rem;font-weight:600;"
                                    >Cancelar</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.9rem;">Nenhuma encomenda encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginator --}}
    @if ($orders->hasPages())
        <div style="margin-top:1rem;">
            {{ $orders->links() }}
        </div>
    @endif

    {{-- Cancel Modal --}}
    @if ($showCancelModal)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:2rem;width:100%;max-width:480px;">
                <h2 style="color:#e2e8f0;font-size:1.1rem;font-weight:700;margin:0 0 0.5rem;">Cancelar encomenda #{{ $cancelOrderId }}</h2>
                <p style="color:#94a3b8;font-size:0.85rem;margin:0 0 1.25rem;">Indique o motivo do cancelamento.</p>

                <textarea
                    wire:model="cancelReason"
                    placeholder="Motivo do cancelamento..."
                    rows="4"
                    style="width:100%;box-sizing:border-box;background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.65rem 0.75rem;font-size:0.9rem;outline:none;resize:vertical;"
                ></textarea>
                @error('cancelReason')
                    <p style="color:#f87171;font-size:0.8rem;margin:0.4rem 0 0;">{{ $message }}</p>
                @enderror

                <div style="display:flex;justify-content:flex-end;gap:0.75rem;margin-top:1.25rem;">
                    <button
                        wire:click="$set('showCancelModal', false)"
                        style="background:#1a1a2e;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.5rem 1.25rem;cursor:pointer;font-size:0.875rem;font-weight:600;"
                    >Cancelar</button>
                    <button
                        wire:click="cancelOrder"
                        style="background:#dc2626;color:white;border:none;border-radius:6px;padding:0.5rem 1.25rem;cursor:pointer;font-size:0.875rem;font-weight:600;"
                    >Confirmar cancelamento</button>
                </div>
            </div>
        </div>
    @endif

</div>
