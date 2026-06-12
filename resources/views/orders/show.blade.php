@extends('layouts.app', ['title' => 'Encomenda #' . $order->id])
@section('content')

{{-- Header --}}
<div class="flex items-center gap-4 mb-8">
    <a href="{{ route('orders.index') }}"
       class="text-fs-muted no-underline text-[0.85rem] transition-colors duration-150 hover:text-fs-purple">
        ← As minhas encomendas
    </a>
    <span class="text-fs-border">|</span>
    <h1 class="text-fs-dark text-[1.3rem] font-bold m-0">Encomenda #{{ $order->id }}</h1>
    <x-status-badge :status="$order->status" />
</div>

<div class="grid gap-6" style="grid-template-columns:2fr 1fr;">

    {{-- Left: Items --}}
    <div>
        <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
            <div class="px-6 py-4 border-b border-fs-border">
                <h3 class="text-fs-dark text-[0.95rem] font-semibold m-0">Artigos encomendados</h3>
            </div>

            @foreach($order->items as $item)
                <div class="flex items-center gap-4 px-6 py-4 border-b border-[#0d0d1a]">
                    <x-tshirt-preview
                        :colorCode="$item->color_code"
                        :imageUrl="$item->tshirtImage?->image_url"
                        size="64px" />
                    <div class="flex-1">
                        <p class="text-fs-dark text-[0.9rem] font-medium m-0 mb-1">
                            {{ $item->tshirtImage?->name ?? 'Design removido' }}
                        </p>
                        <p class="text-fs-muted text-[0.8rem] m-0">
                            {{ $item->color?->name ?? $item->color_code }}
                            · {{ $item->size }}
                            · {{ $item->qty }}x
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-fs-purple font-semibold text-[0.9rem] m-0">
                            €{{ number_format($item->sub_total, 2) }}
                        </p>
                        <p class="text-fs-muted text-[0.75rem] mt-0.5 mb-0">
                            €{{ number_format($item->unit_price, 2) }}/un
                        </p>
                    </div>
                </div>
            @endforeach

            <div class="px-6 py-4 flex justify-end items-center gap-2">
                <span class="text-fs-gray text-[0.9rem]">Total:</span>
                <span class="text-fs-purple font-bold text-[1.15rem]">
                    €{{ number_format($order->total_price, 2) }}
                </span>
            </div>
        </div>

        {{-- Cancellation reason --}}
        @if($order->reason_for_cancellation)
            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg px-5 py-4">
                <p class="text-red-400 text-[0.82rem] font-medium m-0 mb-1">Motivo de anulação:</p>
                <p class="text-fs-gray text-[0.85rem] m-0">{{ $order->reason_for_cancellation }}</p>
            </div>
        @endif
    </div>

    {{-- Right: Details --}}
    <div class="flex flex-col gap-4">

        {{-- Order details card --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-5">
            <h4 class="text-fs-gray text-[0.75rem] uppercase tracking-[0.06em] font-semibold m-0 mb-3">
                Detalhes
            </h4>
            <div class="flex flex-col gap-2">
                <div class="flex justify-between">
                    <span class="text-fs-muted text-[0.82rem]">Data</span>
                    <span class="text-fs-gray text-[0.82rem]">{{ $order->date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-fs-muted text-[0.82rem]">NIF</span>
                    <span class="text-fs-gray text-[0.82rem]">{{ $order->nif ?: '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-fs-muted text-[0.82rem]">Pagamento</span>
                    <span class="text-fs-gray text-[0.82rem]">{{ $order->payment_type }}</span>
                </div>
            </div>
        </div>

        {{-- Address card --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-5">
            <h4 class="text-fs-gray text-[0.75rem] uppercase tracking-[0.06em] font-semibold m-0 mb-2">
                Morada de entrega
            </h4>
            <p class="text-fs-gray text-[0.85rem] m-0 leading-relaxed">{{ $order->address }}</p>
        </div>

        {{-- Notes card --}}
        @if($order->notes)
            <div class="bg-white border border-fs-border rounded-[2px] p-5">
                <h4 class="text-fs-gray text-[0.75rem] uppercase tracking-[0.06em] font-semibold m-0 mb-2">
                    Notas
                </h4>
                <p class="text-fs-gray text-[0.85rem] m-0">{{ $order->notes }}</p>
            </div>
        @endif

        {{-- Receipt download --}}
        @if($order->isClosed() && $order->receipt_url)
            <a href="{{ route('orders.receipt', $order->id) }}"
               target="_blank"
               class="block text-center bg-fs-purple/20 text-fs-purple no-underline border border-fs-purple/20 rounded-lg py-3 text-[0.875rem] font-semibold">
                📄 Descarregar recibo PDF
            </a>
        @endif

    </div>
</div>

@endsection
