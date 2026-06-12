@props(['cartCount' => 0, 'cartEnrichedItems' => [], 'cartTotal' => 0])

<div x-data="{ open: false }" @open-cart.window="open = true">
    <div x-cloak x-show="open" @click="open = false" class="fixed inset-0 bg-[rgba(26,26,26,.35)] z-[150]"></div>

    <div x-cloak :class="open ? 'translate-x-0' : 'translate-x-full'"
        class="fixed top-0 right-0 h-screen w-[360px] max-w-[100vw] bg-[#f5f4f1] border-l border-[#e0ddd8] z-[160] flex flex-col transition-transform duration-250 ease-in-out">
        <div class="px-6 py-5 border-b border-[#e0ddd8] flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <span class="text-[.7rem] font-bold tracking-[.16em] uppercase text-[#1a1a1a]">Carrinho</span>
                @if ($cartCount > 0)
                    <span
                        class="bg-[#1a1a1a] text-[#f5f4f1] rounded-full px-2 py-0.5 text-[.65rem] font-bold">{{ $cartCount }}</span>
                @endif
            </div>
            <button @click="open = false"
                class="bg-transparent border-none text-[#aaa] cursor-pointer text-xl leading-none p-1 transition-colors duration-150 hover:text-[#1a1a1a]">×</button>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-4">
            @if (empty($cartEnrichedItems))
                <div class="text-center py-14 px-4 text-[#b8b4ae]">
                    <svg class="w-10 h-10 mx-auto mb-4 block stroke-[#d8d5d0]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="text-[.82rem] m-0 mb-1 text-[#888]">Carrinho vazio</p>
                    <p class="text-xs m-0 text-[#c8c4be]">Explora o catálogo.</p>
                </div>
            @else
                <div class="flex flex-col gap-2.5">
                    @foreach ($cartEnrichedItems as $item)
                        <div
                            class="bg-[#eeecea] border border-[#e0ddd8] rounded-[2px] p-[.85rem] flex gap-3 items-start">
                            <div class="shrink-0">
                                <x-tshirt-preview :colorCode="$item['color_code']" :imageUrl="$item['image'] ? $item['image']->image_url : null" size="52px" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-[#1a1a1a] text-[.82rem] font-semibold m-0 mb-1 whitespace-nowrap overflow-hidden text-ellipsis">
                                    {{ $item['image']?->name ?? 'Design removido' }}
                                </p>
                                <p class="text-[#aaa8a3] text-[.72rem] m-0 mb-1.5">
                                    {{ $item['color']?->name ?? $item['color_code'] }} · {{ $item['size'] }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="{{ $item['has_discount'] ? 'text-[#2d6a4f]' : 'text-[#1a1a1a]' }} font-bold text-[.82rem]">
                                        €{{ number_format($item['sub_total'], 2) }}
                                    </span>
                                    <span class="text-[#b8b4ae] text-[.7rem]">{{ $item['qty'] }}×
                                        €{{ number_format($item['unit_price'], 2) }}</span>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('cart.destroy', $item['index']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-transparent border-none text-[#c8c4be] cursor-pointer p-0.5 shrink-0 text-[.95rem] leading-none transition-colors duration-150 hover:text-[#c0392b]"
                                    title="Remover">×</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if (!empty($cartEnrichedItems))
            <div class="px-6 py-4 border-t border-[#e0ddd8] shrink-0 bg-[#eeecea]">
                <div class="flex justify-between items-baseline mb-4">
                    <span class="text-[.62rem] font-bold tracking-[.14em] uppercase text-[#aaa8a3]">Total</span>
                    <span class="text-[#1a1a1a] font-bold text-[1.1rem]">€{{ number_format($cartTotal, 2) }}</span>
                </div>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('cart') }}" @click="open = false"
                        class="block text-center bg-transparent text-[#888] no-underline border border-[#d8d5d0] p-2.5 text-[.7rem] font-bold tracking-[.1em] uppercase rounded-[1px] transition-all duration-150 hover:border-[#1a1a1a] hover:text-[#1a1a1a]">
                        Ver carrinho
                    </a>
                    @auth
                        @if (auth()->user()->isClient())
                            <a href="{{ route('checkout') }}" @click="open = false"
                                class="block text-center bg-[#1a1a1a] text-[#f5f4f1] no-underline p-2.5 text-[.7rem] font-bold tracking-[.1em] uppercase rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
                                Finalizar compra →
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" @click="open = false"
                            class="block text-center bg-[#1a1a1a] text-[#f5f4f1] no-underline p-2.5 text-[.7rem] font-bold tracking-[.1em] uppercase rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
                            Entrar para finalizar →
                        </a>
                    @endauth
                </div>
            </div>
        @endif
    </div>
</div>
