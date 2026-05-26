<div>
    <div style="margin-bottom:1.5rem;">
        <h1 style="color:#e2e8f0;font-size:1.4rem;font-weight:700;margin:0 0 0.25rem;">Encomendas Pendentes</h1>
        <p style="color:#64748b;font-size:0.85rem;margin:0;">Processa e fecha as encomendas após estampagem e envio.</p>
    </div>

    @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,3000)"
             style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#4ade80;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:0.85rem;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div style="text-align:center;padding:4rem 2rem;background:#111120;border:1px solid #1e1e30;border-radius:12px;">
            <p style="color:#94a3b8;margin:0;">Não há encomendas pendentes de momento. 🎉</p>
        </div>
    @else
        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#0d0d1a;border-bottom:1px solid #1e1e30;">
                        <th style="color:#64748b;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em;padding:0.75rem 1rem;text-align:left;">#</th>
                        <th style="color:#64748b;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em;padding:0.75rem 1rem;text-align:left;">Cliente</th>
                        <th style="color:#64748b;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em;padding:0.75rem 1rem;text-align:left;">Data</th>
                        <th style="color:#64748b;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em;padding:0.75rem 1rem;text-align:left;">Artigos</th>
                        <th style="color:#64748b;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em;padding:0.75rem 1rem;text-align:right;">Total</th>
                        <th style="color:#64748b;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em;padding:0.75rem 1rem;text-align:right;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr style="border-bottom:1px solid #0d0d1a;">
                            <td style="padding:0.85rem 1rem;color:#94a3b8;font-size:0.85rem;">#{{ $order->id }}</td>
                            <td style="padding:0.85rem 1rem;">
                                <p style="color:#e2e8f0;font-size:0.85rem;margin:0;">{{ $order->customer->user->name }}</p>
                                <p style="color:#64748b;font-size:0.75rem;margin:0.1rem 0 0;">{{ $order->customer->user->email }}</p>
                            </td>
                            <td style="padding:0.85rem 1rem;color:#94a3b8;font-size:0.85rem;">{{ $order->date->format('d/m/Y') }}</td>
                            <td style="padding:0.85rem 1rem;color:#94a3b8;font-size:0.85rem;">{{ $order->items->count() }}</td>
                            <td style="padding:0.85rem 1rem;color:#a78bfa;font-weight:600;font-size:0.9rem;text-align:right;">€{{ number_format($order->total_price, 2) }}</td>
                            <td style="padding:0.85rem 1rem;text-align:right;">
                                <button wire:click="closeOrder({{ $order->id }})"
                                        wire:confirm="Marcar encomenda #{{ $order->id }} como fechada?"
                                        style="background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.4rem 0.9rem;font-size:0.8rem;font-weight:600;cursor:pointer;">
                                    ✓ Fechar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">{{ $orders->links() }}</div>
    @endif
</div>
