<div>
    {{-- Backdrop --}}
    @if($open)
    <div wire:click="close"
         style="position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:150;"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
    </div>
    @endif

    {{-- Drawer panel --}}
    <div style="position:fixed;top:0;right:0;height:100vh;width:380px;max-width:100vw;background:#111120;border-left:1px solid #1e1e30;z-index:160;display:flex;flex-direction:column;transform:{{ $open ? 'translateX(0)' : 'translateX(100%)' }};transition:transform .25s ease;box-shadow:-20px 0 60px rgba(0,0,0,.4);">

        {{-- Header --}}
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #1e1e30;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <svg style="width:18px;height:18px;color:#a78bfa" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h2 style="color:#e2e8f0;font-size:1rem;font-weight:600;margin:0;">Carrinho</h2>
                @if(count($items) > 0)
                    <span style="background:rgba(124,58,237,.2);color:#a78bfa;border-radius:20px;padding:1px 8px;font-size:0.75rem;font-weight:600;">{{ count($items) }}</span>
                @endif
            </div>
            <button wire:click="close"
                    style="background:transparent;border:none;color:#64748b;cursor:pointer;font-size:1.3rem;line-height:1;padding:0.25rem;"
                    onmouseover="this.style.color='#e2e8f0'" onmouseout="this.style.color='#64748b'">×</button>
        </div>

        {{-- Items --}}
        <div style="flex:1;overflow-y:auto;padding:1rem 1.5rem;">
            @if(empty($items))
                <div style="text-align:center;padding:3rem 1rem;color:#64748b;">
                    <svg style="width:48px;height:48px;margin:0 auto 1rem;display:block;opacity:0.4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p style="font-size:0.9rem;margin:0 0 0.25rem;">O teu carrinho está vazio</p>
                    <p style="font-size:0.8rem;margin:0;">Explora o catálogo e adiciona produtos.</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                    @foreach($items as $item)
                        <div style="background:#1a1a2e;border-radius:8px;padding:0.75rem;display:flex;gap:0.75rem;align-items:flex-start;">
                            {{-- Preview --}}
                            <div style="flex-shrink:0;">
                                <x-tshirt-preview
                                    :colorCode="$item['color_code']"
                                    :imageUrl="$item['image'] ? $item['image']->image_url : null"
                                    size="56px" />
                            </div>

                            {{-- Info --}}
                            <div style="flex:1;min-width:0;">
                                <p style="color:#e2e8f0;font-size:0.85rem;font-weight:500;margin:0 0 0.2rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $item['image']?->name ?? 'Design removido' }}
                                </p>
                                <p style="color:#64748b;font-size:0.75rem;margin:0 0 0.5rem;">
                                    {{ $item['color']?->name ?? $item['color_code'] }} · {{ $item['size'] }}
                                </p>
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <span style="color:{{ $item['has_discount'] ? '#4ade80' : '#a78bfa' }};font-weight:600;font-size:0.85rem;">
                                        €{{ number_format($item['sub_total'], 2) }}
                                        @if($item['has_discount'])
                                            <span style="color:#64748b;font-size:0.7rem;font-weight:400;">(desc.)</span>
                                        @endif
                                    </span>
                                    <span style="color:#64748b;font-size:0.75rem;">{{ $item['qty'] }}x €{{ number_format($item['unit_price'], 2) }}</span>
                                </div>
                            </div>

                            {{-- Remove --}}
                            <button wire:click="remove({{ $item['index'] }})"
                                    style="background:transparent;border:none;color:#64748b;cursor:pointer;padding:0.2rem;flex-shrink:0;font-size:0.9rem;"
                                    onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='#64748b'"
                                    title="Remover">×</button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer --}}
        @if(!empty($items))
        <div style="padding:1rem 1.5rem;border-top:1px solid #1e1e30;flex-shrink:0;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                <span style="color:#94a3b8;font-size:0.9rem;">Total</span>
                <span style="color:#e2e8f0;font-weight:700;font-size:1.1rem;">€{{ number_format($total, 2) }}</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                <a href="{{ route('cart') }}" wire:click="close"
                   style="display:block;text-align:center;background:#1a1a2e;color:#94a3b8;text-decoration:none;border:1px solid #1e1e30;border-radius:8px;padding:0.65rem;font-size:0.875rem;font-weight:500;transition:all .2s;"
                   onmouseover="this.style.borderColor='#7c3aed';this.style.color='#a78bfa'" onmouseout="this.style.borderColor='#1e1e30';this.style.color='#94a3b8'">
                    Ver carrinho
                </a>
                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('checkout') }}" wire:click="close"
                           style="display:block;text-align:center;background:#7c3aed;color:white;text-decoration:none;border-radius:8px;padding:0.65rem;font-size:0.875rem;font-weight:600;transition:background .2s;"
                           onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                            Finalizar compra →
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" wire:click="close"
                       style="display:block;text-align:center;background:#7c3aed;color:white;text-decoration:none;border-radius:8px;padding:0.65rem;font-size:0.875rem;font-weight:600;"
                       onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                        Entrar para finalizar →
                    </a>
                @endauth
            </div>
        </div>
        @endif
    </div>
</div>
