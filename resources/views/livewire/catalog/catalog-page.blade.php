<div>
    {{-- Flash --}}
    @if (session('cart_success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-6 right-6 bg-[#f0faf5] border border-[#b7e1cb] text-[#2d6a4f] py-[0.7rem] px-[1.1rem] text-[0.8rem] z-[1000] rounded-[1px]">
            ✓ {{ session('cart_success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-7">
        <div class="text-[0.62rem] font-bold tracking-[0.16em] uppercase text-[#b8b4ae] mb-2">
            Catálogo
        </div>
        <h1 class="text-[1.6rem] font-light tracking-[-0.02em] text-[#1a1a1a] m-0">
            Escolhe o teu <em class="italic font-bold">design.</em>
        </h1>
    </div>

    {{-- Two-column layout --}}
    <div class="grid grid-cols-[220px_1fr] gap-6 items-start">

        {{-- Sidebar --}}
        <aside class="bg-[#eeecea] border border-[#e0ddd8] rounded-[2px] sticky top-6">

            <div class="py-[0.9rem] px-[1.1rem] border-b border-[#e0ddd8] flex items-center justify-between">
                <span class="text-[0.62rem] font-bold tracking-[0.14em] uppercase text-[#888]">Filtros</span>
                @if ($search || $categoryId !== null)
                    <button wire:click="$set('search', ''); $set('categoryId', null)"
                        class="bg-transparent border-none text-[#b8b4ae] text-[0.68rem] font-semibold tracking-[0.08em] uppercase cursor-pointer transition-colors duration-150 hover:text-[#7c6fa0]">
                        Limpar
                    </button>
                @endif
            </div>

            <div class="p-[1.1rem]">

                {{-- Search --}}
                <div class="mb-[1.4rem]">
                    <label
                        class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-[0.55rem]">Pesquisa</label>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Pesquisar..."
                        class="w-full bg-transparent border-0 border-b border-[#ccc9c3] py-[0.4rem] px-0 text-[#1a1a1a] text-[0.82rem] outline-none font-inherit transition-colors duration-200 focus:border-[#7c6fa0] focus:ring-0">
                </div>

                <div class="h-px bg-[#e0ddd8] mb-[1.4rem]"></div>

                {{-- Categories (collapsible) --}}
                <div x-data="{ open: false }" class="mb-[1.4rem]">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full bg-transparent border-none p-0 cursor-pointer mb-[0.65rem]">
                        <label
                            class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] cursor-pointer m-0">Categoria</label>
                        <svg :class="open ? 'rotate-180' : ''" class="transition-transform duration-200 shrink-0"
                            width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 4.5L6 8.5L10 4.5" stroke="#b8b4ae" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="flex flex-col gap-[0.15rem]">
                        <button wire:click="$set('categoryId', null)"
                            class="flex items-center gap-2 w-full bg-transparent border-none py-[0.4rem] px-[0.5rem] cursor-pointer text-left rounded-[1px] transition-colors duration-100 hover:bg-black/5">
                            <span
                                class="w-3 h-3 rounded-full border-[1.5px] shrink-0 {{ $categoryId === null ? 'border-[#7c6fa0] bg-[#7c6fa0]' : 'border-[#ccc9c3] bg-transparent' }}"></span>
                            <span
                                class="text-[0.8rem] {{ $categoryId === null ? 'text-[#7c6fa0] font-semibold' : 'text-[#888] font-normal' }}">Todos</span>
                        </button>

                        @foreach ($categories as $cat)
                            <button wire:click="$set('categoryId', {{ $cat->id }})"
                                class="flex items-center gap-2 w-full bg-transparent border-none py-[0.4rem] px-[0.5rem] cursor-pointer text-left rounded-[1px] transition-colors duration-100 hover:bg-black/5">
                                <span
                                    class="w-3 h-3 rounded-full border-[1.5px] shrink-0 {{ $categoryId === $cat->id ? 'border-[#7c6fa0] bg-[#7c6fa0]' : 'border-[#ccc9c3] bg-transparent' }}"></span>
                                <span
                                    class="text-[0.8rem] {{ $categoryId === $cat->id ? 'text-[#7c6fa0] font-semibold' : 'text-[#888] font-normal' }}">{{ $cat->name }}</span>
                            </button>
                        @endforeach

                        <button wire:click="$set('categoryId', -1)"
                            class="flex items-center gap-2 w-full bg-transparent border-none py-[0.4rem] px-[0.5rem] cursor-pointer text-left rounded-[1px] transition-colors duration-100 hover:bg-black/5">
                            <span
                                class="w-3 h-3 rounded-full border-[1.5px] shrink-0 {{ $categoryId === -1 ? 'border-[#7c6fa0] bg-[#7c6fa0]' : 'border-[#ccc9c3] bg-transparent' }}"></span>
                            <span
                                class="text-[0.8rem] {{ $categoryId === -1 ? 'text-[#7c6fa0] font-semibold' : 'text-[#888] font-normal' }}">Sem
                                categoria</span>
                        </button>
                    </div>
                </div>

                <div class="h-px bg-[#e0ddd8] mb-[1.4rem]"></div>

                {{-- Colors (collapsible) --}}
                <div x-data="{ open: false }" class="mb-[1.4rem]">
                    <button @click="open = !open"
                        class="flex items-center justify-between w-full bg-transparent border-none p-0 cursor-pointer mb-[0.65rem]">
                        <label
                            class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] cursor-pointer m-0">Cores</label>
                        <svg :class="open ? 'rotate-180' : ''" class="transition-transform duration-200 shrink-0"
                            width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 4.5L6 8.5L10 4.5" stroke="#b8b4ae" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="flex flex-wrap gap-[0.35rem] mb-2">
                            @foreach ($colors as $color)
                                <div title="{{ $color->name }}"
                                    class="w-5 h-5 rounded-full border border-black/10 cursor-default transition-transform duration-150 hover:scale-125"
                                    style="background:#{{ $color->code }};">
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[#c8c4be] text-[0.68rem] m-0">Escolhes ao adicionar ao carrinho</p>
                    </div>
                </div>

                <div class="h-px bg-[#e0ddd8] mb-[1.4rem]"></div>

                {{-- Price --}}
                <div>
                    <label
                        class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-[0.55rem]">Preço</label>
                    <div class="py-[0.7rem] px-[0.9rem] bg-[#f5f4f1] border border-[#e0ddd8] rounded-[1px]">
                        <div class="flex justify-between items-baseline mb-[0.3rem]">
                            <span class="text-[#aaa] text-[0.75rem]">Por unidade</span>
                            <span
                                class="text-[#1a1a1a] font-bold text-[0.95rem]">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                        </div>
                        <div class="text-[#b8b4ae] text-[0.68rem] leading-[1.4]">
                            Desc. a partir de {{ $prices->qty_discount }} un.
                            <span class="text-[#2d6a4f] block mt-[2px]">→
                                €{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        {{-- Content --}}
        <div>

            @if ($images->isEmpty())
                <div class="text-center py-20 px-8 border border-[#e0ddd8] rounded-[2px]">
                    <p class="text-[0.9rem] text-[#888] m-0 mb-1">Nenhum design encontrado</p>
                    <p class="text-[0.8rem] text-[#c8c4be] m-0">Tenta uma pesquisa diferente.</p>
                </div>
            @else
                <div class="grid grid-cols-3 gap-4 mb-8">
                    @foreach ($images as $image)
                        <div
                            class="bg-white border border-[#e0ddd8] rounded-[2px] overflow-hidden transition-colors duration-150 hover:border-[#7c6fa0]">

                            @php
                                $bare = basename($image->image_url);
                                if (\Illuminate\Support\Str::startsWith($image->image_url, 'tshirt_images_private/')) {
                                    $thumbDesignUrl = route('private-image', $bare);
                                } elseif (str_contains($image->image_url, '/')) {
                                    $thumbDesignUrl = asset('storage/' . $image->image_url);
                                } else {
                                    $thumbDesignUrl = asset('storage/tshirt_images/' . $bare);
                                }
                            @endphp

                            <div
                                class="bg-[#eeecea] h-[180px] flex items-center justify-center border-b border-[#e0ddd8] overflow-hidden p-4">
                                <img src="{{ $thumbDesignUrl }}" alt="{{ $image->name }}"
                                    class="max-w-full max-h-full object-contain block"
                                    onerror="this.style.opacity='.15'">
                            </div>

                            <div class="p-[0.85rem]">
                                @if ($image->category)
                                    <span
                                        class="inline-block text-[0.6rem] font-bold tracking-[0.1em] uppercase text-[#7c6fa0] mb-[0.35rem]">
                                        {{ $image->category->name }}
                                    </span>
                                @endif
                                <h3
                                    class="text-[#1a1a1a] text-[0.85rem] font-semibold m-0 mb-[0.2rem] whitespace-nowrap overflow-hidden text-ellipsis">
                                    {{ $image->name }}
                                </h3>
                                @if ($image->description)
                                    <p class="text-[#aaa] text-[0.72rem] m-0 mb-[0.65rem] line-clamp-2 leading-[1.45]">
                                        {{ $image->description }}
                                    </p>
                                @endif
                                <div class="flex items-center justify-between mt-2">
                                    <span
                                        class="text-[#1a1a1a] font-bold text-[0.9rem]">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                                    <div class="flex gap-[0.35rem]">
                                        <a href="{{ route('try-on', ['design' => $image->id]) }}" title="Provador 3D"
                                            class="bg-[#f5f4f1] text-[#888] border border-[#e0ddd8] py-[0.3rem] px-[0.55rem] text-[0.72rem] cursor-pointer transition-all duration-150 no-underline inline-flex items-center rounded-[1px] hover:border-[#7c6fa0] hover:text-[#7c6fa0]">
                                            3D
                                        </a>
                                        <button wire:click="openModal({{ $image->id }})"
                                            class="bg-[#1a1a1a] text-[#f5f4f1] border-none py-[0.3rem] px-[0.75rem] text-[0.68rem] font-bold tracking-[0.08em] uppercase cursor-pointer transition-colors duration-150 rounded-[1px] hover:bg-[#333]">
                                            + Adicionar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($images->hasPages())
                    <div class="flex justify-center gap-[0.4rem] py-4">
                        @if ($images->onFirstPage())
                            <span
                                class="bg-[#eeecea] text-[#c8c4be] border border-[#e0ddd8] py-[0.4rem] px-[0.9rem] text-[0.8rem] rounded-[1px]">←</span>
                        @else
                            <button wire:click="previousPage"
                                class="bg-white text-[#888] border border-[#e0ddd8] py-[0.4rem] px-[0.9rem] text-[0.8rem] cursor-pointer rounded-[1px] transition-all duration-150 hover:border-[#1a1a1a]">←</button>
                        @endif

                        @foreach ($images->getUrlRange(max(1, $images->currentPage() - 2), min($images->lastPage(), $images->currentPage() + 2)) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})"
                                class="py-[0.4rem] px-[0.75rem] text-[0.8rem] cursor-pointer rounded-[1px] border transition-all duration-150 {{ $images->currentPage() === $page ? 'bg-[#1a1a1a] text-[#f5f4f1] border-[#1a1a1a]' : 'bg-white text-[#888] border-[#e0ddd8]' }}">
                                {{ $page }}
                            </button>
                        @endforeach

                        @if ($images->hasMorePages())
                            <button wire:click="nextPage"
                                class="bg-white text-[#888] border border-[#e0ddd8] py-[0.4rem] px-[0.9rem] text-[0.8rem] cursor-pointer rounded-[1px] transition-all duration-150 hover:border-[#1a1a1a]">→</button>
                        @else
                            <span
                                class="bg-[#eeecea] text-[#c8c4be] border border-[#e0ddd8] py-[0.4rem] px-[0.9rem] text-[0.8rem] rounded-[1px]">→</span>
                        @endif
                    </div>
                @endif
            @endif
        </div>

    </div>

    {{-- Modal --}}
    @if ($showModal && $selectedImage)
        <div class="fixed inset-0 bg-[#1a1a1a]/40 z-[200] flex items-center justify-center p-4"
            wire:click.self="closeModal">
            <div class="bg-[#f5f4f1] border border-[#e0ddd8] rounded-[2px] w-full max-w-[460px] overflow-hidden">

                <div class="flex items-center justify-between py-[1.1rem] px-[1.35rem] border-b border-[#e0ddd8]">
                    <div>
                        <div class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-[0.2rem]">
                            Adicionar ao carrinho
                        </div>
                        <h3 class="text-[#1a1a1a] text-[0.9rem] font-semibold m-0">{{ $selectedImage->name }}</h3>
                    </div>
                    <button wire:click="closeModal"
                        class="bg-transparent border-none text-[#aaa] cursor-pointer text-[1.2rem] leading-none transition-colors duration-150 hover:text-[#1a1a1a]">×</button>
                </div>

                <div class="p-[1.35rem] flex gap-[1.35rem]">
                    <div class="shrink-0">
                        <x-tshirt-preview :colorCode="$selectedColor ?: 'white'" :imageUrl="$selectedImage->image_url" size="110px" />
                    </div>

                    <div class="flex-1 flex flex-col gap-4">
                        {{-- Color --}}
                        <div>
                            <label
                                class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-[0.5rem]">Cor</label>
                            <div class="flex gap-[0.4rem] flex-wrap">
                                @foreach ($colors as $color)
                                    <button wire:click="$set('selectedColor', '{{ $color->code }}')"
                                        title="{{ $color->name }}"
                                        class="w-[26px] h-[26px] rounded-full cursor-pointer transition-all duration-150"
                                        style="background:#{{ $color->code }}; border:2px solid {{ $selectedColor === $color->code ? '#7c6fa0' : 'transparent' }}; box-shadow:{{ $selectedColor === $color->code ? '0 0 0 2px #7c6fa0' : 'inset 0 0 0 1px rgba(0,0,0,.12)' }};">
                                    </button>
                                @endforeach
                            </div>
                            @if ($selectedColor)
                                <p class="text-[#aaa] text-[0.7rem] mt-[0.3rem] m-0">
                                    {{ $colors->firstWhere('code', $selectedColor)?->name }}
                                </p>
                            @endif
                        </div>

                        {{-- Size --}}
                        <div>
                            <label
                                class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-[0.5rem]">Tamanho</label>
                            <div class="flex gap-[0.35rem]">
                                @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                                    <button wire:click="$set('selectedSize', '{{ $size }}')"
                                        class="border py-[0.28rem] px-[0.55rem] text-[0.75rem] font-semibold cursor-pointer rounded-[1px] transition-colors duration-150 min-w-[34px] font-inherit {{ $selectedSize === $size ? 'bg-[#1a1a1a] text-[#f5f4f1] border-[#1a1a1a]' : 'bg-transparent text-[#888] border-[#d8d5d0]' }}">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Quantidade --}}
                        <div>
                            <label
                                class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-[#b8b4ae] mb-[0.5rem]">
                                Quantidade
                            </label>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="decrementQty"
                                    class="bg-transparent border border-[#d8d5d0] w-[30px] h-[30px] text-[#888] cursor-pointer text-base flex items-center justify-center rounded-[1px] transition-colors duration-150 hover:border-[#1a1a1a]">
                                    −
                                </button>

                                <span class="text-[#1a1a1a] font-semibold min-w-[2rem] text-center text-[0.9rem]">
                                    {{ $qty }}
                                </span>

                                <button type="button" wire:click="incrementQty"
                                    class="bg-transparent border border-[#d8d5d0] w-[30px] h-[30px] text-[#888] cursor-pointer text-base flex items-center justify-center rounded-[1px] transition-colors duration-150 hover:border-[#1a1a1a]">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Preço dinâmico dentro do modal --}}
                        <div
                            class="bg-[#eeecea] border border-[#e0ddd8] py-[0.6rem] px-[0.75rem] text-[0.75rem] rounded-[1px]">

                            {{-- Cálculo do preço unitário --}}
                            @php
                                $currentPrice = $qty >= $qtyThreshold ? $discountPrice : $unitPrice;
                                $subTotal = $qty * $currentPrice;
                            @endphp

                            <div class="flex justify-between text-[#888] mb-1">
                                <span>Preço unitário</span>
                                <span class="text-[#1a1a1a]">€{{ number_format($currentPrice, 2) }}</span>
                            </div>

                            <div
                                class="flex justify-between text-[#1a1a1a] font-bold border-t border-[#e0ddd8] pt-1 mt-1">
                                <span>Total</span>
                                <span class="text-[0.9rem]">€{{ number_format($subTotal, 2) }}</span>
                            </div>

                            {{-- Aviso de desconto --}}
                            @if ($qty < $qtyThreshold)
                                <div class="text-[#c8c4be] mt-[0.25rem] text-[0.68rem]">
                                    Desc. a partir de {{ $qtyThreshold }} unidades
                                </div>
                            @else
                                <div class="flex justify-between text-[#2d6a4f] mt-[0.25rem] text-[0.7rem]">
                                    <span>✓ Desconto aplicado</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div
                    class="py-[0.9rem] px-[1.35rem] border-t border-[#e0ddd8] flex gap-[0.6rem] justify-end bg-[#eeecea]">
                    <button wire:click="closeModal"
                        class="bg-transparent text-[#888] border border-[#d8d5d0] py-[0.5rem] px-[1.1rem] text-[0.7rem] font-bold tracking-[0.1em] uppercase cursor-pointer rounded-[1px] font-inherit transition-colors duration-150 hover:border-[#1a1a1a] hover:text-[#1a1a1a]">
                        Cancelar
                    </button>
                    <button wire:click="addToCart"
                        class="bg-[#1a1a1a] text-[#f5f4f1] border-none py-[0.5rem] px-[1.1rem] text-[0.7rem] font-bold tracking-[0.1em] uppercase cursor-pointer rounded-[1px] font-inherit transition-colors duration-150 hover:bg-[#333]">
                        Adicionar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
