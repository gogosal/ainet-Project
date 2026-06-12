<div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;">
        <div>
            <div
                style="font-size:.58rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#c8c4be;margin-bottom:.3rem;">
                Visão geral</div>
            <p style="color:#888;font-size:.82rem;margin:0;">
                {{ now()->locale('pt')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        </div>
        <a href="{{ route('admin.orders') }}"
            style="display:inline-flex;align-items:center;gap:.45rem;background:#1a1a1a;color:#f5f4f1;text-decoration:none;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:.48rem 1rem;border-radius:1px;transition:background .15s;"
            onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
            Ver encomendas
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    {{-- KPI row --}}
    @php
        $kpis = [
            [
                'label' => 'Clientes',
                'value' => $stats['totalClients'],
                'sub' => 'contas registadas',
                'accent' => '#7c6fa0',
                'icon' =>
                    '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
            ],
            [
                'label' => 'Pendentes',
                'value' => $stats['pendingOrders'],
                'sub' => 'a aguardar',
                'accent' => '#d97706',
                'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
            ],
            [
                'label' => 'Fechadas',
                'value' => $stats['closedOrders'],
                'sub' => number_format($stats['totalRevenue'], 2, ',', '.') . ' € receita',
                'accent' => '#16a34a',
                'icon' => '<polyline points="20 6 9 17 4 12"/>',
            ],
            [
                'label' => 'Canceladas',
                'value' => $stats['canceledOrders'],
                'sub' => 'total canceladas',
                'accent' => '#dc2626',
                'icon' =>
                    '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',
            ],
        ];
    @endphp

    <div
        style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:#e0ddd8;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;margin-bottom:1px;">
        @foreach ($kpis as $k)
            <div style="background:#fff;padding:1.35rem 1.25rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                    <span
                        style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;">{{ $k['label'] }}</span>
                    <svg width="14" height="14" fill="none" stroke="{{ $k['accent'] }}" viewBox="0 0 24 24"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        style="opacity:.7;">{!! $k['icon'] !!}</svg>
                </div>
                <div
                    style="font-size:2.2rem;font-weight:300;letter-spacing:-.04em;color:#1a1a1a;line-height:1;margin-bottom:.4rem;">
                    {{ $k['value'] }}</div>
                <div style="font-size:.72rem;color:#aaa;">{{ $k['sub'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Secondary stats --}}
    @php
        $secondary = [
            [
                'label' => 'Colaboradores',
                'value' => $stats['totalStaff'],
                'sub' => 'Funcionários & Admins',
                'icon' =>
                    '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>',
            ],
            [
                'label' => 'Imagens Catálogo',
                'value' => $stats['catalogImages'],
                'sub' => 'designs públicos',
                'icon' =>
                    '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" stroke="none"/><path d="M21 15l-5-5L5 21"/>',
            ],
            [
                'label' => 'Imgs Personalizadas',
                'value' => $stats['customImages'],
                'sub' => 'enviadas por clientes',
                'icon' =>
                    '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
            ],
        ];
    @endphp

    <div
        style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:#e0ddd8;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;margin-bottom:1.5rem;margin-top:1px;">
        @foreach ($secondary as $s)
            <div style="background:#eeecea;padding:1.1rem 1.25rem;display:flex;align-items:center;gap:.9rem;">
                <svg width="16" height="16" fill="none" stroke="#b8b4ae" viewBox="0 0 24 24" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">{!! $s['icon'] !!}</svg>
                <div>
                    <div
                        style="font-size:1.55rem;font-weight:300;letter-spacing:-.03em;color:#1a1a1a;line-height:1;margin-bottom:.2rem;">
                        {{ $s['value'] }}</div>
                    <div style="font-size:.68rem;color:#aaa;font-weight:500;">{{ $s['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Recent orders --}}
    <div style="background:#fff;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;">
        <div
            style="padding:.9rem 1.25rem;border-bottom:1px solid #e0ddd8;display:flex;align-items:center;justify-content:space-between;">
            <h2 style="color:#1a1a1a;font-size:.82rem;font-weight:600;margin:0;letter-spacing:-.01em;">Encomendas
                Recentes</h2>
            <span
                style="color:#b8b4ae;font-size:.62rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">Últimas
                5</span>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9f8f6;">
                    @foreach (['#', 'Cliente', 'Data', 'Total', 'Estado', ''] as $th)
                        <th
                            style="padding:.6rem 1.1rem;text-align:left;color:#c8c4be;font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;border-bottom:1px solid #e0ddd8;">
                            {{ $th }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($recentOrders as $order)
                    @php
                        $sm = [
                            'pending' => ['Pendente', '#d97706', 'rgba(217,119,6,.08)', 'rgba(217,119,6,.2)'],
                            'closed' => ['Fechada', '#16a34a', 'rgba(22,163,74,.08)', 'rgba(22,163,74,.2)'],
                            'canceled' => ['Cancelada', '#dc2626', 'rgba(220,38,38,.06)', 'rgba(220,38,38,.18)'],
                        ];
                        [$slabel, $sc, $sbg, $sbdr] = $sm[$order->status] ?? [
                            $order->status,
                            '#888',
                            'rgba(0,0,0,.04)',
                            'rgba(0,0,0,.1)',
                        ];
                    @endphp
                    <tr style="transition:background .1s;" onmouseover="this.style.background='#f9f8f6'"
                        onmouseout="this.style.background='transparent'">
                        <td
                            style="padding:.8rem 1.1rem;border-bottom:1px solid #f0ede8;color:#c8c4be;font-size:.75rem;font-family:monospace;">
                            #{{ $order->id }}</td>
                        <td
                            style="padding:.8rem 1.1rem;border-bottom:1px solid #f0ede8;color:#1a1a1a;font-size:.82rem;font-weight:500;">
                            {{ $order->customer?->user?->name ?? '—' }}</td>
                        <td style="padding:.8rem 1.1rem;border-bottom:1px solid #f0ede8;color:#aaa;font-size:.78rem;">
                            {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}</td>
                        <td
                            style="padding:.8rem 1.1rem;border-bottom:1px solid #f0ede8;color:#1a1a1a;font-size:.82rem;font-weight:700;">
                            {{ number_format($order->total_price, 2, ',', '.') }} €</td>
                        <td style="padding:.8rem 1.1rem;border-bottom:1px solid #f0ede8;">
                            <span
                                style="background:{{ $sbg }};color:{{ $sc }};border:1px solid {{ $sbdr }};border-radius:1px;padding:.18rem .6rem;font-size:.65rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;white-space:nowrap;">{{ $slabel }}</span>
                        </td>
                        <td style="padding:.8rem 1.1rem;border-bottom:1px solid #f0ede8;">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                style="color:#7c6fa0;text-decoration:none;font-size:.75rem;font-weight:600;display:inline-flex;align-items:center;gap:.3rem;transition:color .15s;"
                                onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#7c6fa0'">
                                Detalhe
                                <svg width="10" height="10" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:3rem;text-align:center;color:#c8c4be;font-size:.82rem;">
                            Nenhuma encomenda registada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
