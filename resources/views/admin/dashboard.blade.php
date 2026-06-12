@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-7">
        <div>
            <div class="text-[0.58rem] font-bold tracking-[0.18em] uppercase text-fs-mid mb-1">Visão geral</div>
            <p class="text-fs-gray text-[0.82rem] m-0">{{ now()->locale('pt')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
        </div>
        <a href="{{ route('admin.orders') }}"
            class="inline-flex items-center gap-[0.45rem] bg-fs-dark text-fs-light no-underline text-[0.68rem] font-bold tracking-[0.1em] uppercase px-4 py-[0.48rem] rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
            Ver encomendas
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    {{-- KPI row --}}
    <div class="grid grid-cols-4 gap-px bg-fs-border border border-fs-border rounded-[2px] overflow-hidden mb-px">
        @foreach ($kpis as $k)
            <div class="bg-white px-5 py-[1.35rem]">
                <div class="flex items-center justify-between mb-4">
                    <span
                        class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted">{{ $k['label'] }}</span>
                    <svg width="14" height="14" fill="none" stroke="{{ $k['accent'] }}" viewBox="0 0 24 24"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="opacity-70">{!! $k['icon'] !!}</svg>
                </div>
                <div class="text-[2.2rem] font-light tracking-[-0.04em] text-fs-dark leading-none mb-[0.4rem]">
                    {{ $k['value'] }}</div>
                <div class="text-[0.72rem] text-[#aaa]">{{ $k['sub'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Secondary stats --}}
    <div class="grid grid-cols-3 gap-px bg-fs-border border border-fs-border rounded-[2px] overflow-hidden mb-6 mt-px">
        @foreach ($secondaryStats as $s)
            <div class="bg-fs-bg px-5 py-[1.1rem] flex items-center gap-[0.9rem]">
                <svg width="16" height="16" fill="none" stroke="#b8b4ae" viewBox="0 0 24 24" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">{!! $s['icon'] !!}</svg>
                <div>
                    <div class="text-[1.55rem] font-light tracking-[-0.03em] text-fs-dark leading-none mb-[0.2rem]">
                        {{ $s['value'] }}</div>
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
                    @foreach (['#', 'Cliente', 'Data', 'Total', 'Estado', ''] as $th)
                        <th
                            class="px-[1.1rem] py-[0.6rem] text-left text-[#c8c4be] text-[0.58rem] font-bold uppercase tracking-[0.12em] border-b border-fs-border">
                            {{ $th }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($recentOrders as $order)
                    <tr class="transition-colors duration-100 hover:bg-[#f9f8f6]">
                        <td
                            class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8] text-[#c8c4be] text-[0.75rem] font-mono">
                            #{{ $order->id }}</td>
                        <td
                            class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8] text-fs-dark text-[0.82rem] font-medium">
                            {{ $order->customer?->user?->name ?? '—' }}</td>
                        <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8] text-[#aaa] text-[0.78rem]">
                            {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}</td>
                        <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8] text-fs-dark text-[0.82rem] font-bold">
                            {{ number_format($order->total_price, 2, ',', '.') }} €</td>
                        <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8]">
                            <span
                                class="rounded-[1px] px-[0.6rem] py-[0.18rem] text-[0.65rem] font-bold tracking-[0.06em] uppercase whitespace-nowrap border"
                                style="background:{{ $order->format['bg'] }}; color:{{ $order->format['color'] }}; border-color:{{ $order->format['border'] }};">
                                {{ $order->format['label'] }}
                            </span>
                        </td>
                        <td class="px-[1.1rem] py-[0.8rem] border-b border-[#f0ede8]">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                class="text-fs-purple no-underline text-[0.75rem] font-semibold inline-flex items-center gap-[0.3rem] transition-colors duration-150 hover:text-fs-dark">
                                Detalhe
                                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-[#c8c4be] text-[0.82rem]">Nenhuma encomenda
                            registada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
