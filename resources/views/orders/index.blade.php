@extends('layouts.app', ['title' => 'As minhas encomendas'])
@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-fs-dark text-[1.6rem] font-bold m-0 mb-1">As minhas encomendas</h1>
        <p class="text-fs-muted text-[0.9rem] m-0">Histórico de compras e estado das encomendas</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('orders.index') }}"
           class="no-underline border rounded-[2px] px-3.5 py-1.5 text-[0.8rem] cursor-pointer transition-all duration-150
                  {{ $statusFilter === '' || $statusFilter === null
                      ? 'bg-fs-purple/20 text-fs-purple border-fs-purple'
                      : 'bg-white text-fs-muted border-fs-border' }}">
            Todas
        </a>
        <a href="{{ route('orders.index', ['status' => 'pending']) }}"
           class="no-underline border rounded-[2px] px-3.5 py-1.5 text-[0.8rem] cursor-pointer transition-all duration-150
                  {{ $statusFilter === 'pending'
                      ? 'bg-fs-purple/20 text-fs-purple border-fs-purple'
                      : 'bg-white text-fs-muted border-fs-border' }}">
            Pendentes
        </a>
        <a href="{{ route('orders.index', ['status' => 'closed']) }}"
           class="no-underline border rounded-[2px] px-3.5 py-1.5 text-[0.8rem] cursor-pointer transition-all duration-150
                  {{ $statusFilter === 'closed'
                      ? 'bg-fs-purple/20 text-fs-purple border-fs-purple'
                      : 'bg-white text-fs-muted border-fs-border' }}">
            Fechadas
        </a>
        <a href="{{ route('orders.index', ['status' => 'canceled']) }}"
           class="no-underline border rounded-[2px] px-3.5 py-1.5 text-[0.8rem] cursor-pointer transition-all duration-150
                  {{ $statusFilter === 'canceled'
                      ? 'bg-fs-purple/20 text-fs-purple border-fs-purple'
                      : 'bg-white text-fs-muted border-fs-border' }}">
            Anuladas
        </a>
    </div>
</div>

@if($orders->isEmpty())
    <div class="text-center py-16 px-8 bg-white border border-fs-border rounded-[2px]">
        <p class="text-fs-gray text-[1rem] m-0 mb-4">Ainda não tens encomendas.</p>
        <a href="{{ route('catalog') }}"
           class="bg-fs-purple text-white no-underline rounded-lg px-6 py-2.5 text-[0.9rem] font-semibold">
            Ver catálogo →
        </a>
    </div>
@else
    <div class="flex flex-col gap-3">
        @foreach($orders as $order)
            <a href="{{ route('orders.show', $order->id) }}"
               class="block bg-white border border-fs-border rounded-[2px] px-6 py-5 no-underline transition-colors duration-200 hover:border-fs-purple">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-1.5">
                            <span class="text-fs-dark font-semibold text-[0.95rem]">#{{ $order->id }}</span>
                            <x-status-badge :status="$order->status" />
                        </div>
                        <p class="text-fs-muted text-[0.82rem] m-0">
                            {{ $order->date->format('d/m/Y') }}
                            · {{ $order->items->count() }} artigo{{ $order->items->count() !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-fs-purple font-bold text-[1.05rem]">
                            €{{ number_format($order->total_price, 2) }}
                        </span>
                        @if($order->isClosed() && $order->receipt_url)
                            <a href="{{ route('orders.receipt', $order->id) }}"
                               target="_blank"
                               onclick="event.stopPropagation()"
                               class="bg-fs-purple/20 text-fs-purple no-underline border border-fs-purple/20 rounded-[6px] px-3 py-1 text-[0.78rem] font-medium">
                                📄 Recibo
                            </a>
                        @endif
                        <svg class="w-4 h-4 text-fs-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endif

@endsection
