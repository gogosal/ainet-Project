<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="mb-8">
        <div class="text-[0.62rem] font-bold tracking-[0.16em] uppercase text-[#b8b4ae] mb-2">Carrinho</div>
        <h1 class="text-[1.6rem] font-light tracking-[-0.02em] text-[#1a1a1a] m-0">A tua <em
                class="italic font-bold">selecção.</em></h1>
    </div>

    @if (empty($items))
        <div class="text-center py-20 border border-[#e0ddd8] rounded-[2px] bg-[#eeecea]">
            <svg class="w-12 h-12 mx-auto mb-4 block stroke-[#d8d5d0]" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-[#888] text-[1rem] font-normal m-0 mb-2">O teu carrinho está vazio</h2>
            <p class="text-[#b8b4ae] text-[0.82rem] m-0 mb-6">Explora o catálogo e escolhe os teus designs.</p>
            <a href="{{ route('catalog') }}"
                class="inline-block bg-[#1a1a1a] text-[#f5f4f1] no-underline py-[0.65rem] px-6 text-[0.7rem] font-bold tracking-[0.12em] uppercase rounded-[1px] transition-colors hover:bg-[#333]">
                Ver catálogo →
            </a>
        </div>
    @else
        <div class="grid grid-cols-[1fr_300px] gap-6 items-start">

            {{-- Items --}}
            <div>
                <div class="flex justify-end mb-3">
                    <button wire:click="clear" wire:confirm="Tens a certeza que queres limpar o carrinho?"
                        class="bg-transparent border-none text-[#b8b4ae] text-[0.68rem] font-semibold tracking-[0.1em] uppercase cursor-pointer transition-colors duration-150 hover:text-[#c0392b]">
                        Limpar tudo
                    </button>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach ($items as $item)
                        <div class="bg-white border border-[#e0ddd8] rounded-[2px] p-4 flex gap-4 items-start">
                            <div class="shrink-0">
                                <x-tshirt-preview :colorCode="$editColors[$item['index']] ?? $item['color_code']" :imageUrl="$item['image'] ? $item['image']->image_url : null" size="76px" />
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-[#1a1a1a] text-[0.9rem] font-semibold m-0 mb-3">
                                    {{ $item['image']?->name ?? 'Design removido' }}
                                </h3>

                                <div class="grid grid-cols-[1fr_1fr_auto] gap-3 items-end">
                                    {{-- Cor --}}
                                    <div>
                                        <label
                                            class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-1">Cor</label>
                                        <div class="relative">
                                            <select wire:model="editColors.{{ $item['index'] }}"
                                                wire:change="updateItem({{ $item['index'] }})"
                                                class="w-full bg-white border border-[#d8d5d0] rounded-md py-1.5 pl-2 pr-7 text-[0.8rem] text-[#1a1a1a] outline-none cursor-pointer focus:border-[#7c6fa0] focus:ring-1 focus:ring-[#7c6fa0] appearance-none transition-all">
                                                @foreach ($colors as $color)
                                                    <option value="{{ $color->code }}">{{ $color->name }}</option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#aaa]">
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                                    <path
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Tamanho --}}
                                    <div>
                                        <label
                                            class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-1">Tamanho</label>
                                        <div class="relative">
                                            <select wire:model="editSizes.{{ $item['index'] }}"
                                                wire:change="updateItem({{ $item['index'] }})"
                                                class="w-full bg-white border border-[#d8d5d0] rounded-md py-1.5 pl-2 pr-7 text-[0.8rem] text-[#1a1a1a] outline-none cursor-pointer focus:border-[#7c6fa0] focus:ring-1 focus:ring-[#7c6fa0] appearance-none transition-all">
                                                @foreach (['XS', 'S', 'M', 'L', 'XL'] as $s)
                                                    <option value="{{ $s }}">{{ $s }}</option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#aaa]">
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                                    <path
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Qtd --}}
                                    <div>
                                        <label
                                            class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-1">Qtd</label>
                                        <div class="flex items-center gap-1">
                                            <button wire:click="decrementQty({{ $item['index'] }})"
                                                class="bg-transparent border border-[#d8d5d0] w-7 h-7 flex items-center justify-center rounded-[1px] hover:border-[#1a1a1a] transition-all">−</button>
                                            <input type="number" wire:model="editQtys.{{ $item['index'] }}"
                                                wire:change="updateItem({{ $item['index'] }})" min="0"
                                                max="99"
                                                class="w-10 text-center border-b border-[#ccc9c3] outline-none text-[0.82rem]
           [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                                            <button wire:click="incrementQty({{ $item['index'] }})"
                                                class="bg-transparent border border-[#d8d5d0] w-7 h-7 flex items-center justify-center rounded-[1px] hover:border-[#1a1a1a] transition-all">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-1 shrink-0">
                                <span
                                    class="font-bold text-[0.95rem] {{ $item['has_discount'] ? 'text-[#2d6a4f]' : 'text-[#1a1a1a]' }}">
                                    €{{ number_format($item['sub_total'], 2) }}
                                </span>
                                <span class="text-[#b8b4ae] text-[0.7rem]">{{ $item['qty'] }}×
                                    €{{ number_format($item['unit_price'], 2) }}</span>
                                <button wire:click="remove({{ $item['index'] }})"
                                    class="text-[#c8c4be] text-[0.72rem] font-bold uppercase tracking-[0.08em] mt-2 transition-colors hover:text-[#c0392b]">
                                    Remover
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Summary --}}
            <div class="bg-[#eeecea] border border-[#e0ddd8] rounded-[2px] p-5 sticky top-[72px]">
                <div class="text-[0.62rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-4">Resumo</div>
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-[#888] text-[0.82rem]">
                        <span>Artigos ({{ count($items) }})</span>
                        <span class="text-[#1a1a1a]">€{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-[#888] text-[0.82rem]">
                        <span>Envio</span>
                        <span class="text-[#2d6a4f]">Grátis</span>
                    </div>
                </div>
                <div class="border-t border-[#e0ddd8] pt-3 mb-5 flex justify-between items-baseline">
                    <span class="text-[0.62rem] font-bold tracking-[0.14em] uppercase text-[#888]">Total</span>
                    <span class="text-[#1a1a1a] font-bold text-[1.15rem]">€{{ number_format($total, 2) }}</span>
                </div>

                @auth
                    @if (auth()->user()->isClient())
                        <a href="{{ route('checkout') }}"
                            class="block text-center bg-[#1a1a1a] text-[#f5f4f1] p-[0.7rem] text-[0.7rem] font-bold tracking-[0.12em] uppercase rounded-[1px] mb-3 hover:bg-[#333] transition-colors">Finalizar
                            compra →</a>
                    @else
                        <p class="text-[#b8b4ae] text-[0.75rem] text-center mb-3">Só clientes podem comprar.</p>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="block text-center bg-[#1a1a1a] text-[#f5f4f1] p-[0.7rem] text-[0.7rem] font-bold tracking-[0.12em] uppercase rounded-[1px] mb-3 hover:bg-[#333] transition-colors">Entrar
                        para finalizar →</a>
                    <p class="text-[#c8c4be] text-[0.7rem] text-center mb-3">O carrinho mantém-se após o login.</p>
                @endauth

                <a href="{{ route('catalog') }}"
                    class="block text-center text-[#b8b4ae] text-[0.7rem] font-semibold tracking-[0.08em] uppercase py-1 hover:text-[#7c6fa0] transition-colors">
                    ← Continuar a comprar
                </a>
            </div>
        </div>
    @endif
</div>
