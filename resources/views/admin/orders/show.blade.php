@extends('layouts.admin', ['title' => 'Encomenda #' . $order->id])
@section('content')

<div class="p-6" x-data="{ showCancelModal: false, cancelReason: '' }">

    {{-- Back link --}}
    <div class="mb-5">
        <a href="{{ route('admin.orders') }}" class="text-fs-gray text-[0.85rem] no-underline inline-flex items-center gap-[0.4rem] hover:text-fs-dark transition-colors">
            ← Voltar às encomendas
        </a>
    </div>

    {{-- Order Header --}}
    <div class="bg-white border border-fs-border rounded-[2px] p-6 mb-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-fs-dark text-[1.4rem] font-bold m-0 mb-1">Encomenda #{{ $order->id }}</h1>
                <p class="text-fs-gray text-[0.85rem] m-0">
                    {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d/m/Y') : '—' }}
                </p>
            </div>
            <div class="flex items-center gap-4 flex-wrap">
                {{-- Status badge --}}
                @if ($order->status === 'pending')
                    <span class="bg-yellow-50 text-yellow-400 border border-yellow-200 rounded-[1px] px-[0.85rem] py-[0.3rem] text-[0.8rem] font-semibold">Pendente</span>
                @elseif ($order->status === 'closed')
                    <span class="bg-green-50 text-green-600 border border-green-200 rounded-[1px] px-[0.85rem] py-[0.3rem] text-[0.8rem] font-semibold">Fechada</span>
                @elseif ($order->status === 'canceled')
                    <span class="bg-red-50 text-red-400 border border-red-200 rounded-[1px] px-[0.85rem] py-[0.3rem] text-[0.8rem] font-semibold">Cancelada</span>
                @endif

                {{-- Actions for pending --}}
                @if ($order->isPending())
                    <form method="POST" action="{{ route('admin.orders.close', $order->id) }}" class="inline" onsubmit="return confirm('Fechar esta encomenda?')">
                        @csrf
                        <button type="submit"
                            class="bg-green-50 text-green-600 border border-green-300 rounded-[1px] px-4 py-[0.45rem] cursor-pointer text-[0.85rem] font-semibold hover:bg-green-100 transition-colors">
                            Fechar encomenda
                        </button>
                    </form>
                    <button
                        type="button"
                        @click="showCancelModal = true"
                        class="bg-red-50 text-red-400 border border-red-300 rounded-[1px] px-4 py-[0.45rem] cursor-pointer text-[0.85rem] font-semibold hover:bg-red-100 transition-colors">
                        Cancelar encomenda
                    </button>
                @endif

                {{-- Receipt link --}}
                @if ($order->receipt_url)
                    <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank"
                       class="bg-purple-50 text-fs-purple border border-purple-200 rounded-[1px] px-4 py-[0.45rem] text-[0.85rem] font-semibold no-underline hover:bg-purple-100 transition-colors">
                        Recibo PDF
                    </a>
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
    <div class="grid gap-6 items-start grid-cols-[1fr_340px]">

        {{-- Left: Items table --}}
        <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
            <div class="px-5 py-4 border-b border-fs-border">
                <h2 class="text-fs-dark text-[1rem] font-semibold m-0">Itens da encomenda</h2>
            </div>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#f9f8f6]">
                        <th class="px-4 py-[0.65rem] text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Imagem</th>
                        <th class="px-4 py-[0.65rem] text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Cor</th>
                        <th class="px-4 py-[0.65rem] text-left text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Tam.</th>
                        <th class="px-4 py-[0.65rem] text-right text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Qtd.</th>
                        <th class="px-4 py-[0.65rem] text-right text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Preço unit.</th>
                        <th class="px-4 py-[0.65rem] text-right text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($order->items as $item)
                        <tr>
                            <td class="px-4 py-[0.65rem] border-b border-fs-border text-fs-dark text-[0.85rem]">
                                <div class="flex items-center gap-2">
                                    <x-tshirt-preview :colorCode="$item->color_code" :imageUrl="$item->tshirtImage?->image_url" size="50px" />
                                    <span>{{ $item->tshirtImage?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-[0.65rem] border-b border-fs-border text-[0.85rem]">
                                <div class="flex items-center gap-[0.4rem]">
                                    @if ($item->color_code)
                                        <span class="inline-block w-[14px] h-[14px] rounded-full border border-white/15 flex-shrink-0"
                                              style="background:{{ $item->color_code }};"></span>
                                    @endif
                                    <span class="text-fs-gray">{{ $item->color?->name ?? $item->color_code ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-[0.65rem] border-b border-fs-border text-fs-gray text-[0.85rem]">{{ $item->size ?? '—' }}</td>
                            <td class="px-4 py-[0.65rem] border-b border-fs-border text-fs-dark text-[0.85rem] text-right">{{ $item->qty }}</td>
                            <td class="px-4 py-[0.65rem] border-b border-fs-border text-fs-gray text-[0.85rem] text-right">{{ number_format($item->unit_price, 2, ',', '.') }}€</td>
                            <td class="px-4 py-[0.65rem] border-b border-fs-border text-fs-dark text-[0.85rem] font-semibold text-right">{{ number_format($item->sub_total, 2, ',', '.') }}€</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-fs-gray text-[0.85rem]">Sem itens.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Right: Order summary --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-6">
            <h2 class="text-fs-dark text-[1rem] font-semibold m-0 mb-5">Resumo</h2>

            <div class="flex flex-col gap-[0.85rem]">
                <div>
                    <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Morada</p>
                    <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->address ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">NIF</p>
                    <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->nif ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Forma de pagamento</p>
                    <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->payment_type ?? '—' }}</p>
                </div>
                @if ($order->payment_ref ?? null)
                    <div>
                        <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Referência de pagamento</p>
                        <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->payment_ref }}</p>
                    </div>
                @endif
                @if ($order->notes ?? null)
                    <div>
                        <p class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-1">Notas</p>
                        <p class="text-fs-dark text-[0.85rem] m-0">{{ $order->notes }}</p>
                    </div>
                @endif

                <div class="border-t border-fs-border pt-[0.85rem] mt-1">
                    <div class="flex justify-between items-center">
                        <p class="text-fs-gray text-[0.85rem] m-0">Total</p>
                        <p class="text-fs-dark text-[1.1rem] font-bold m-0">{{ number_format($order->total_price, 2, ',', '.') }}€</p>
                    </div>
                </div>

                @if ($order->isCanceled() && ($order->reason_for_cancellation ?? null))
                    <div class="bg-red-50 border border-red-200 rounded-[1px] p-[0.85rem] mt-1">
                        <p class="text-red-400 text-[0.75rem] font-semibold uppercase tracking-[0.05em] m-0 mb-[0.3rem]">Motivo do cancelamento</p>
                        <p class="text-red-300 text-[0.85rem] m-0">{{ $order->reason_for_cancellation }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Cancel Modal (Alpine.js) --}}
    <div
        x-show="showCancelModal"
        x-cloak
        class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
        id="cancel"
    >
        <div class="bg-white border border-fs-border rounded-[2px] p-8 w-full max-w-[480px]">
            <h2 class="text-fs-dark text-[1.1rem] font-bold m-0 mb-2">Cancelar encomenda #{{ $order->id }}</h2>
            <p class="text-fs-gray text-[0.85rem] m-0 mb-5">Indique o motivo do cancelamento.</p>

            <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}">
                @csrf
                <textarea
                    name="reason"
                    x-model="cancelReason"
                    placeholder="Motivo do cancelamento..."
                    rows="4"
                    class="w-full bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.65rem] text-[0.9rem] outline-none resize-y focus:border-fs-purple"
                ></textarea>
                @error('reason')
                    <p class="text-red-400 text-[0.8rem] mt-[0.4rem] m-0">{{ $message }}</p>
                @enderror

                <div class="flex justify-end gap-3 mt-5">
                    <button
                        type="button"
                        @click="showCancelModal = false"
                        class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-5 py-2 cursor-pointer text-[0.875rem] font-semibold hover:border-fs-dark hover:text-fs-dark transition-colors">
                        Fechar
                    </button>
                    <button
                        type="submit"
                        class="bg-red-600 text-white border-none rounded-[1px] px-5 py-2 cursor-pointer text-[0.875rem] font-semibold hover:bg-red-700 transition-colors">
                        Confirmar cancelamento
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
