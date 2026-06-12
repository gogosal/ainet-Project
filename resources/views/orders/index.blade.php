@extends('layouts.app', ['title' => 'As minhas encomendas'])
@section('content')

    <div class="max-w-5xl mx-auto py-8">

        {{-- Cabeçalho e Filtros --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 m-0 mb-1">As minhas encomendas</h1>
                <p class="text-gray-500 text-sm m-0">Histórico de compras e estado das encomendas</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach (['' => 'Todas', 'pending' => 'Pendentes', 'closed' => 'Fechadas', 'canceled' => 'Anuladas'] as $val => $label)
                    <a href="{{ route('orders.index', $val ? ['status' => $val] : []) }}"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors border no-underline cursor-pointer
                        {{ $statusFilter === $val
                            ? 'bg-indigo-50 text-indigo-700 border-indigo-300'
                            : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Estado Vazio (Sem encomendas) --}}
        @if ($orders->isEmpty())
            <div class="text-center py-16 px-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                <p class="text-gray-500 text-lg mb-6 m-0">Ainda não tens encomendas.</p>
                <a href="{{ route('catalog') }}"
                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors no-underline">
                    Ver catálogo &rarr;
                </a>
            </div>
        @else
            {{-- Lista de Encomendas --}}
            <div class="space-y-4">
                @foreach ($orders as $order)
                    {{-- AQUI ESTÁ A CORREÇÃO: O cartão agora é uma div (relative) em vez de um link (a) --}}
                    <div
                        class="relative bg-white border border-gray-200 rounded-xl p-5 hover:border-indigo-300 hover:shadow-sm transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                        {{-- O link principal "invisível" que se estica sobre todo o cartão --}}
                        <a href="{{ route('orders.show', $order->id) }}" class="absolute inset-0 z-0 rounded-xl"></a>

                        {{-- Lado Esquerdo: Info --}}
                        <div class="relative z-10 pointer-events-none">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-gray-900 font-bold text-lg">#{{ $order->id }}</span>
                                <x-status-badge :status="$order->status" />
                            </div>
                            <p class="text-gray-500 text-sm m-0">
                                {{ $order->date->format('d/m/Y') }} &middot; {{ $order->items->count() }}
                                artigo{{ $order->items->count() !== 1 ? 's' : '' }}
                            </p>
                        </div>

                        {{-- Lado Direito: Preço & Ações --}}
                        <div
                            class="relative z-10 flex items-center gap-5 border-t sm:border-0 border-gray-100 pt-3 sm:pt-0">
                            <span class="text-indigo-900 font-bold text-lg pointer-events-none">
                                &euro;{{ number_format($order->total_price, 2) }}
                            </span>

                            @if ($order->isClosed() && $order->receipt_url)
                                {{-- Este link tem um z-20 para ficar "acima" do link invisível da encomenda inteira --}}
                                <a href="{{ route('orders.receipt', $order->id) }}" target="_blank"
                                    class="relative z-20 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 rounded-md text-sm font-medium transition-colors flex items-center gap-2 no-underline">
                                    📄 Recibo
                                </a>
                            @endif

                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors pointer-events-none"
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
