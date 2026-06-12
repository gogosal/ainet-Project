<div>
    {{-- Backdrop --}}
    @if ($open)
        <div wire:click="close" class="fixed inset-0 bg-[#1a1a1a]/35 z-[150]"
            x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">
        </div>
    @endif

    {{-- Drawer --}}
    <div
        class="fixed top-0 right-0 h-screen w-[360px] max-w-[100vw] bg-[#f5f4f1] border-l border-[#e0ddd8] z-[160] flex flex-col transition-transform duration-250 ease-in-out {{ $open ? 'translate-x-0' : 'translate-x-full' }}">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-[#e0ddd8] flex items-center justify-between shrink-0">
            <div class="flex items-center gap-[0.6rem]">
                <span class="text-[0.7rem] font-bold tracking-[0.16em] uppercase text-[#1a1a1a]">Carrinho</span>
                @if (count($items) > 0)
                    <span
                        class="bg-[#1a1a1a] text-[#f5f4f1] rounded-full py-[1px] px-[7px] text-[0.65rem] font-bold">{{ count($items) }}</span>
                @endif
            </div>
            <button wire:click="close"
                class="bg-transparent border-none text-[#aaa] cursor-pointer text-[1.2rem] leading-none p-1 transition-colors duration-150 hover:text-[#1a1a1a]">×</button>
        </div>

        {{-- Items --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">
            @if (empty($items))
                <div class="text-center py-14 px-4 text-[#b8b4ae]">
                    <svg class="w-10 h-10 mx-auto mb-4 block stroke-[#d8d5d0]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-[0.82rem] m-0 mb-[0.2rem] text-[#888]">Carrinho vazio</p>
                    <p class="text-[0.75rem] m-0 text-[#c8c4be]">Explora o catálogo.</p>
                </div>
            @else
                <div class="flex flex-col gap-[0.6rem]">
                    @foreach ($items as $item)
                        <div
                            class="bg-[#eeecea] border border-[#e0ddd8] rounded-[2px] p-[0.85rem] flex gap-[0.75rem] items-start">
                            <div class="shrink-0">
                                <x-tshirt-preview :colorCode="$item['color_code']" :imageUrl="$item['image'] ? $item['image']->image_url : null" size="52px" />
                            </div>

                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-[#1a1a1a] text-[0.82rem] font-semibold m-0 mb-[0.2rem] whitespace-nowrap overflow-hidden text-ellipsis">
                                    {{ $item['image']?->name ?? 'Design removido' }}
                                </p>
                                <p class="text-[#aaa8a3] text-[0.72rem] m-0 mb-[0.4rem]">
                                    {{ $item['color']?->name ?? $item['color_code'] }} · {{ $item['size'] }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="font-bold text-[0.82rem] {{ $item['has_discount'] ? 'text-[#2d6a4f]' : 'text-[#1a1a1a]' }}">
                                        €{{ number_format($item['sub_total'], 2) }}
                                    </span>
                                    <span class="text-[#b8b4ae] text-[0.7rem]">{{ $item['qty'] }}×
                                        €{{ number_format($item['unit_price'], 2) }}</span>
                                </div>
                            </div>

                            <button wire:click="remove({{ $item['index'] }})"
                                class="bg-transparent border-none text-[#c8c4be] cursor-pointer p-[0.15rem] shrink-0 text-[0.95rem] leading-none transition-colors duration-150 hover:text-[#c0392b]"
                                title="Remover">×</button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer --}}
        @if (!empty($items))
            <div class="px-6 py-4 border-t border-[#e0ddd8] shrink-0 bg-[#eeecea]">
                <div class="flex justify-between items-baseline mb-4">
                    <span class="text-[0.62rem] font-bold tracking-[0.14em] uppercase text-[#aaa8a3]">Total</span>
                    <span class="text-[#1a1a1a] font-bold text-[1.1rem]">€{{ number_format($total, 2) }}</span>
                </div>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('cart') }}" wire:click="close"
                        class="block text-center bg-transparent text-[#888] no-underline border border-[#d8d5d0] p-[0.6rem] text-[0.7rem] font-bold tracking-[0.1em] uppercase rounded-[1px] transition-all duration-150 hover:border-[#1a1a1a] hover:text-[#1a1a1a]">
                        Ver carrinho
                    </a>
                    @auth
                        @if (auth()->user()->isClient())
                            <a href="{{ route('checkout') }}" wire:click="close"
                                class="block text-center bg-[#1a1a1a] text-[#f5f4f1] no-underline p-[0.6rem] text-[0.7rem] font-bold tracking-[0.1em] uppercase rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
                                Finalizar compra →
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" wire:click="close"
                            class="block text-center bg-[#1a1a1a] text-[#f5f4f1] no-underline p-[0.6rem] text-[0.7rem] font-bold tracking-[0.1em] uppercase rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
                            Entrar para finalizar →
                        </a>
                    @endauth
                </div>
            </div>
        @endif
    </div>
</div>
