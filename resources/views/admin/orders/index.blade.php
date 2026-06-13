@extends('layouts.admin', ['title' => 'Encomendas'])
@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-fs-dark text-[1.5rem] font-bold m-0">Encomendas</h1>
                <p class="text-fs-gray text-[0.85rem] mt-1 m-0">Gestão de encomendas</p>
            </div>
        </div>

        {{-- Search + Filters --}}
        <form method="GET" action="{{ route('admin.orders') }}"
            class="bg-white border border-fs-border rounded-[2px] p-6 mb-6">
            <div class="flex gap-4 flex-wrap items-center mb-4">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Pesquisar por ID ou nome do cliente..."
                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-[300px] text-[0.9rem] outline-none focus:border-fs-purple" />
                <div class="flex gap-2 flex-wrap">
                    @foreach (['all' => 'Todas', 'pending' => 'Pendentes', 'closed' => 'Fechadas', 'canceled' => 'Canceladas'] as $value => $label)
                        <a href="{{ route('admin.orders', array_merge(request()->except('status', 'page'), ['status' => $value, 'search' => $search ?? ''])) }}"
                            class="rounded-[1px] px-4 py-2 text-[0.85rem] font-semibold border border-fs-border no-underline {{ ($statusFilter ?? 'all') === $value ? 'bg-fs-purple text-white border-fs-purple' : 'bg-white text-fs-gray hover:text-fs-dark' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3 flex-wrap items-center border-t border-fs-border pt-4">
                <span class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">Filtrar por data</span>
                <div class="flex items-center gap-2">
                    <label class="text-fs-gray text-[0.8rem]">De</label>
                    <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                        class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.5rem] text-[0.85rem] outline-none focus:border-fs-purple" />
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-fs-gray text-[0.8rem]">Até</label>
                    <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                        class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.5rem] text-[0.85rem] outline-none focus:border-fs-purple" />
                </div>
                <button type="submit"
                    class="rounded-[1px] px-4 py-2 text-[0.85rem] font-semibold border border-fs-border no-underline2
                    bg-fs-purple text-white border-fs-purple hover:bg-fs-purple/90 transition-colors">
                    Aplicar
                </button>
                @if ($dateFrom || $dateTo)
                    <a href="{{ route('admin.orders', array_merge(request()->except('date_from', 'date_to', 'page'))) }}"
                        class="text-fs-gray text-[0.8rem] no-underline hover:text-fs-dark transition-colors">
                        Limpar datas
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#f9f8f6]">
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            #</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Cliente</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Data</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Total</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Pagamento</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Estado</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-4 py-3 border-b border-fs-border text-fs-purple text-[0.85rem] font-semibold">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="text-fs-purple no-underline">#{{ $order->id }}</a>
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-fs-dark text-[0.85rem]">
                                {{ $order->customer?->user?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-fs-gray text-[0.85rem]">
                                {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-fs-dark text-[0.85rem] font-semibold">
                                {{ number_format($order->total_price, 2, ',', '.') }}€
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-fs-gray text-[0.85rem]">
                                {{ $order->payment_type ?? '—' }}
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-[0.85rem]">
                                @if ($order->status === 'pending')
                                    <span
                                        class="bg-yellow-50 text-yellow-400 border border-yellow-200 rounded-[1px] px-[0.65rem] py-[0.2rem] text-[0.75rem] font-semibold">Pendente</span>
                                @elseif ($order->status === 'closed')
                                    <span
                                        class="bg-green-50 text-green-600 border border-green-200 rounded-[1px] px-[0.65rem] py-[0.2rem] text-[0.75rem] font-semibold">Fechada</span>
                                @elseif ($order->status === 'canceled')
                                    <span
                                        class="bg-red-50 text-red-400 border border-red-200 rounded-[1px] px-[0.65rem] py-[0.2rem] text-[0.75rem] font-semibold">Cancelada</span>
                                @else
                                    <span class="text-fs-gray">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-[0.85rem]">
                                <div class="flex gap-[0.4rem] flex-wrap items-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="bg-white text-fs-purple border border-fs-purple rounded-[1px] px-[0.65rem] py-[0.3rem] text-[0.78rem] font-semibold no-underline hover:bg-fs-purple hover:text-white transition-colors">
                                        Ver
                                    </a>
                                    @if ($order->isPending())
                                        <form method="POST" action="{{ route('admin.orders.close', $order->id) }}"
                                            class="inline"
                                            onsubmit="return confirm('Fechar a encomenda #{{ $order->id }}?')">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-50 text-green-600 border border-green-200 rounded-[1px] px-[0.65rem] py-[0.3rem] cursor-pointer text-[0.78rem] font-semibold">
                                                Fechar
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.orders.show', $order->id) }}#cancel"
                                            class="bg-red-50 text-red-400 border border-red-200 rounded-[1px] px-[0.65rem] py-[0.3rem] text-[0.78rem] font-semibold no-underline">
                                            Cancelar
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-fs-gray text-[0.9rem]">Nenhuma encomenda
                                encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginator --}}
        @if ($orders->hasPages())
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif

    </div>
@endsection
