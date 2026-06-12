@extends('layouts.app', ['title' => 'Encomenda #' . $order->id])
@section('content')
    <div class="max-w-5xl mx-auto py-8">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('orders.index') }}"
                class="text-fs-muted hover:text-fs-dark transition-colors text-[0.8rem] font-medium no-underline">
                &larr; As minhas encomendas
            </a>
            <span class="text-fs-border">|</span>
            <h1 class="text-[1.5rem] font-bold text-fs-dark m-0">Encomenda #{{ $order->id }}</h1>
            <x-status-badge :status="$order->status" />
        </div>

        {{-- Layout Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Coluna Principal: Items --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-fs-border bg-fs-bg">
                        <p class="text-[0.72rem] font-semibold uppercase tracking-[0.1em] text-fs-muted m-0">Artigos encomendados</p>
                    </div>

                    <div class="divide-y divide-fs-border">
                        @foreach ($order->items as $item)
                            <div class="flex items-center gap-4 px-5 py-4">
                                <x-tshirt-preview :colorCode="$item->color_code" :imageUrl="$item->tshirtImage?->image_url" size="64px"
                                    class="rounded-[1px] border border-fs-border" />

                                <div class="flex-1">
                                    <p class="text-fs-dark font-semibold text-[0.9rem] mb-0.5 m-0">
                                        {{ $item->tshirtImage?->name ?? 'Design removido' }}</p>
                                    <p class="text-fs-gray text-[0.82rem] m-0">{{ $item->color?->name ?? $item->color_code }}
                                        &middot; {{ $item->size }} &middot; {{ $item->qty }}x</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-fs-dark font-bold text-[1rem] m-0">
                                        &euro;{{ number_format($item->sub_total, 2) }}</p>
                                    <p class="text-fs-muted text-[0.75rem] mt-0.5 m-0">
                                        &euro;{{ number_format($item->unit_price, 2) }}/un</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-5 py-4 bg-fs-bg border-t border-fs-border flex justify-end items-center gap-3">
                        <span class="text-fs-gray text-[0.85rem] font-medium">Total:</span>
                        <span class="text-fs-dark font-bold text-[1.4rem]">&euro;{{ number_format($order->total_price, 2) }}</span>
                    </div>
                </div>

                @if ($order->reason_for_cancellation)
                    <div class="bg-fs-red/5 border border-fs-red/20 rounded-[2px] p-4">
                        <p class="text-fs-red text-[0.72rem] font-semibold uppercase tracking-[0.08em] mb-1 m-0">Motivo de anulação</p>
                        <p class="text-fs-dark text-[0.88rem] m-0">{{ $order->reason_for_cancellation }}</p>
                    </div>
                @endif
            </div>

            {{-- Coluna Lateral --}}
            <div class="space-y-4">

                {{-- Detalhes --}}
                <div class="bg-white border border-fs-border rounded-[2px] p-5">
                    <p class="text-[0.68rem] font-semibold uppercase tracking-[0.12em] text-fs-muted mb-4 m-0">Detalhes</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-fs-gray text-[0.85rem]">Data</span>
                            <span class="text-fs-dark text-[0.85rem] font-medium">{{ $order->date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-fs-gray text-[0.85rem]">NIF</span>
                            <span class="text-fs-dark text-[0.85rem] font-medium">{{ $order->nif ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-fs-gray text-[0.85rem]">Pagamento</span>
                            <span class="text-fs-dark text-[0.85rem] font-medium">{{ $order->payment_type }}</span>
                        </div>
                    </div>
                </div>

                {{-- Morada --}}
                <div class="bg-white border border-fs-border rounded-[2px] p-5">
                    <p class="text-[0.68rem] font-semibold uppercase tracking-[0.12em] text-fs-muted mb-3 m-0">Morada de entrega</p>
                    <p class="text-fs-dark text-[0.88rem] leading-relaxed m-0">{{ $order->address }}</p>
                </div>

                {{-- Notas --}}
                @if ($order->notes)
                    <div class="bg-white border border-fs-border rounded-[2px] p-5">
                        <p class="text-[0.68rem] font-semibold uppercase tracking-[0.12em] text-fs-muted mb-3 m-0">Notas da encomenda</p>
                        <p class="text-fs-dark text-[0.88rem] m-0">{{ $order->notes }}</p>
                    </div>
                @endif

                {{-- Recibo PDF --}}
                @if ($order->isClosed() && $order->receipt_url)
                    <a href="{{ route('orders.receipt', $order->id) }}" target="_blank"
                        class="block w-full text-center bg-fs-purple/10 hover:bg-fs-purple/20 text-fs-purple border border-fs-purple/20 transition-colors rounded-[1px] py-2.5 text-[0.8rem] font-semibold uppercase tracking-[0.05em] no-underline">
                        Descarregar recibo PDF
                    </a>
                @endif
            </div>

        </div>
    </div>
@endsection
