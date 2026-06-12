@extends('layouts.app', ['title' => 'Encomenda #' . $order->id])
@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('orders.index') }}"
                class="text-gray-500 hover:text-indigo-600 transition-colors text-sm font-medium no-underline">
                &larr; As minhas encomendas
            </a>
            <span class="text-gray-300">|</span>
            <h1 class="text-2xl font-bold text-gray-900 m-0">Encomenda #{{ $order->id }}</h1>
            <x-status-badge :status="$order->status" />
        </div>

        {{-- Layout Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Coluna Principal: Items --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-gray-800 text-lg font-semibold m-0">Artigos encomendados</h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <div class="flex items-center gap-4 px-6 py-4">
                                <x-tshirt-preview :colorCode="$item->color_code" :imageUrl="$item->tshirtImage?->image_url" size="64px"
                                    class="rounded-md border border-gray-100" />

                                <div class="flex-1">
                                    <p class="text-gray-900 font-medium mb-1">
                                        {{ $item->tshirtImage?->name ?? 'Design removido' }}</p>
                                    <p class="text-gray-500 text-sm m-0">{{ $item->color?->name ?? $item->color_code }}
                                        &middot; {{ $item->size }} &middot; {{ $item->qty }}x</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-indigo-900 font-semibold text-lg m-0">
                                        &euro;{{ number_format($item->sub_total, 2) }}</p>
                                    <p class="text-gray-400 text-xs mt-1 m-0">
                                        &euro;{{ number_format($item->unit_price, 2) }}/un</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end items-center gap-3">
                        <span class="text-gray-500 font-medium">Total:</span>
                        <span
                            class="text-indigo-900 font-bold text-2xl">&euro;{{ number_format($order->total_price, 2) }}</span>
                    </div>
                </div>

                @if ($order->reason_for_cancellation)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                        <p class="text-red-600 font-semibold text-sm mb-1">Motivo de anulação:</p>
                        <p class="text-red-800 text-sm m-0">{{ $order->reason_for_cancellation }}</p>
                    </div>
                @endif
            </div>

            {{-- Coluna Lateral: Detalhes --}}
            <div class="space-y-6">

                {{-- Detalhes Card --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h4 class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-4 m-0">Detalhes</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Data</span>
                            <span class="text-gray-900 text-sm font-medium">{{ $order->date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">NIF</span>
                            <span class="text-gray-900 text-sm font-medium">{{ $order->nif ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">Pagamento</span>
                            <span class="text-gray-900 text-sm font-medium">{{ $order->payment_type }}</span>
                        </div>
                    </div>
                </div>

                {{-- Morada Card --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h4 class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-3 m-0">Morada de entrega</h4>
                    <p class="text-gray-700 text-sm leading-relaxed m-0">{{ $order->address }}</p>
                </div>

                {{-- Notas Card --}}
                @if ($order->notes)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                        <h4 class="text-gray-400 text-xs uppercase tracking-wider font-bold mb-3 m-0">Notas da Encomenda
                        </h4>
                        <p class="text-gray-700 text-sm m-0">{{ $order->notes }}</p>
                    </div>
                @endif

                {{-- Botão Recibo PDF --}}
                @if ($order->isClosed() && $order->receipt_url)
                    <a href="{{ route('orders.receipt', $order->id) }}" target="_blank"
                        class="block w-full text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 transition-colors rounded-xl py-3 text-sm font-semibold shadow-sm">
                        📄 Descarregar recibo PDF
                    </a>
                @endif
            </div>

        </div>
    </div>
@endsection
