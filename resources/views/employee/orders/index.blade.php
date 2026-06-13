@extends('layouts.app', ['title' => 'Encomendas Pendentes'])

@section('content')
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-[0.72rem] font-semibold uppercase tracking-[0.1em] text-fs-muted m-0 mb-1">Funcionário</p>
            <h1 class="text-fs-dark text-[1.5rem] font-bold m-0 mb-1">Encomendas Pendentes</h1>
            <p class="text-fs-gray text-[0.85rem] m-0">Processa e fecha as encomendas após estampagem e envio.</p>
        </div>

        {{-- Empty state --}}
        @if ($orders->isEmpty())
            <div class="text-center py-16 px-8 bg-white border border-fs-border rounded-[2px]">
                <p class="text-fs-gray m-0">Não há encomendas pendentes de momento.</p>
            </div>
        @else
            <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-fs-bg border-b border-fs-border">
                            <th
                                class="px-4 py-3 text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                #</th>
                            <th
                                class="px-4 py-3 text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Cliente</th>
                            <th
                                class="px-4 py-3 text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Data</th>
                            <th
                                class="px-4 py-3 text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Artigos</th>
                            <th
                                class="px-4 py-3 text-right text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Total</th>
                            <th
                                class="px-4 py-3 text-right text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-fs-border">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-fs-light transition-colors">
                                <td class="px-4 py-3 text-fs-purple text-[0.85rem] font-semibold">#{{ $order->id }}</td>
                                <td class="px-4 py-3">
                                    <p class="text-fs-dark text-[0.85rem] font-medium m-0">
                                        {{ $order->customer->user->name }}</p>
                                    <p class="text-fs-gray text-[0.75rem] m-0 mt-0.5">{{ $order->customer->user->email }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-fs-gray text-[0.85rem]">{{ $order->date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-fs-gray text-[0.85rem]">{{ $order->items->count() }}</td>
                                <td class="px-4 py-3 text-fs-dark font-semibold text-[0.9rem] text-right">
                                    €{{ number_format($order->total_price, 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('employee.orders.show', $order->id) }}"
                                            class="text-fs-purple border border-fs-purple rounded-[1px] px-3 py-[0.3rem] text-[0.78rem] font-semibold no-underline hover:bg-fs-purple hover:text-white transition-colors">
                                            Ver
                                        </a>
                                        <form method="POST" action="{{ route('employee.orders.close', $order->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-fs-purple text-white border-none rounded-[1px] px-3 py-[0.3rem] text-[0.78rem] font-semibold cursor-pointer hover:opacity-80 transition-opacity">
                                                ✓ Fechar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $orders->links() }}</div>
        @endif

    </div>
@endsection
