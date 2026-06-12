@extends('layouts.app', ['title' => 'Catálogo'])

@section('content')

    {{-- Flash toast --}}
    @if (session('cart_success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed bottom-6 right-6 z-[1000] bg-[#f0faf5] border border-[#b7e1cb] text-fs-green px-[1.1rem] py-[0.7rem] text-[0.8rem] rounded-[1px]">
            ✓ {{ session('cart_success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-[1.75rem]">
        <div class="text-[0.62rem] font-bold tracking-[0.16em] uppercase text-fs-muted mb-2">Catálogo</div>
        <h1 class="text-[1.6rem] font-light tracking-[-0.02em] text-fs-dark m-0">Escolhe o teu <em
                class="italic font-bold">design.</em></h1>
    </div>

    {{-- Alpine modal state --}}
    <div x-data="{
        showModal: false,
        selectedImageId: null,
        selectedImageName: '',
        selectedImageUrl: '',
        selectedColor: '{{ $colors->first()?->code ?? '' }}',
        selectedSize: 'M',
        qty: 1,
        openModal(id, name, imgUrl, firstColor) {
            this.selectedImageId = id;
            this.selectedImageName = name;
            this.selectedImageUrl = imgUrl;
            this.selectedColor = firstColor;
            this.selectedSize = 'M';
            this.qty = 1;
            this.showModal = true;
        },
        closeModal() { this.showModal = false; }
    }">

        {{-- Layout de Duas Colunas com ID e transição para o AJAX --}}
        <div id="catalog-area"
            class="grid gap-6 items-start [grid-template-columns:220px_1fr] transition-opacity duration-200">

            {{-- Sidebar --}}
            <aside class="bg-fs-bg border border-fs-border rounded-[2px] sticky top-6">

                <div class="px-[1.1rem] py-[0.9rem] border-b border-fs-border flex items-center justify-between">
                    <span class="text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-gray">Filtros</span>
                    @if ($search || $categoryId !== null)
                        <a href="{{ route('catalog') }}"
                            class="text-fs-muted text-[0.68rem] font-semibold tracking-[0.08em] uppercase no-underline transition-colors duration-150 hover:text-fs-purple">
                            Limpar
                        </a>
                    @endif
                </div>

                <div class="p-[1.1rem]">

                    {{-- Search --}}
                    <div class="mb-[1.4rem]">
                        <label
                            class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-[0.55rem]">Pesquisa</label>
                        <form method="GET" action="{{ route('catalog') }}">
                            @if ($categoryId !== null)
                                <input type="hidden" name="category" value="{{ $categoryId }}">
                            @endif
                            <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar..."
                                class="w-full bg-transparent border-0 border-b border-fs-mid px-0 py-[0.4rem] text-fs-dark text-[0.82rem] outline-none font-[inherit] transition-colors duration-200 focus:border-fs-purple">
                        </form>
                    </div>

                    <div class="h-px bg-fs-border mb-[1.4rem]"></div>

                    {{-- Categories (collapsible) --}}
                    <div x-data="{ open: false }" class="mb-[1.4rem]">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full bg-transparent border-0 p-0 cursor-pointer mb-[0.65rem]">
                            <label
                                class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted cursor-pointer m-0">Categoria</label>
                            <svg :class="open ? 'rotate-180' : ''" class="transition-transform duration-200 shrink-0"
                                width="12" height="12" viewBox="0 0 12 12" fill="none">
                                <path d="M2 4.5L6 8.5L10 4.5" stroke="#b8b4ae" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="flex flex-col gap-[0.15rem]">
                            <a href="{{ route('catalog', array_filter(['search' => $search])) }}"
                                class="flex items-center gap-2 w-full px-2 py-[0.4rem] cursor-pointer text-left rounded-[1px] no-underline transition-colors duration-100 hover:bg-black/[0.04]">
                                <span
                                    class="w-3 h-3 rounded-full shrink-0 border-[1.5px] {{ $categoryId === null ? 'border-fs-purple bg-fs-purple' : 'border-fs-mid bg-transparent' }}"></span>
                                <span
                                    class="text-[0.8rem] {{ $categoryId === null ? 'text-fs-purple font-semibold' : 'text-fs-gray font-normal' }}">Todos</span>
                            </a>

                            @foreach ($categories as $cat)
                                <a href="{{ route('catalog', array_filter(['search' => $search, 'category' => $cat->id])) }}"
                                    class="flex items-center gap-2 w-full px-2 py-[0.4rem] cursor-pointer text-left rounded-[1px] no-underline transition-colors duration-100 hover:bg-black/[0.04]">
                                    <span
                                        class="w-3 h-3 rounded-full shrink-0 border-[1.5px] {{ (int) $categoryId === $cat->id ? 'border-fs-purple bg-fs-purple' : 'border-fs-mid bg-transparent' }}"></span>
                                    <span
                                        class="text-[0.8rem] {{ (int) $categoryId === $cat->id ? 'text-fs-purple font-semibold' : 'text-fs-gray font-normal' }}">{{ $cat->name }}</span>
                                </a>
                            @endforeach

                            <a href="{{ route('catalog', array_filter(['search' => $search, 'category' => -1])) }}"
                                class="flex items-center gap-2 w-full px-2 py-[0.4rem] cursor-pointer text-left rounded-[1px] no-underline transition-colors duration-100 hover:bg-black/[0.04]">
                                <span
                                    class="w-3 h-3 rounded-full shrink-0 border-[1.5px] {{ $categoryId === '-1' ? 'border-fs-purple bg-fs-purple' : 'border-fs-mid bg-transparent' }}"></span>
                                <span
                                    class="text-[0.8rem] {{ $categoryId === '-1' ? 'text-fs-purple font-semibold' : 'text-fs-gray font-normal' }}">Sem
                                    categoria</span>
                            </a>
                        </div>
                    </div>

                    <div class="h-px bg-fs-border mb-[1.4rem]"></div>

                    {{-- Price --}}
                    <div>
                        <label
                            class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-[0.55rem]">Preço</label>
                        <div class="px-[0.9rem] py-[0.7rem] bg-fs-light border border-fs-border rounded-[1px]">
                            <div class="flex justify-between items-baseline mb-[0.3rem]">
                                <span class="text-[#aaa] text-[0.75rem]">Por unidade</span>
                                <span
                                    class="text-fs-dark font-bold text-[0.95rem]">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                            </div>
                            <div class="text-fs-muted text-[0.68rem] leading-[1.4]">
                                Desc. a partir de {{ $prices->qty_discount }} un.
                                <span class="text-fs-green block mt-[2px]">→
                                    €{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

            {{-- Main content --}}
            <div>
                @if ($images->isEmpty())
                    <div class="text-center py-[5rem] px-8 border border-fs-border rounded-[2px]">
                        <p class="text-[0.9rem] text-fs-gray m-0 mb-1">Nenhum design encontrado</p>
                        <p class="text-[0.8rem] text-[#c8c4be] m-0">Tenta uma pesquisa diferente.</p>
                    </div>
                @else
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        @foreach ($images as $image)
                            {{-- GRELHA FLEXÍVEL: Mantém os botões sempre no fundo independentemente do tamanho do texto --}}
                            <div
                                class="bg-white border border-fs-border rounded-[2px] overflow-hidden transition-colors duration-150 hover:border-fs-purple flex flex-col h-full">

                                <div
                                    class="bg-fs-bg h-[180px] shrink-0 flex items-center justify-center border-b border-fs-border overflow-hidden p-4">
                                    {{-- Utilizamos o Accessor do Model aqui --}}
                                    <img src="{{ $image->resolved_url }}" alt="{{ $image->name }}"
                                        class="max-w-full max-h-full object-contain block"
                                        onerror="this.style.opacity='.15'">
                                </div>

                                <div class="p-[0.85rem] flex flex-col flex-1">
                                    @if ($image->category)
                                        <span
                                            class="inline-block text-[0.6rem] font-bold tracking-[0.14em] uppercase text-fs-purple mb-[0.35rem]">
                                            {{ $image->category->name }}
                                        </span>
                                    @endif

                                    <h3
                                        class="text-fs-dark text-[0.85rem] font-semibold m-0 mb-[0.2rem] whitespace-nowrap overflow-hidden text-ellipsis">
                                        {{ $image->name }}
                                    </h3>

                                    @if ($image->description)
                                        <p class="text-[#aaa] text-[0.72rem] m-0 mb-[0.65rem] leading-[1.45] line-clamp-2">
                                            {{ $image->description }}
                                        </p>
                                    @endif

                                    {{-- MT-AUTO puxa isto para baixo --}}
                                    <div class="flex items-center justify-between mt-auto pt-2">
                                        <span
                                            class="text-fs-dark font-bold text-[0.9rem]">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                                        <div class="flex gap-[0.35rem]">
                                            <a href="{{ route('view3d', ['design' => $image->id]) }}" title="Provador 3D"
                                                class="bg-fs-light text-fs-gray border border-fs-border px-[0.55rem] py-[0.3rem] text-[0.72rem] no-underline inline-flex items-center rounded-[1px] transition-all duration-150 hover:border-fs-purple hover:text-fs-purple">
                                                3D
                                            </a>
                                            <button type="button"
                                                @click="openModal({{ $image->id }}, '{{ addslashes($image->name) }}', '{{ $image->resolved_url }}', '{{ $colors->first()?->code ?? '' }}')"
                                                class="bg-fs-dark text-fs-light border-0 px-3 py-[0.3rem] text-[0.68rem] font-bold tracking-[0.08em] uppercase cursor-pointer rounded-[1px] transition-colors duration-150 hover:bg-[#333]">
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
                                    class="bg-fs-bg text-[#c8c4be] border border-fs-border px-[0.9rem] py-[0.4rem] text-[0.8rem] rounded-[1px]">←</span>
                            @else
                                <a href="{{ $images->previousPageUrl() }}"
                                    class="bg-white text-fs-gray border border-fs-border px-[0.9rem] py-[0.4rem] text-[0.8rem] no-underline rounded-[1px] transition-all duration-150 hover:border-fs-dark">←</a>
                            @endif

                            @foreach ($images->getUrlRange(max(1, $images->currentPage() - 2), min($images->lastPage(), $images->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}"
                                    class="border px-3 py-[0.4rem] text-[0.8rem] no-underline rounded-[1px] transition-all duration-150
                                  {{ $images->currentPage() === $page ? 'bg-fs-dark text-fs-light border-fs-dark' : 'bg-white text-fs-gray border-fs-border hover:border-fs-dark' }}">
                                    {{ $page }}
                                </a>
                            @endforeach

                            @if ($images->hasMorePages())
                                <a href="{{ $images->nextPageUrl() }}"
                                    class="bg-white text-fs-gray border border-fs-border px-[0.9rem] py-[0.4rem] text-[0.8rem] no-underline rounded-[1px] transition-all duration-150 hover:border-fs-dark">→</a>
                            @else
                                <span
                                    class="bg-fs-bg text-[#c8c4be] border border-fs-border px-[0.9rem] py-[0.4rem] text-[0.8rem] rounded-[1px]">→</span>
                            @endif
                        </div>
                    @endif
                @endif
            </div>

        </div>

        {{-- Add to Cart Modal --}}
        <div x-show="showModal" class="fixed inset-0 bg-[rgba(26,26,26,0.4)] z-[200] flex items-center justify-center p-4"
            @click.self="closeModal()" x-cloak>
            <div class="bg-fs-light border border-fs-border rounded-[2px] w-full max-w-[460px] overflow-hidden">

                {{-- Modal header --}}
                <div class="flex items-center justify-between px-[1.35rem] py-[1.1rem] border-b border-fs-border">
                    <div>
                        <div class="text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-[0.2rem]">
                            Adicionar ao carrinho</div>
                        <h3 class="text-fs-dark text-[0.9rem] font-semibold m-0" x-text="selectedImageName"></h3>
                    </div>
                    <button @click="closeModal()"
                        class="bg-transparent border-0 text-[#aaa] cursor-pointer text-[1.2rem] leading-none transition-colors duration-150 hover:text-fs-dark">×</button>
                </div>

                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="tshirt_image_id" :value="selectedImageId">
                    <input type="hidden" name="color_code" :value="selectedColor">
                    <input type="hidden" name="size" :value="selectedSize">
                    <input type="hidden" name="qty" :value="qty">
                    <input type="hidden" name="side" value="front">

                    <div class="px-[1.35rem] py-[1.35rem] flex gap-[1.35rem]">

                        {{-- Preview Dinâmico --}}
                        <div class="shrink-0" style="width: 110px; height: 110px; position: relative; overflow: hidden;">
                            <img :src="'{{ asset('storage/tshirt_base') }}/' + selectedColor + '.jpg'"
                                onerror="this.onerror=null; this.src='{{ file_exists(public_path('storage/tshirt_base/fafafa.jpg')) ? asset('storage/tshirt_base/fafafa.jpg') : asset('storage/tshirt_base/plain_white.png') }}';"
                                alt="T-shirt de Base"
                                style="width: 100%; height: 100%; object-fit: contain; display: block;">
                            <img :src="selectedImageUrl" alt="Design Preview"
                                style="position: absolute; top: 26%; left: 50%; transform: translateX(-50%); width: 32%; height: 32%; object-fit: contain; mix-blend-mode: multiply; pointer-events: none;">
                        </div>

                        <div class="flex-1 flex flex-col gap-4">
                            {{-- Color --}}
                            <div>
                                <label
                                    class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Cor</label>
                                <div class="flex gap-[0.4rem] flex-wrap">
                                    @foreach ($colors as $color)
                                        <button type="button" @click="selectedColor = '{{ $color->code }}'"
                                            title="{{ $color->name }}"
                                            :class="selectedColor === '{{ $color->code }}' ?
                                                'ring-2 ring-fs-purple ring-offset-1' : 'ring-1 ring-black/10'"
                                            class="w-[26px] h-[26px] rounded-full cursor-pointer transition-all duration-150 border-0"
                                            style="background:#{{ $color->code }}">
                                        </button>
                                    @endforeach
                                </div>
                                <p class="text-[#aaa] text-[0.7rem] mt-[0.3rem] mb-0">
                                    @foreach ($colors as $color)
                                        <span x-show="selectedColor === '{{ $color->code }}'">{{ $color->name }}</span>
                                    @endforeach
                                </p>
                            </div>

                            {{-- Size --}}
                            <div>
                                <label
                                    class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Tamanho</label>
                                <div class="flex gap-[0.35rem]">
                                    @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                                        <button type="button" @click="selectedSize = '{{ $size }}'"
                                            :class="selectedSize === '{{ $size }}' ?
                                                'bg-fs-dark text-fs-light border-fs-dark' :
                                                'bg-transparent text-fs-gray border-[#d8d5d0]'"
                                            class="border px-[0.55rem] py-[0.28rem] text-[0.75rem] font-semibold cursor-pointer rounded-[1px] transition-all duration-150 min-w-[34px] font-[inherit]">
                                            {{ $size }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Qty --}}
                            <div>
                                <label
                                    class="block text-[0.58rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Quantidade</label>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="qty = Math.max(1, qty - 1)"
                                        class="bg-transparent border border-[#d8d5d0] w-[30px] h-[30px] text-fs-gray cursor-pointer text-[1rem] flex items-center justify-center rounded-[1px] transition-all duration-150 font-[inherit] hover:border-fs-dark">−</button>
                                    <span class="text-fs-dark font-semibold min-w-[2rem] text-center text-[0.9rem]"
                                        x-text="qty"></span>
                                    <button type="button" @click="qty = Math.min(99, qty + 1)"
                                        class="bg-transparent border border-[#d8d5d0] w-[30px] h-[30px] text-fs-gray cursor-pointer text-[1rem] flex items-center justify-center rounded-[1px] transition-all duration-150 font-[inherit] hover:border-fs-dark">+</button>
                                </div>
                            </div>

                            {{-- Resumo de Preço Total Dinâmico --}}
                            <div class="bg-fs-bg border border-fs-border px-3 py-[0.6rem] text-[0.75rem] rounded-[1px]">
                                <div class="flex justify-between text-fs-gray mb-[0.3rem]">
                                    <span>Preço Total</span>
                                    <span class="text-fs-dark font-bold text-[0.95rem]"
                                        x-text="'€' + (qty * (qty >= {{ $prices->qty_discount }} ? {{ $prices->unit_price_catalog_discount }} : {{ $prices->unit_price_catalog }})).toFixed(2)">
                                    </span>
                                </div>
                                <div x-show="qty >= {{ $prices->qty_discount }}"
                                    class="flex justify-between text-fs-green mt-1 text-[0.7rem] transition-all">
                                    <span>✓ Desconto de quantidade aplicado</span>
                                    <span>€{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                                </div>
                                <div x-show="qty < {{ $prices->qty_discount }}"
                                    class="text-[#c8c4be] mt-1 text-[0.68rem] transition-all">
                                    Desc. a partir de {{ $prices->qty_discount }} unidades
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal footer --}}
                    <div class="px-[1.35rem] py-[0.9rem] border-t border-fs-border flex gap-[0.6rem] justify-end bg-fs-bg">
                        <button type="button" @click="closeModal()"
                            class="bg-transparent text-fs-gray border border-[#d8d5d0] px-[1.1rem] py-2 text-[0.7rem] font-bold tracking-[0.1em] uppercase cursor-pointer rounded-[1px] font-[inherit] transition-all duration-150 hover:border-fs-dark hover:text-fs-dark">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-fs-dark text-fs-light border-0 px-[1.1rem] py-2 text-[0.7rem] font-bold tracking-[0.1em] uppercase cursor-pointer rounded-[1px] font-[inherit] transition-colors duration-150 hover:bg-[#333]">
                            Adicionar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>{{-- end x-data --}}

    {{-- Script AJAX para filtros e paginação sem piscar o ecrã --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const catalogArea = document.getElementById('catalog-area');
            if (!catalogArea) return;

            catalogArea.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href && !link.href.includes('view3d')) {
                    e.preventDefault();
                    fetchCatalog(link.href);
                }
            });

            catalogArea.addEventListener('submit', function(e) {
                if (e.target.tagName === 'FORM') {
                    e.preventDefault();
                    const url = new URL(e.target.action);
                    url.search = new URLSearchParams(new FormData(e.target)).toString();
                    fetchCatalog(url.toString());
                }
            });

            function fetchCatalog(url) {
                catalogArea.style.opacity = '0.4';
                catalogArea.style.pointerEvents = 'none';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('catalog-area');

                        if (newContent) {
                            catalogArea.innerHTML = newContent.innerHTML;
                            history.pushState(null, '', url);
                        }
                    })
                    .catch(error => console.error('Erro ao processar filtros:', error))
                    .finally(() => {
                        catalogArea.style.opacity = '1';
                        catalogArea.style.pointerEvents = 'auto';
                    });
            }

            window.addEventListener('popstate', () => {
                fetchCatalog(window.location.href);
            });
        });
    </script>
@endsection
