@extends('layouts.employee', ['title' => 'Encomendas Pendentes'])

@section('content')
<div>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-fs-dark text-[1.4rem] font-bold m-0 mb-1">Encomendas Pendentes</h1>
        <p class="text-[#aaa] text-[0.85rem] m-0">Processa e fecha as encomendas após estampagem e envio.</p>
    </div>

    {{-- Empty state --}}
    @if($orders->isEmpty())
        <div class="text-center py-16 px-8 bg-white border border-fs-border rounded-[2px]">
            <p class="text-fs-gray m-0">Não há encomendas pendentes de momento. 🎉</p>
        </div>
    @else
        <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#0d0d1a] border-b border-fs-border">
                        <th class="text-[#aaa] text-[0.75rem] font-semibold uppercase tracking-[0.04em] py-3 px-4 text-left">#</th>
                        <th class="text-[#aaa] text-[0.75rem] font-semibold uppercase tracking-[0.04em] py-3 px-4 text-left">Cliente</th>
                        <th class="text-[#aaa] text-[0.75rem] font-semibold uppercase tracking-[0.04em] py-3 px-4 text-left">Data</th>
                        <th class="text-[#aaa] text-[0.75rem] font-semibold uppercase tracking-[0.04em] py-3 px-4 text-left">Artigos</th>
                        <th class="text-[#aaa] text-[0.75rem] font-semibold uppercase tracking-[0.04em] py-3 px-4 text-right">Total</th>
                        <th class="text-[#aaa] text-[0.75rem] font-semibold uppercase tracking-[0.04em] py-3 px-4 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b border-[#0d0d1a]">
                            <td class="py-[0.85rem] px-4 text-fs-gray text-[0.85rem]">#{{ $order->id }}</td>
                            <td class="py-[0.85rem] px-4">
                                <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->customer->user->name }}</p>
                                <p class="text-[#aaa] text-[0.75rem] m-0 mt-[0.1rem]">{{ $order->customer->user->email }}</p>
                            </td>
                            <td class="py-[0.85rem] px-4 text-fs-gray text-[0.85rem]">{{ $order->date->format('d/m/Y') }}</td>
                            <td class="py-[0.85rem] px-4 text-fs-gray text-[0.85rem]">{{ $order->items->count() }}</td>
                            <td class="py-[0.85rem] px-4 text-fs-purple font-semibold text-[0.9rem] text-right">€{{ number_format($order->total_price, 2) }}</td>
                            <td class="py-[0.85rem] px-4 text-right">
                                <form method="POST" action="{{ route('employee.orders.close', $order->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="bg-fs-purple text-white border-0 rounded-[6px] py-[0.4rem] px-[0.9rem] text-[0.8rem] font-semibold cursor-pointer hover:bg-[#6b5f90] transition-colors duration-150"
                                    >
                                        ✓ Fechar
                                    </button>
                                </form>
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
