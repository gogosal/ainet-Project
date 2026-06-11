<div>
    {{-- Backdrop --}}
    @if($open)
    <div wire:click="close"
         style="position:fixed;inset:0;background:rgba(26,26,26,.35);z-index:150;"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
    </div>
    @endif

    {{-- Drawer --}}
    <div style="position:fixed;top:0;right:0;height:100vh;width:360px;max-width:100vw;background:#f5f4f1;border-left:1px solid #e0ddd8;z-index:160;display:flex;flex-direction:column;transform:{{ $open ? 'translateX(0)' : 'translateX(100%)' }};transition:transform .25s ease;">

        {{-- Header --}}
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #e0ddd8;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:.6rem;">
                <span style="font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#1a1a1a;">Carrinho</span>
                @if(count($items) > 0)
                    <span style="background:#1a1a1a;color:#f5f4f1;border-radius:999px;padding:1px 7px;font-size:.65rem;font-weight:700;">{{ count($items) }}</span>
                @endif
            </div>
            <button wire:click="close"
                    style="background:transparent;border:none;color:#aaa;cursor:pointer;font-size:1.2rem;line-height:1;padding:.25rem;transition:color .15s;"
                    onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#aaa'">×</button>
        </div>

        {{-- Items --}}
        <div style="flex:1;overflow-y:auto;padding:1rem 1.5rem;">
            @if(empty($items))
                <div style="text-align:center;padding:3.5rem 1rem;color:#b8b4ae;">
                    <svg style="width:40px;height:40px;margin:0 auto 1rem;display:block;stroke:#d8d5d0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p style="font-size:.82rem;margin:0 0 .2rem;color:#888;">Carrinho vazio</p>
                    <p style="font-size:.75rem;margin:0;color:#c8c4be;">Explora o catálogo.</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:.6rem;">
                    @foreach($items as $item)
                        <div style="background:#eeecea;border:1px solid #e0ddd8;border-radius:2px;padding:.85rem;display:flex;gap:.75rem;align-items:flex-start;">
                            <div style="flex-shrink:0;">
                                <x-tshirt-preview
                                    :colorCode="$item['color_code']"
                                    :imageUrl="$item['image'] ? $item['image']->image_url : null"
                                    size="52px" />
                            </div>

                            <div style="flex:1;min-width:0;">
                                <p style="color:#1a1a1a;font-size:.82rem;font-weight:600;margin:0 0 .2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $item['image']?->name ?? 'Design removido' }}
                                </p>
                                <p style="color:#aaa8a3;font-size:.72rem;margin:0 0 .4rem;">
                                    {{ $item['color']?->name ?? $item['color_code'] }} · {{ $item['size'] }}
                                </p>
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <span style="color:{{ $item['has_discount'] ? '#2d6a4f' : '#1a1a1a' }};font-weight:700;font-size:.82rem;">
                                        €{{ number_format($item['sub_total'], 2) }}
                                    </span>
                                    <span style="color:#b8b4ae;font-size:.7rem;">{{ $item['qty'] }}× €{{ number_format($item['unit_price'], 2) }}</span>
                                </div>
                            </div>

                            <button wire:click="remove({{ $item['index'] }})"
                                    style="background:transparent;border:none;color:#c8c4be;cursor:pointer;padding:.15rem;flex-shrink:0;font-size:.95rem;line-height:1;transition:color .15s;"
                                    onmouseover="this.style.color='#c0392b'" onmouseout="this.style.color='#c8c4be'"
                                    title="Remover">×</button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer --}}
        @if(!empty($items))
        <div style="padding:1rem 1.5rem;border-top:1px solid #e0ddd8;flex-shrink:0;background:#eeecea;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:1rem;">
                <span style="font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#aaa8a3;">Total</span>
                <span style="color:#1a1a1a;font-weight:700;font-size:1.1rem;">€{{ number_format($total, 2) }}</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:.5rem;">
                <a href="{{ route('cart') }}" wire:click="close"
                   style="display:block;text-align:center;background:transparent;color:#888;text-decoration:none;border:1px solid #d8d5d0;padding:.6rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;border-radius:1px;transition:all .15s;"
                   onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0';this.style.color='#888'">
                    Ver carrinho
                </a>
                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('checkout') }}" wire:click="close"
                           style="display:block;text-align:center;background:#1a1a1a;color:#f5f4f1;text-decoration:none;padding:.6rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;border-radius:1px;transition:background .15s;"
                           onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                            Finalizar compra →
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" wire:click="close"
                       style="display:block;text-align:center;background:#1a1a1a;color:#f5f4f1;text-decoration:none;padding:.6rem;font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;border-radius:1px;"
                       onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                        Entrar para finalizar →
                    </a>
                @endauth
            </div>
        </div>
        @endif
    </div>
</div>
