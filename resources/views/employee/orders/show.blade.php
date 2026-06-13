@extends('layouts.app', ['title' => 'Encomenda #' . $order->id])
@section('content')
    <div class="max-w-5xl mx-auto">

        {{-- Back link --}}
        <div class="mb-5">
            <a href="{{ route('employee.orders') }}"
                class="text-fs-gray text-[0.85rem] no-underline inline-flex items-center gap-1.5 hover:text-fs-dark transition-colors">
                ← Voltar às encomendas
            </a>
        </div>

        {{-- Order Header --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-6 mb-6">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <p class="text-[0.72rem] font-semibold uppercase tracking-[0.1em] text-fs-muted m-0 mb-1">Funcionário</p>
                    <h1 class="text-fs-dark text-[1.4rem] font-bold m-0 mb-1">Encomenda #{{ $order->id }}</h1>
                    <p class="text-fs-gray text-[0.85rem] m-0">
                        {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    @if ($order->status === 'pending')
                        <span
                            class="bg-yellow-50 text-yellow-500 border border-yellow-200 rounded-[1px] px-3 py-[0.3rem] text-[0.8rem] font-semibold">Pendente</span>
                    @elseif ($order->status === 'closed')
                        <span
                            class="bg-green-50 text-green-600 border border-green-200 rounded-[1px] px-3 py-[0.3rem] text-[0.8rem] font-semibold">Fechada</span>
                    @elseif ($order->status === 'canceled')
                        <span
                            class="bg-red-50 text-red-400 border border-red-200 rounded-[1px] px-3 py-[0.3rem] text-[0.8rem] font-semibold">Cancelada</span>
                    @endif

                    @if ($order->isPending())
                        <form method="POST" action="{{ route('employee.orders.close', $order->id) }}" class="inline"
                            onsubmit="return confirm('Fechar a encomenda #{{ $order->id }}?')">
                            @csrf
                            <button type="submit"
                                class="bg-fs-purple text-white border-none rounded-[1px] px-4 py-[0.45rem] cursor-pointer text-[0.85rem] font-semibold hover:opacity-80 transition-opacity">
                                ✓ Fechar encomenda
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Client info --}}
            <div class="mt-4 pt-4 border-t border-fs-border flex gap-8 flex-wrap">
                <div>
                    <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Cliente</p>
                    <p class="text-fs-dark text-[0.9rem] m-0">{{ $order->customer?->user?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Email</p>
                    <p class="text-fs-dark text-[0.9rem] m-0">{{ $order->customer?->user?->email ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Two column layout --}}
        <div class="grid gap-6 items-start" style="grid-template-columns:1fr 300px;">

            {{-- Items table --}}
            <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
                <div class="px-5 py-4 border-b border-fs-border">
                    <h2 class="text-fs-dark text-[1rem] font-semibold m-0">Itens da encomenda</h2>
                </div>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-fs-bg border-b border-fs-border">
                            <th
                                class="px-4 py-3 text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Produto</th>
                            <th
                                class="px-4 py-3 text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Cor</th>
                            <th
                                class="px-4 py-3 text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Tam.</th>
                            <th
                                class="px-4 py-3 text-right text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Qtd.</th>
                            <th
                                class="px-4 py-3 text-right text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em]">
                                Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-fs-border">
                        @forelse ($order->items as $item)
                            <tr class="hover:bg-fs-light transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <x-tshirt-preview :colorCode="$item->color_code" :imageUrl="$item->tshirtImage?->image_url" size="44px" />
                                        <span
                                            class="text-fs-dark text-[0.85rem]">{{ $item->tshirtImage?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">
                                        @if ($item->color_code)
                                            <span class="inline-block w-3 h-3 rounded-full border border-black/10 shrink-0"
                                                style="background:{{ $item->color_code }};"></span>
                                        @endif
                                        <span
                                            class="text-fs-gray text-[0.85rem]">{{ $item->color?->name ?? ($item->color_code ?? '—') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-fs-gray text-[0.85rem]">{{ $item->size ?? '—' }}</td>
                                <td class="px-4 py-3 text-fs-dark text-[0.85rem] text-right">{{ $item->qty }}</td>
                                <td class="px-4 py-3 text-fs-dark text-[0.85rem] font-semibold text-right">
                                    {{ number_format($item->sub_total, 2, ',', '.') }}€</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-fs-gray text-[0.85rem]">Sem itens.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Resumo --}}
            <div class="bg-white border border-fs-border rounded-[2px] p-6">
                <h2 class="text-fs-dark text-[1rem] font-semibold m-0 mb-5">Resumo</h2>
                <div class="flex flex-col gap-4">
                    <div>
                        <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Morada</p>
                        <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">NIF</p>
                        <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->nif ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Pagamento
                        </p>
                        <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->payment_type ?? '—' }}</p>
                    </div>
                    @if ($order->payment_ref ?? null)
                        <div>
                            <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">
                                Referência</p>
                            <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->payment_ref }}</p>
                        </div>
                    @endif
                    @if ($order->notes ?? null)
                        <div>
                            <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Notas
                            </p>
                            <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->notes }}</p>
                        </div>
                    @endif
                    <div class="border-t border-fs-border pt-4 mt-1">
                        <div class="flex justify-between items-center">
                            <p class="text-fs-gray text-[0.85rem] m-0">Total</p>
                            <p class="text-fs-dark text-[1.1rem] font-bold m-0">
                                {{ number_format($order->total_price, 2, ',', '.') }}€</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
