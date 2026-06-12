@extends('layouts.app', ['title' => 'As minhas encomendas'])
@section('content')

    <div class="max-w-4xl mx-auto py-8">

        {{-- Cabeçalho e Filtros --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <p class="text-[0.72rem] font-semibold uppercase tracking-[0.1em] text-fs-muted m-0 mb-1">Conta</p>
                <h1 class="text-[1.6rem] font-bold text-fs-dark m-0">As minhas encomendas</h1>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach (['' => 'Todas', 'pending' => 'Pendentes', 'closed' => 'Fechadas', 'canceled' => 'Anuladas'] as $val => $label)
                    <a href="{{ route('orders.index', $val ? ['status' => $val] : []) }}"
                        class="px-4 py-[0.45rem] rounded-[1px] text-[0.8rem] font-semibold transition-colors border no-underline
                        {{ $statusFilter === $val
                            ? 'bg-fs-purple/10 text-fs-purple border-fs-purple/30'
                            : 'bg-white text-fs-gray border-fs-border hover:border-fs-dark hover:text-fs-dark' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Lista vazia --}}
        @if ($orders->isEmpty())
            <div class="text-center py-16 px-6 bg-white border border-fs-border rounded-[2px]">
                <p class="text-fs-gray text-[0.95rem] mb-6 m-0">Ainda não tens encomendas.</p>
                <a href="{{ route('catalog') }}"
                    class="inline-block bg-fs-dark hover:opacity-80 text-fs-light font-semibold py-2.5 px-6 rounded-[1px] transition-opacity no-underline text-[0.9rem]">
                    Ver catálogo &rarr;
                </a>
            </div>
        @else
            {{-- Lista de Encomendas --}}
            <div class="flex flex-col gap-3">
                @foreach ($orders as $order)
                    <div class="relative bg-white border border-fs-border rounded-[2px] p-5 hover:border-fs-dark transition-colors group flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                        <a href="{{ route('orders.show', $order->id) }}" class="absolute inset-0 z-0 rounded-[2px]"></a>

                        {{-- Lado esquerdo --}}
                        <div class="relative z-10 pointer-events-none">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-fs-dark font-bold text-[1rem]">#{{ $order->id }}</span>
                                <x-status-badge :status="$order->status" />
                            </div>
                            <p class="text-fs-gray text-[0.82rem] m-0">
                                {{ $order->date->format('d/m/Y') }} &middot; {{ $order->items->count() }} artigo{{ $order->items->count() !== 1 ? 's' : '' }}
                            </p>
                        </div>

                        {{-- Lado direito --}}
                        <div class="relative z-10 flex items-center gap-4 border-t sm:border-0 border-fs-border pt-3 sm:pt-0">
                            <span class="text-fs-dark font-bold text-[1rem] pointer-events-none">
                                &euro;{{ number_format($order->total_price, 2) }}
                            </span>

                            @if ($order->isClosed() && $order->receipt_url)
                                <a href="{{ route('orders.receipt', $order->id) }}" target="_blank"
                                    class="relative z-20 px-3 py-1.5 bg-fs-purple/10 hover:bg-fs-purple/20 text-fs-purple border border-fs-purple/20 rounded-[1px] text-[0.78rem] font-semibold transition-colors no-underline">
                                    Recibo PDF
                                </a>
                            @endif

                            <svg class="w-4 h-4 text-fs-muted group-hover:text-fs-dark transition-colors pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginação --}}
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection
