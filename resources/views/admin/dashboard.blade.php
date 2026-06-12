@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-7">
    <div>
        <div class="text-[0.58rem] font-bold tracking-[0.18em] uppercase text-fs-mid mb-1">Visão geral</div>
        <p class="text-fs-gray text-[0.82rem] m-0">{{ now()->locale('pt')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
    </div>
    <a href="{{ route('admin.orders') }}"
       class="inline-flex items-center gap-[0.45rem] bg-fs-dark text-fs-light no-underline text-[0.68rem] font-bold tracking-[0.1em] uppercase px-4 py-[0.48rem] rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
        Ver encomendas
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
</div>

{{-- KPI row --}}
@php
    $kpis = [
        ['label' => 'Clientes',   'value' => $stats['totalClients'],   'sub' => 'contas registadas',
         'accent' => '#7c6fa0', 'icon' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>'],
        ['label' => 'Pendentes',  'value' => $stats['pendingOrders'],  'sub' => 'a aguardar',
         'accent' => '#d97706', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
        ['label' => 'Fechadas',   'value' => $stats['closedOrders'],   'sub' => number_format($stats['totalRevenue'], 2, ',', '.') . ' € receita',
         'accent' => '#16a34a', 'icon' => '<polyline points="20 6 9 17 4 12"/>'],
        ['label' => 'Canceladas', 'value' => $stats['canceledOrders'], 'sub' => 'total canceladas',
         'accent' => '#dc2626', 'icon' => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'],
    ];
@endphp

<div class="grid grid-cols-4 gap-px bg-fs-border border border-fs-border rounded-[2px] overflow-hidden mb-px">
    @foreach($kpis as $k)
    <div class="bg-white px-5 py-[1.35rem]">
        <div class="flex items-center justify-between mb-4">
            <span class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted">{{ $k['label'] }}</span>
            <svg width="14" height="14" fill="none" stroke="{{ $k['accent'] }}" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-70">{!! $k['icon'] !!}</svg>
        </div>
        <div class="text-[2.2rem] font-light tracking-[-0.04em] text-fs-dark leading-none mb-[0.4rem]">{{ $k['value'] }}</div>
        <div class="text-[0.72rem] text-[#aaa]">{{ $k['sub'] }}</div>
    </div>
    @endforeach
</div>

{{-- Secondary stats --}}
@php
    $secondary = [
        ['label' => 'Colaboradores',      'value' => $stats['totalStaff'],    'sub' => 'Funcionários & Admins',
         'icon' => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>'],
        ['label' => 'Imagens Catálogo',   'value' => $stats['catalogImages'], 'sub' => 'designs públicos',
         'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" stroke="none"/><path d="M21 15l-5-5L5 21"/>'],
        ['label' => 'Imgs Personalizadas','value' => $stats['customImages'],  'sub' => 'enviadas por clientes',
         'icon' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>'],
    ];
@endphp

<div class="grid grid-cols-3 gap-px bg-fs-border border border-fs-border rounded-[2px] overflow-hidden mb-6 mt-px">
    @foreach($secondary as $s)
    <div class="bg-fs-bg px-5 py-[1.1rem] flex items-center gap-[0.9rem]">
        <svg width="16" height="16" fill="none" stroke="#b8b4ae" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">{!! $s['icon'] !!}</svg>
        <div>
            <div class="text-[1.55rem] font-light tracking-[-0.03em] text-fs-dark leading-none mb-[0.2rem]">{{ $s['value'] }}</div>
            <div class="text-[0.68rem] text-[#aaa] font-medium">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Recent orders --}}
<div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
    <div class="px-5 py-[0.9rem] border-b border-fs-border flex items-center justify-between">
        <h2 class="text-fs-dark text-[0.82rem] font-semibold m-0 tracking-[-0.01em]">Encomendas Recentes</h2>
        <span class="text-fs-muted text-[0.62rem] font-bold tracking-[0.1em] uppercase">Últimas 5</span>
    </div>
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-[#f9f8f6]">
                @foreach(['#', 'Cliente', 'Data', 'Total', 'Estado', ''] as $th)
                <th class="px-[1.1rem] py-[0.6rem] text-left text-[#c8c4be] text-[0.58rem] font-bold uppercase tracking-[0.12em] border-b border-fs-border">{{ $th }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($recentOrders as $order)
                @php
                    $sm = [
                        'pending'  => ['Pendente',  '#d97706', 'rgba(217,119,6,.08)',  'rgba(217,119,6,.2)'],
                        'closed'   => ['Fechada',   '#16a34a', 'rgba(22,163,74,.08)',  'rgba(22,163,74,.2)'],
                        'canceled' => ['Cancelada', '#dc2626', 'rgba(220,38,38,.06)', 'rgba(220,38,38,.18)'],
                    ];
                    [$slabel, $sc, $sbg, $sbdr] = $sm[$order->status] ?? [$order->status, '#888', 'rgba(0,0,0,.04)', 'rgba(0,0,0,.1)'];
                @endphp
                <tr class="transition-colors duration-100 hover:bg-[#f9f8f6]">
                    <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8] text-[#c8c4be] text-[0.75rem] font-mono">#{{ $order->id }}</td>
                    <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8] text-fs-dark text-[0.82rem] font-medium">{{ $order->customer?->user?->name ?? '—' }}</td>
                    <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8] text-[#aaa] text-[0.78rem]">{{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}</td>
                    <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8] text-fs-dark text-[0.82rem] font-bold">{{ number_format($order->total_price, 2, ',', '.') }} €</td>
                    <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8]">
                        <span class="rounded-[1px] px-[0.6rem] py-[0.18rem] text-[0.65rem] font-bold tracking-[0.06em] uppercase whitespace-nowrap border"
                              style="background:{{ $sbg }};color:{{ $sc }};border-color:{{ $sbdr }};">{{ $slabel }}</span>
                    </td>
                    <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8]">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="text-fs-purple no-underline text-[0.75rem] font-semibold inline-flex items-center gap-[0.3rem] transition-colors duration-150 hover:text-fs-dark">
                            Detalhe
                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-[#c8c4be] text-[0.82rem]">Nenhuma encomenda registada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
