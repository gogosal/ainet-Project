<div>

    {{-- Page header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;">
        <div>
            <h1 style="color:#f1f5f9;font-size:1.35rem;font-weight:700;margin:0;letter-spacing:-.02em;">Visão Geral</h1>
            <p style="color:#374151;font-size:0.82rem;margin:0.2rem 0 0;">{{ now()->locale('pt')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        </div>
        <a href="{{ route('admin.orders') }}"
           style="display:inline-flex;align-items:center;gap:0.45rem;background:#7c3aed;color:white;text-decoration:none;font-size:0.82rem;font-weight:600;padding:0.48rem 1rem;border-radius:8px;transition:background .15s;"
           onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
            Ver encomendas
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- KPI row --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.125rem;">

        @php
            $kpis = [
                [
                    'label'    => 'Clientes',
                    'value'    => $stats['totalClients'],
                    'sub'      => 'contas registadas',
                    'accent'   => '#7c3aed',
                    'accentBg' => 'rgba(124,58,237,.12)',
                    'icon'     => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',
                ],
                [
                    'label'    => 'Pendentes',
                    'value'    => $stats['pendingOrders'],
                    'sub'      => 'a aguardar',
                    'accent'   => '#d97706',
                    'accentBg' => 'rgba(217,119,6,.10)',
                    'icon'     => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                ],
                [
                    'label'    => 'Fechadas',
                    'value'    => $stats['closedOrders'],
                    'sub'      => number_format($stats['totalRevenue'], 2, ',', '.') . ' € receita',
                    'accent'   => '#16a34a',
                    'accentBg' => 'rgba(22,163,74,.10)',
                    'icon'     => '<polyline points="20 6 9 17 4 12"/>',
                ],
                [
                    'label'    => 'Canceladas',
                    'value'    => $stats['canceledOrders'],
                    'sub'      => 'total canceladas',
                    'accent'   => '#dc2626',
                    'accentBg' => 'rgba(220,38,38,.08)',
                    'icon'     => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',
                ],
            ];
        @endphp

        @foreach($kpis as $k)
        <div style="background:#0b0b1a;border:1px solid #13132a;border-radius:14px;padding:1.35rem;position:relative;overflow:hidden;">
            {{-- Glow blob --}}
            <div style="position:absolute;top:-16px;right:-16px;width:64px;height:64px;border-radius:50%;background:{{ $k['accentBg'] }};pointer-events:none;"></div>
            {{-- Icon --}}
            <div style="width:34px;height:34px;border-radius:9px;background:{{ $k['accentBg'] }};border:1px solid {{ $k['accent'] }}33;display:flex;align-items:center;justify-content:center;margin-bottom:0.9rem;">
                <svg width="15" height="15" fill="none" stroke="{{ $k['accent'] }}" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $k['icon'] !!}</svg>
            </div>
            <p style="color:#374151;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin:0 0 0.3rem;">{{ $k['label'] }}</p>
            <p style="color:#f1f5f9;font-size:2.1rem;font-weight:700;margin:0;line-height:1;letter-spacing:-.03em;">{{ $k['value'] }}</p>
            <p style="color:#374151;font-size:0.72rem;margin:0.45rem 0 0;">{{ $k['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Secondary stats --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
        @php
            $secondary = [
                ['label' => 'Colaboradores', 'value' => $stats['totalStaff'], 'sub' => 'Funcionários & Admins',
                 'icon' => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>'],
                ['label' => 'Imagens Catálogo', 'value' => $stats['catalogImages'], 'sub' => 'designs públicos',
                 'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" stroke="none"/><path d="M21 15l-5-5L5 21"/>'],
                ['label' => 'Imgs Personalizadas', 'value' => $stats['customImages'], 'sub' => 'enviadas por clientes',
                 'icon' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>'],
            ];
        @endphp
        @foreach($secondary as $s)
        <div style="background:#0b0b1a;border:1px solid #13132a;border-radius:14px;padding:1.1rem 1.25rem;display:flex;align-items:center;gap:1rem;">
            <div style="width:40px;height:40px;border-radius:10px;background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.18);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="#a78bfa" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $s['icon'] !!}</svg>
            </div>
            <div>
                <p style="color:#f1f5f9;font-size:1.55rem;font-weight:700;margin:0;line-height:1;letter-spacing:-.025em;">{{ $s['value'] }}</p>
                <p style="color:#374151;font-size:0.72rem;margin:0.2rem 0 0;">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Recent orders table --}}
    <div style="background:#0b0b1a;border:1px solid #13132a;border-radius:14px;overflow:hidden;">
        <div style="padding:1.1rem 1.4rem;border-bottom:1px solid #13132a;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:0.6rem;">
                <div style="width:6px;height:6px;border-radius:50%;background:#7c3aed;"></div>
                <h2 style="color:#e2e8f0;font-size:0.9rem;font-weight:600;margin:0;">Encomendas Recentes</h2>
            </div>
            <span style="background:#12122a;color:#475569;font-size:0.70rem;font-weight:500;padding:0.2rem 0.65rem;border-radius:20px;border:1px solid #1e1e38;">Últimas 5</span>
        </div>
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#080814;">
                    @foreach(['#', 'Cliente', 'Data', 'Total', 'Estado', ''] as $th)
                    <th style="padding:0.7rem 1.25rem;text-align:left;color:#2d2d4e;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;border-bottom:1px solid #13132a;">{{ $th }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($recentOrders as $order)
                    @php
                        $sm = [
                            'pending'  => ['Pendente',  '#f59e0b', 'rgba(245,158,11,.1)',  'rgba(245,158,11,.2)'],
                            'closed'   => ['Fechada',   '#22c55e', 'rgba(34,197,94,.1)',   'rgba(34,197,94,.2)'],
                            'canceled' => ['Cancelada', '#f87171', 'rgba(248,113,113,.1)', 'rgba(248,113,113,.2)'],
                        ];
                        [$slabel, $sc, $sbg, $sbdr] = $sm[$order->status] ?? [$order->status, '#94a3b8', 'rgba(148,163,184,.08)', 'rgba(148,163,184,.15)'];
                    @endphp
                    <tr onmouseover="this.style.background='rgba(124,58,237,.04)'" onmouseout="this.style.background='transparent'" style="transition:background .1s;">
                        <td style="padding:0.85rem 1.25rem;border-bottom:1px solid #0e0e22;color:#374151;font-size:0.82rem;font-family:monospace;">#{{ $order->id }}</td>
                        <td style="padding:0.85rem 1.25rem;border-bottom:1px solid #0e0e22;color:#e2e8f0;font-size:0.85rem;font-weight:500;">{{ $order->customer?->user?->name ?? '—' }}</td>
                        <td style="padding:0.85rem 1.25rem;border-bottom:1px solid #0e0e22;color:#374151;font-size:0.82rem;">{{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}</td>
                        <td style="padding:0.85rem 1.25rem;border-bottom:1px solid #0e0e22;color:#c4b5fd;font-size:0.85rem;font-weight:700;">{{ number_format($order->total_price, 2, ',', '.') }} €</td>
                        <td style="padding:0.85rem 1.25rem;border-bottom:1px solid #0e0e22;">
                            <span style="background:{{ $sbg }};color:{{ $sc }};border:1px solid {{ $sbdr }};border-radius:20px;padding:0.22rem 0.7rem;font-size:0.72rem;font-weight:600;white-space:nowrap;">{{ $slabel }}</span>
                        </td>
                        <td style="padding:0.85rem 1.25rem;border-bottom:1px solid #0e0e22;">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               style="color:#7c3aed;text-decoration:none;font-size:0.78rem;font-weight:600;display:inline-flex;align-items:center;gap:0.3rem;transition:color .15s;"
                               onmouseover="this.style.color='#a78bfa'" onmouseout="this.style.color='#7c3aed'">
                                Detalhe
                                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:3rem;text-align:center;color:#2d2d4e;font-size:0.85rem;">Nenhuma encomenda registada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
