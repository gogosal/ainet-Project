<div style="display:flex;flex-direction:column;height:calc(100vh - 64px - 4rem);">
    {{-- Flash success --}}
    @if (session('cart_success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             style="position:fixed;bottom:1.5rem;right:1.5rem;background:#1a1a2e;border:1px solid #7c3aed;border-radius:8px;padding:0.75rem 1.25rem;color:#a78bfa;font-size:0.85rem;z-index:1000;box-shadow:0 8px 24px rgba(124,58,237,.2);">
            ✓ {{ session('cart_success') }}
        </div>
    @endif

    {{-- Page header --}}
    <div style="flex-shrink:0;margin-bottom:0.75rem;">
        <h1 style="color:#e2e8f0;font-size:1.6rem;font-weight:700;margin:0 0 0.15rem;">Catálogo</h1>
        <p style="color:#64748b;font-size:0.9rem;margin:0;">Escolhe o teu design e personaliza a tua t-shirt</p>
    </div>

    {{-- Two-column layout: sidebar + content --}}
    <div style="display:grid;grid-template-columns:240px 1fr;gap:1.5rem;flex:1;min-height:0;overflow:hidden;">

        {{-- ════════════════════════════ --}}
        {{-- LEFT SIDEBAR                 --}}
        {{-- ════════════════════════════ --}}
        <aside style="background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow-y:auto;overflow-x:hidden;">

            {{-- Sidebar header --}}
            <div style="padding:1rem 1.25rem;border-bottom:1px solid #1e1e30;display:flex;align-items:center;justify-content:space-between;">
                <span style="color:#e2e8f0;font-size:0.9rem;font-weight:600;display:flex;align-items:center;gap:0.4rem;">
                    <svg style="width:14px;height:14px;color:#a78bfa" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filtros
                </span>
                @if($search || $categoryId !== null)
                    <button wire:click="$set('search', ''); $set('categoryId', null)"
                            style="background:transparent;border:none;color:#64748b;font-size:0.75rem;cursor:pointer;padding:0;transition:color .15s;"
                            onmouseover="this.style.color='#a78bfa'" onmouseout="this.style.color='#64748b'">
                        Limpar
                    </button>
                @endif
            </div>

            <div style="padding:1.25rem;">

                {{-- Search --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;color:#94a3b8;font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:0.5rem;">Pesquisa</label>
                    <div style="position:relative;">
                        <svg style="position:absolute;left:0.6rem;top:50%;transform:translateY(-50%);width:13px;height:13px;color:#64748b;pointer-events:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Pesquisar..."
                               style="width:100%;background:#0d0d1a;border:1px solid #1e1e30;border-radius:7px;padding:0.55rem 0.6rem 0.55rem 2rem;color:#e2e8f0;font-size:0.83rem;outline:none;box-sizing:border-box;transition:border-color .2s;"
                               onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
                    </div>
                </div>

                {{-- Divider --}}
                <div style="height:1px;background:#1e1e30;margin-bottom:1.5rem;"></div>

                {{-- Category --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;color:#94a3b8;font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:0.6rem;">Categoria</label>
                    <div style="display:flex;flex-direction:column;gap:0.2rem;">
                        {{-- Todos --}}
                        <button wire:click="$set('categoryId', null)"
                                style="display:flex;align-items:center;gap:0.55rem;width:100%;background:{{ $categoryId === null ? 'rgba(124,58,237,.2)' : 'transparent' }};border:1px solid {{ $categoryId === null ? 'rgba(124,58,237,.4)' : 'transparent' }};border-radius:7px;padding:0.45rem 0.65rem;cursor:pointer;text-align:left;transition:all .15s;"
                                onmouseover="if({{ $categoryId === null ? 'false' : 'true' }}) { this.style.background='rgba(255,255,255,.04)'; }"
                                onmouseout="if({{ $categoryId === null ? 'false' : 'true' }}) { this.style.background='transparent'; }">
                            <span style="width:14px;height:14px;border-radius:50%;border:2px solid {{ $categoryId === null ? '#a78bfa' : '#334155' }};background:{{ $categoryId === null ? '#7c3aed' : 'transparent' }};flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                @if($categoryId === null)
                                    <span style="width:5px;height:5px;background:white;border-radius:50%;display:block;"></span>
                                @endif
                            </span>
                            <span style="color:{{ $categoryId === null ? '#a78bfa' : '#94a3b8' }};font-size:0.83rem;font-weight:{{ $categoryId === null ? '600' : '400' }};">Todos</span>
                        </button>

                        @foreach($categories as $cat)
                            <button wire:click="$set('categoryId', {{ $cat->id }})"
                                    style="display:flex;align-items:center;gap:0.55rem;width:100%;background:{{ $categoryId === $cat->id ? 'rgba(124,58,237,.2)' : 'transparent' }};border:1px solid {{ $categoryId === $cat->id ? 'rgba(124,58,237,.4)' : 'transparent' }};border-radius:7px;padding:0.45rem 0.65rem;cursor:pointer;text-align:left;transition:all .15s;"
                                    onmouseover="if({{ $categoryId === $cat->id ? 'false' : 'true' }}) { this.style.background='rgba(255,255,255,.04)'; }"
                                    onmouseout="if({{ $categoryId === $cat->id ? 'false' : 'true' }}) { this.style.background='transparent'; }">
                                <span style="width:14px;height:14px;border-radius:50%;border:2px solid {{ $categoryId === $cat->id ? '#a78bfa' : '#334155' }};background:{{ $categoryId === $cat->id ? '#7c3aed' : 'transparent' }};flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                    @if($categoryId === $cat->id)
                                        <span style="width:5px;height:5px;background:white;border-radius:50%;display:block;"></span>
                                    @endif
                                </span>
                                <span style="color:{{ $categoryId === $cat->id ? '#a78bfa' : '#94a3b8' }};font-size:0.83rem;font-weight:{{ $categoryId === $cat->id ? '600' : '400' }};">{{ $cat->name }}</span>
                            </button>
                        @endforeach

                        {{-- Sem categoria --}}
                        <button wire:click="$set('categoryId', -1)"
                                style="display:flex;align-items:center;gap:0.55rem;width:100%;background:{{ $categoryId === -1 ? 'rgba(124,58,237,.2)' : 'transparent' }};border:1px solid {{ $categoryId === -1 ? 'rgba(124,58,237,.4)' : 'transparent' }};border-radius:7px;padding:0.45rem 0.65rem;cursor:pointer;text-align:left;transition:all .15s;"
                                onmouseover="if({{ $categoryId === -1 ? 'false' : 'true' }}) { this.style.background='rgba(255,255,255,.04)'; }"
                                onmouseout="if({{ $categoryId === -1 ? 'false' : 'true' }}) { this.style.background='transparent'; }">
                            <span style="width:14px;height:14px;border-radius:50%;border:2px solid {{ $categoryId === -1 ? '#a78bfa' : '#334155' }};background:{{ $categoryId === -1 ? '#7c3aed' : 'transparent' }};flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                @if($categoryId === -1)
                                    <span style="width:5px;height:5px;background:white;border-radius:50%;display:block;"></span>
                                @endif
                            </span>
                            <span style="color:{{ $categoryId === -1 ? '#a78bfa' : '#94a3b8' }};font-size:0.83rem;font-weight:{{ $categoryId === -1 ? '600' : '400' }};">Sem categoria</span>
                        </button>
                    </div>
                </div>

                {{-- Divider --}}
                <div style="height:1px;background:#1e1e30;margin-bottom:1.5rem;"></div>

                {{-- Colors (decorative) --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;color:#94a3b8;font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:0.6rem;">Cores disponíveis</label>
                    <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                        @foreach($colors as $color)
                            <div title="{{ $color->name }}"
                                 style="width:22px;height:22px;border-radius:50%;background:{{ $color->code }};box-shadow:inset 0 0 0 1px rgba(255,255,255,.15);cursor:default;transition:transform .15s;"
                                 onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                            </div>
                        @endforeach
                    </div>
                    <p style="color:#475569;font-size:0.72rem;margin:0.5rem 0 0;">Escolhes a cor ao adicionar ao carrinho</p>
                </div>

                {{-- Divider --}}
                <div style="height:1px;background:#1e1e30;margin-bottom:1.5rem;"></div>

                {{-- Price info --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;color:#94a3b8;font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:0.6rem;">Preço</label>
                    <div style="background:#0d0d1a;border-radius:8px;padding:0.75rem;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:0.3rem;">
                            <span style="color:#64748b;font-size:0.78rem;">Preço unitário</span>
                            <span style="color:#a78bfa;font-weight:700;font-size:1rem;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                        </div>
                        <div style="color:#475569;font-size:0.72rem;line-height:1.4;">
                            Desconto a partir de {{ $prices->qty_discount }} unidades
                            <span style="color:#4ade80;display:block;margin-top:2px;">→ €{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                        </div>
                    </div>
                </div>

                {{-- Clear filters button --}}
                @if($search || $categoryId !== null)
                    <button wire:click="$set('search', ''); $set('categoryId', null)"
                            style="display:flex;align-items:center;justify-content:center;gap:0.4rem;width:100%;background:transparent;border:1px solid #1e1e30;border-radius:7px;padding:0.5rem;color:#64748b;font-size:0.82rem;cursor:pointer;transition:all .2s;"
                            onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'" onmouseout="this.style.borderColor='#1e1e30';this.style.color='#64748b'">
                        <svg style="width:12px;height:12px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpar filtros
                    </button>
                @endif

            </div>
        </aside>

        {{-- ════════════════════════════ --}}
        {{-- RIGHT CONTENT AREA           --}}
        {{-- ════════════════════════════ --}}
        <div style="overflow-y:auto;overflow-x:hidden;">

            {{-- Product grid --}}
            @if($images->isEmpty())
                <div style="text-align:center;padding:5rem 2rem;color:#64748b;background:#111120;border:1px solid #1e1e30;border-radius:12px;">
                    <div style="font-size:3rem;margin-bottom:1rem;">👕</div>
                    <p style="font-size:1rem;margin:0 0 0.25rem;">Nenhum design encontrado</p>
                    <p style="font-size:0.85rem;margin:0;">Tenta uma pesquisa diferente ou limpa os filtros.</p>
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.1rem;margin-bottom:2rem;">
                    @foreach($images as $image)
                        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;transition:all .2s;cursor:pointer;"
                             onmouseover="this.style.borderColor='#7c3aed';this.style.transform='translateY(-2px)'"
                             onmouseout="this.style.borderColor='#1e1e30';this.style.transform='translateY(0)'">

                            {{-- Image area --}}
                            <div style="background:#0d0d1a;padding:1.25rem;display:flex;align-items:center;justify-content:center;min-height:170px;">
                                <x-tshirt-preview
                                    :colorCode="'white'"
                                    :imageUrl="$image->image_url"
                                    size="140px" />
                            </div>

                            {{-- Info --}}
                            <div style="padding:0.9rem;">
                                @if($image->category)
                                    <span style="display:inline-block;background:rgba(124,58,237,.15);color:#a78bfa;border-radius:20px;padding:1px 8px;font-size:0.68rem;font-weight:500;margin-bottom:0.35rem;">
                                        {{ $image->category->name }}
                                    </span>
                                @endif
                                <h3 style="color:#e2e8f0;font-size:0.88rem;font-weight:600;margin:0 0 0.25rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $image->name }}
                                </h3>
                                @if($image->description)
                                    <p style="color:#64748b;font-size:0.75rem;margin:0 0 0.65rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.45;">
                                        {{ $image->description }}
                                    </p>
                                @endif
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.4rem;">
                                    <span style="color:#a78bfa;font-weight:700;font-size:0.95rem;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                                    <div style="display:flex;gap:0.35rem;">
                                        <a href="{{ route('try-on', ['design' => $image->id]) }}"
                                           title="Provador 3D"
                                           style="background:#1a1a2e;color:#64748b;border:1px solid #1e1e30;border-radius:6px;padding:0.35rem 0.55rem;font-size:0.78rem;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;"
                                           onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'" onmouseout="this.style.borderColor='#1e1e30';this.style.color='#64748b'">
                                            ◈
                                        </a>
                                        <button wire:click="openModal({{ $image->id }})"
                                                style="background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.35rem 0.75rem;font-size:0.78rem;font-weight:600;cursor:pointer;transition:background .2s;"
                                                onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                                            + Adicionar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($images->hasPages())
                    <div style="display:flex;justify-content:center;gap:0.5rem;padding:1rem 0;">
                        @if($images->onFirstPage())
                            <span style="background:#1a1a2e;color:#64748b;border:1px solid #1e1e30;border-radius:6px;padding:0.4rem 0.9rem;font-size:0.85rem;">←</span>
                        @else
                            <button wire:click="previousPage" style="background:#1a1a2e;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.4rem 0.9rem;font-size:0.85rem;cursor:pointer;">←</button>
                        @endif

                        @foreach($images->getUrlRange(max(1, $images->currentPage()-2), min($images->lastPage(), $images->currentPage()+2)) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})"
                                    style="background:{{ $images->currentPage() === $page ? '#7c3aed' : '#1a1a2e' }};color:{{ $images->currentPage() === $page ? 'white' : '#94a3b8' }};border:1px solid {{ $images->currentPage() === $page ? '#7c3aed' : '#1e1e30' }};border-radius:6px;padding:0.4rem 0.75rem;font-size:0.85rem;cursor:pointer;">
                                {{ $page }}
                            </button>
                        @endforeach

                        @if($images->hasMorePages())
                            <button wire:click="nextPage" style="background:#1a1a2e;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.4rem 0.9rem;font-size:0.85rem;cursor:pointer;">→</button>
                        @else
                            <span style="background:#1a1a2e;color:#64748b;border:1px solid #1e1e30;border-radius:6px;padding:0.4rem 0.9rem;font-size:0.85rem;">→</span>
                        @endif
                    </div>
                @endif
            @endif
        </div>{{-- end right col --}}

    </div>{{-- end grid --}}

    {{-- Add to cart modal --}}
    @if($showModal && $selectedImage)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:200;display:flex;align-items:center;justify-content:center;padding:1rem;"
             wire:click.self="closeModal">
            <div style="background:#111120;border:1px solid #1e1e30;border-radius:16px;width:100%;max-width:480px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.6);">

                {{-- Modal header --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid #1e1e30;">
                    <h3 style="color:#e2e8f0;font-size:1rem;font-weight:600;margin:0;">{{ $selectedImage->name }}</h3>
                    <button wire:click="closeModal" style="background:transparent;border:none;color:#64748b;cursor:pointer;font-size:1.2rem;line-height:1;">×</button>
                </div>

                {{-- Preview + form --}}
                <div style="padding:1.5rem;display:flex;gap:1.5rem;">
                    {{-- Preview --}}
                    <div style="flex-shrink:0;">
                        <x-tshirt-preview
                            :colorCode="$selectedColor ?: 'white'"
                            :imageUrl="$selectedImage->image_url"
                            size="120px" />
                    </div>

                    {{-- Form --}}
                    <div style="flex:1;display:flex;flex-direction:column;gap:1rem;">
                        {{-- Color selection --}}
                        <div>
                            <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:500;margin-bottom:0.5rem;">Cor</label>
                            <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                                @foreach($colors as $color)
                                    <button wire:click="$set('selectedColor', '{{ $color->code }}')"
                                            title="{{ $color->name }}"
                                            style="width:28px;height:28px;border-radius:50%;background:{{ $color->code }};border:2px solid {{ $selectedColor === $color->code ? '#a78bfa' : 'transparent' }};cursor:pointer;box-shadow:{{ $selectedColor === $color->code ? '0 0 0 2px #7c3aed' : 'inset 0 0 0 1px rgba(255,255,255,.15)' }};transition:all .15s;">
                                    </button>
                                @endforeach
                            </div>
                            @if($selectedColor)
                                <p style="color:#64748b;font-size:0.75rem;margin-top:0.3rem;">
                                    {{ $colors->firstWhere('code', $selectedColor)?->name }}
                                </p>
                            @endif
                        </div>

                        {{-- Size --}}
                        <div>
                            <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:500;margin-bottom:0.5rem;">Tamanho</label>
                            <div style="display:flex;gap:0.4rem;">
                                @foreach(['XS','S','M','L','XL'] as $size)
                                    <button wire:click="$set('selectedSize', '{{ $size }}')"
                                            style="background:{{ $selectedSize === $size ? '#7c3aed' : '#1a1a2e' }};color:{{ $selectedSize === $size ? 'white' : '#94a3b8' }};border:1px solid {{ $selectedSize === $size ? '#7c3aed' : '#1e1e30' }};border-radius:6px;padding:0.3rem 0.6rem;font-size:0.8rem;cursor:pointer;font-weight:500;transition:all .15s;min-width:36px;">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Qty --}}
                        <div>
                            <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:500;margin-bottom:0.5rem;">Quantidade</label>
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <button wire:click="$set('qty', max(1, $qty - 1))"
                                        style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;width:32px;height:32px;color:#94a3b8;cursor:pointer;font-size:1.1rem;display:flex;align-items:center;justify-content:center;">−</button>
                                <span style="color:#e2e8f0;font-weight:600;min-width:2rem;text-align:center;">{{ $qty }}</span>
                                <button wire:click="$set('qty', min(99, $qty + 1))"
                                        style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;width:32px;height:32px;color:#94a3b8;cursor:pointer;font-size:1.1rem;display:flex;align-items:center;justify-content:center;">+</button>
                            </div>
                        </div>

                        {{-- Price info --}}
                        <div style="background:#1a1a2e;border-radius:6px;padding:0.6rem 0.75rem;font-size:0.8rem;">
                            <div style="display:flex;justify-content:space-between;color:#64748b;">
                                <span>Preço unitário</span>
                                <span style="color:#a78bfa;font-weight:600;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                            </div>
                            @if($qty >= $prices->qty_discount)
                                <div style="display:flex;justify-content:space-between;color:#4ade80;margin-top:0.25rem;font-size:0.75rem;">
                                    <span>✓ Desconto de quantidade aplicado</span>
                                    <span>€{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                                </div>
                            @else
                                <div style="color:#64748b;margin-top:0.25rem;font-size:0.72rem;">
                                    Desconto a partir de {{ $prices->qty_discount }} unidades
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Modal footer --}}
                <div style="padding:1rem 1.5rem;border-top:1px solid #1e1e30;display:flex;gap:0.75rem;justify-content:flex-end;">
                    <button wire:click="closeModal"
                            style="background:transparent;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.55rem 1.25rem;font-size:0.875rem;cursor:pointer;">
                        Cancelar
                    </button>
                    <button wire:click="addToCart"
                            style="background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.55rem 1.25rem;font-size:0.875rem;font-weight:600;cursor:pointer;transition:background .2s;"
                            onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                        Adicionar ao carrinho
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
