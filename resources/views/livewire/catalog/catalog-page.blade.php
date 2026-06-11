<div>
    {{-- Flash --}}
    @if (session('cart_success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             style="position:fixed;bottom:1.5rem;right:1.5rem;background:#f0faf5;border:1px solid #b7e1cb;color:#2d6a4f;padding:.7rem 1.1rem;font-size:.8rem;z-index:1000;border-radius:1px;">
            ✓ {{ session('cart_success') }}
        </div>
    @endif

    {{-- Header --}}
    <div style="margin-bottom:1.75rem;">
        <div style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Catálogo</div>
        <h1 style="font-size:1.6rem;font-weight:300;letter-spacing:-.02em;color:#1a1a1a;margin:0;">Escolhe o teu <em style="font-style:italic;font-weight:700;">design.</em></h1>
    </div>

    {{-- Two-column layout --}}
    <div style="display:grid;grid-template-columns:220px 1fr;gap:1.5rem;align-items:start;">

        {{-- Sidebar --}}
        <aside style="background:#eeecea;border:1px solid #e0ddd8;border-radius:2px;position:sticky;top:1.5rem;">

            <div style="padding:.9rem 1.1rem;border-bottom:1px solid #e0ddd8;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#888;">Filtros</span>
                @if($search || $categoryId !== null)
                    <button wire:click="$set('search', ''); $set('categoryId', null)"
                            style="background:transparent;border:none;color:#b8b4ae;font-size:.68rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:color .15s;"
                            onmouseover="this.style.color='#7c6fa0'" onmouseout="this.style.color='#b8b4ae'">
                        Limpar
                    </button>
                @endif
            </div>

            <div style="padding:1.1rem;">

                {{-- Search --}}
                <div style="margin-bottom:1.4rem;">
                    <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.55rem;">Pesquisa</label>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Pesquisar..."
                           style="width:100%;background:transparent;border:none;border-bottom:1px solid #ccc9c3;padding:.4rem 0;color:#1a1a1a;font-size:.82rem;outline:none;font-family:inherit;transition:border-color .2s;box-sizing:border-box;"
                           onfocus="this.style.borderBottomColor='#7c6fa0'" onblur="this.style.borderBottomColor='#ccc9c3'">
                </div>

                <div style="height:1px;background:#e0ddd8;margin-bottom:1.4rem;"></div>

                {{-- Categories (collapsible) --}}
                <div x-data="{ open: false }" style="margin-bottom:1.4rem;">
                    <button @click="open = !open"
                            style="display:flex;align-items:center;justify-content:space-between;width:100%;background:transparent;border:none;padding:0;cursor:pointer;margin-bottom:.65rem;">
                        <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;cursor:pointer;margin:0;">Categoria</label>
                        <svg :style="open ? 'transform:rotate(180deg)' : ''" style="transition:transform .2s;flex-shrink:0;" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 4.5L6 8.5L10 4.5" stroke="#b8b4ae" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse style="display:flex;flex-direction:column;gap:.15rem;">
                        <button wire:click="$set('categoryId', null)"
                                style="display:flex;align-items:center;gap:.5rem;width:100%;background:transparent;border:none;padding:.4rem .5rem;cursor:pointer;text-align:left;border-radius:1px;transition:background .1s;"
                                onmouseover="this.style.background='rgba(0,0,0,.04)'" onmouseout="this.style.background='transparent'">
                            <span style="width:12px;height:12px;border-radius:50%;border:1.5px solid {{ $categoryId === null ? '#7c6fa0' : '#ccc9c3' }};background:{{ $categoryId === null ? '#7c6fa0' : 'transparent' }};flex-shrink:0;"></span>
                            <span style="font-size:.8rem;color:{{ $categoryId === null ? '#7c6fa0' : '#888' }};font-weight:{{ $categoryId === null ? '600' : '400' }};">Todos</span>
                        </button>

                        @foreach($categories as $cat)
                            <button wire:click="$set('categoryId', {{ $cat->id }})"
                                    style="display:flex;align-items:center;gap:.5rem;width:100%;background:transparent;border:none;padding:.4rem .5rem;cursor:pointer;text-align:left;border-radius:1px;transition:background .1s;"
                                    onmouseover="this.style.background='rgba(0,0,0,.04)'" onmouseout="this.style.background='transparent'">
                                <span style="width:12px;height:12px;border-radius:50%;border:1.5px solid {{ $categoryId === $cat->id ? '#7c6fa0' : '#ccc9c3' }};background:{{ $categoryId === $cat->id ? '#7c6fa0' : 'transparent' }};flex-shrink:0;"></span>
                                <span style="font-size:.8rem;color:{{ $categoryId === $cat->id ? '#7c6fa0' : '#888' }};font-weight:{{ $categoryId === $cat->id ? '600' : '400' }};">{{ $cat->name }}</span>
                            </button>
                        @endforeach

                        <button wire:click="$set('categoryId', -1)"
                                style="display:flex;align-items:center;gap:.5rem;width:100%;background:transparent;border:none;padding:.4rem .5rem;cursor:pointer;text-align:left;border-radius:1px;transition:background .1s;"
                                onmouseover="this.style.background='rgba(0,0,0,.04)'" onmouseout="this.style.background='transparent'">
                            <span style="width:12px;height:12px;border-radius:50%;border:1.5px solid {{ $categoryId === -1 ? '#7c6fa0' : '#ccc9c3' }};background:{{ $categoryId === -1 ? '#7c6fa0' : 'transparent' }};flex-shrink:0;"></span>
                            <span style="font-size:.8rem;color:{{ $categoryId === -1 ? '#7c6fa0' : '#888' }};font-weight:{{ $categoryId === -1 ? '600' : '400' }};">Sem categoria</span>
                        </button>
                    </div>
                </div>

                <div style="height:1px;background:#e0ddd8;margin-bottom:1.4rem;"></div>

                {{-- Colors (collapsible) --}}
                <div x-data="{ open: false }" style="margin-bottom:1.4rem;">
                    <button @click="open = !open"
                            style="display:flex;align-items:center;justify-content:space-between;width:100%;background:transparent;border:none;padding:0;cursor:pointer;margin-bottom:.65rem;">
                        <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;cursor:pointer;margin:0;">Cores</label>
                        <svg :style="open ? 'transform:rotate(180deg)' : ''" style="transition:transform .2s;flex-shrink:0;" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 4.5L6 8.5L10 4.5" stroke="#b8b4ae" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse>
                        <div style="display:flex;flex-wrap:wrap;gap:.35rem;margin-bottom:.5rem;">
                            @foreach($colors as $color)
                                <div title="{{ $color->name }}"
                                     style="width:20px;height:20px;border-radius:50%;background:#{{ $color->code }};border:1px solid rgba(0,0,0,.1);cursor:default;transition:transform .15s;"
                                     onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='scale(1)'">
                                </div>
                            @endforeach
                        </div>
                        <p style="color:#c8c4be;font-size:.68rem;margin:0;">Escolhes ao adicionar ao carrinho</p>
                    </div>
                </div>

                <div style="height:1px;background:#e0ddd8;margin-bottom:1.4rem;"></div>

                {{-- Price --}}
                <div>
                    <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.55rem;">Preço</label>
                    <div style="padding:.7rem .9rem;background:#f5f4f1;border:1px solid #e0ddd8;border-radius:1px;">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:.3rem;">
                            <span style="color:#aaa;font-size:.75rem;">Por unidade</span>
                            <span style="color:#1a1a1a;font-weight:700;font-size:.95rem;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                        </div>
                        <div style="color:#b8b4ae;font-size:.68rem;line-height:1.4;">
                            Desc. a partir de {{ $prices->qty_discount }} un.
                            <span style="color:#2d6a4f;display:block;margin-top:2px;">→ €{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        {{-- Content --}}
        <div>

            @if($images->isEmpty())
                <div style="text-align:center;padding:5rem 2rem;border:1px solid #e0ddd8;border-radius:2px;">
                    <p style="font-size:.9rem;color:#888;margin:0 0 .25rem;">Nenhum design encontrado</p>
                    <p style="font-size:.8rem;color:#c8c4be;margin:0;">Tenta uma pesquisa diferente.</p>
                </div>
            @else
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:2rem;">
                    @foreach($images as $image)
                        <div style="background:#fff;border:1px solid #e0ddd8;border-radius:2px;overflow:hidden;transition:border-color .15s;"
                             onmouseover="this.style.borderColor='#7c6fa0'" onmouseout="this.style.borderColor='#e0ddd8'">

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
                            <div style="background:#eeecea;height:180px;display:flex;align-items:center;justify-content:center;border-bottom:1px solid #e0ddd8;overflow:hidden;padding:1rem;">
                                <img src="{{ $thumbDesignUrl }}" alt="{{ $image->name }}"
                                     style="max-width:100%;max-height:100%;object-fit:contain;display:block;"
                                     onerror="this.style.opacity='.15'">
                            </div>

                            <div style="padding:.85rem;">
                                @if($image->category)
                                    <span style="display:inline-block;font-size:.6rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#7c6fa0;margin-bottom:.35rem;">{{ $image->category->name }}</span>
                                @endif
                                <h3 style="color:#1a1a1a;font-size:.85rem;font-weight:600;margin:0 0 .2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $image->name }}
                                </h3>
                                @if($image->description)
                                    <p style="color:#aaa;font-size:.72rem;margin:0 0 .65rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.45;">
                                        {{ $image->description }}
                                    </p>
                                @endif
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:.5rem;">
                                    <span style="color:#1a1a1a;font-weight:700;font-size:.9rem;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                                    <div style="display:flex;gap:.35rem;">
                                        <a href="{{ route('try-on', ['design' => $image->id]) }}"
                                           title="Provador 3D"
                                           style="background:#f5f4f1;color:#888;border:1px solid #e0ddd8;padding:.3rem .55rem;font-size:.72rem;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;border-radius:1px;"
                                           onmouseover="this.style.borderColor='#7c6fa0';this.style.color='#7c6fa0'" onmouseout="this.style.borderColor='#e0ddd8';this.style.color='#888'">
                                            3D
                                        </a>
                                        <button wire:click="openModal({{ $image->id }})"
                                                style="background:#1a1a1a;color:#f5f4f1;border:none;padding:.3rem .75rem;font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:background .15s;border-radius:1px;"
                                                onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
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
                    <div style="display:flex;justify-content:center;gap:.4rem;padding:1rem 0;">
                        @if($images->onFirstPage())
                            <span style="background:#eeecea;color:#c8c4be;border:1px solid #e0ddd8;padding:.4rem .9rem;font-size:.8rem;border-radius:1px;">←</span>
                        @else
                            <button wire:click="previousPage" style="background:#fff;color:#888;border:1px solid #e0ddd8;padding:.4rem .9rem;font-size:.8rem;cursor:pointer;border-radius:1px;transition:all .15s;" onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#e0ddd8'">←</button>
                        @endif

                        @foreach($images->getUrlRange(max(1, $images->currentPage()-2), min($images->lastPage(), $images->currentPage()+2)) as $page => $url)
                            <button wire:click="gotoPage({{ $page }})"
                                    style="background:{{ $images->currentPage() === $page ? '#1a1a1a' : '#fff' }};color:{{ $images->currentPage() === $page ? '#f5f4f1' : '#888' }};border:1px solid {{ $images->currentPage() === $page ? '#1a1a1a' : '#e0ddd8' }};padding:.4rem .75rem;font-size:.8rem;cursor:pointer;border-radius:1px;transition:all .15s;">
                                {{ $page }}
                            </button>
                        @endforeach

                        @if($images->hasMorePages())
                            <button wire:click="nextPage" style="background:#fff;color:#888;border:1px solid #e0ddd8;padding:.4rem .9rem;font-size:.8rem;cursor:pointer;border-radius:1px;transition:all .15s;" onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#e0ddd8'">→</button>
                        @else
                            <span style="background:#eeecea;color:#c8c4be;border:1px solid #e0ddd8;padding:.4rem .9rem;font-size:.8rem;border-radius:1px;">→</span>
                        @endif
                    </div>
                @endif
            @endif
        </div>

    </div>

    {{-- Modal --}}
    @if($showModal && $selectedImage)
        <div style="position:fixed;inset:0;background:rgba(26,26,26,.4);z-index:200;display:flex;align-items:center;justify-content:center;padding:1rem;"
             wire:click.self="closeModal">
            <div style="background:#f5f4f1;border:1px solid #e0ddd8;border-radius:2px;width:100%;max-width:460px;overflow:hidden;">

                <div style="display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.35rem;border-bottom:1px solid #e0ddd8;">
                    <div>
                        <div style="font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.2rem;">Adicionar ao carrinho</div>
                        <h3 style="color:#1a1a1a;font-size:.9rem;font-weight:600;margin:0;">{{ $selectedImage->name }}</h3>
                    </div>
                    <button wire:click="closeModal" style="background:transparent;border:none;color:#aaa;cursor:pointer;font-size:1.2rem;line-height:1;transition:color .15s;" onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#aaa'">×</button>
                </div>

                <div style="padding:1.35rem;display:flex;gap:1.35rem;">
                    <div style="flex-shrink:0;">
                        <x-tshirt-preview
                            :colorCode="$selectedColor ?: 'white'"
                            :imageUrl="$selectedImage->image_url"
                            size="110px" />
                    </div>

                    <div style="flex:1;display:flex;flex-direction:column;gap:1rem;">
                        {{-- Color --}}
                        <div>
                            <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Cor</label>
                            <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                @foreach($colors as $color)
                                    <button wire:click="$set('selectedColor', '{{ $color->code }}')"
                                            title="{{ $color->name }}"
                                            style="width:26px;height:26px;border-radius:50%;background:#{{ $color->code }};border:2px solid {{ $selectedColor === $color->code ? '#7c6fa0' : 'transparent' }};cursor:pointer;box-shadow:{{ $selectedColor === $color->code ? '0 0 0 2px #7c6fa0' : 'inset 0 0 0 1px rgba(0,0,0,.12)' }};transition:all .15s;">
                                    </button>
                                @endforeach
                            </div>
                            @if($selectedColor)
                                <p style="color:#aaa;font-size:.7rem;margin-top:.3rem;">{{ $colors->firstWhere('code', $selectedColor)?->name }}</p>
                            @endif
                        </div>

                        {{-- Size --}}
                        <div>
                            <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Tamanho</label>
                            <div style="display:flex;gap:.35rem;">
                                @foreach(['XS','S','M','L','XL'] as $size)
                                    <button wire:click="$set('selectedSize', '{{ $size }}')"
                                            style="background:{{ $selectedSize === $size ? '#1a1a1a' : 'transparent' }};color:{{ $selectedSize === $size ? '#f5f4f1' : '#888' }};border:1px solid {{ $selectedSize === $size ? '#1a1a1a' : '#d8d5d0' }};padding:.28rem .55rem;font-size:.75rem;font-weight:600;cursor:pointer;border-radius:1px;transition:all .15s;min-width:34px;font-family:inherit;">
                                        {{ $size }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Qty --}}
                        <div>
                            <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Quantidade</label>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <button wire:click="$set('qty', max(1, $qty - 1))"
                                        style="background:transparent;border:1px solid #d8d5d0;width:30px;height:30px;color:#888;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;border-radius:1px;transition:all .15s;font-family:inherit;"
                                        onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0'">−</button>
                                <span style="color:#1a1a1a;font-weight:600;min-width:2rem;text-align:center;font-size:.9rem;">{{ $qty }}</span>
                                <button wire:click="$set('qty', min(99, $qty + 1))"
                                        style="background:transparent;border:1px solid #d8d5d0;width:30px;height:30px;color:#888;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;border-radius:1px;transition:all .15s;font-family:inherit;"
                                        onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0'">+</button>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div style="background:#eeecea;border:1px solid #e0ddd8;padding:.6rem .75rem;font-size:.75rem;border-radius:1px;">
                            <div style="display:flex;justify-content:space-between;color:#888;">
                                <span>Por unidade</span>
                                <span style="color:#1a1a1a;font-weight:700;">€{{ number_format($prices->unit_price_catalog, 2) }}</span>
                            </div>
                            @if($qty >= $prices->qty_discount)
                                <div style="display:flex;justify-content:space-between;color:#2d6a4f;margin-top:.25rem;font-size:.7rem;">
                                    <span>✓ Desconto de quantidade</span>
                                    <span>€{{ number_format($prices->unit_price_catalog_discount, 2) }}/un</span>
                                </div>
                            @else
                                <div style="color:#c8c4be;margin-top:.25rem;font-size:.68rem;">
                                    Desc. a partir de {{ $prices->qty_discount }} unidades
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="padding:.9rem 1.35rem;border-top:1px solid #e0ddd8;display:flex;gap:.6rem;justify-content:flex-end;background:#eeecea;">
                    <button wire:click="closeModal"
                            style="background:transparent;color:#888;border:1px solid #d8d5d0;padding:.5rem 1.1rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;border-radius:1px;font-family:inherit;transition:all .15s;"
                            onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
                        Cancelar
                    </button>
                    <button wire:click="addToCart"
                            style="background:#1a1a1a;color:#f5f4f1;border:none;padding:.5rem 1.1rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;border-radius:1px;font-family:inherit;transition:background .15s;"
                            onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                        Adicionar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

