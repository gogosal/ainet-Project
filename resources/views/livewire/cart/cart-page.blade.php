<div>
    <div style="margin-bottom:2rem;">
        <h1 style="color:#e2e8f0;font-size:1.6rem;font-weight:700;margin:0 0 0.25rem;">Carrinho de compras</h1>
        <p style="color:#64748b;font-size:0.9rem;margin:0;">Revê a tua selecção antes de finalizar</p>
    </div>

    @if(empty($items))
        <div style="text-align:center;padding:5rem 2rem;background:#111120;border:1px solid #1e1e30;border-radius:16px;">
            <svg style="width:64px;height:64px;margin:0 auto 1rem;display:block;color:#1e1e30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h2 style="color:#94a3b8;font-size:1.1rem;font-weight:500;margin:0 0 0.5rem;">O teu carrinho está vazio</h2>
            <p style="color:#64748b;font-size:0.85rem;margin:0 0 1.5rem;">Explora o catálogo e escolhe os teus designs favoritos.</p>
            <a href="{{ route('catalog') }}"
               style="display:inline-block;background:#7c3aed;color:white;text-decoration:none;border-radius:8px;padding:0.65rem 1.5rem;font-size:0.9rem;font-weight:600;">
                Explorar catálogo →
            </a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

            {{-- Items list --}}
            <div>
                {{-- Clear all --}}
                <div style="display:flex;justify-content:flex-end;margin-bottom:0.75rem;">
                    <button wire:click="clear" wire:confirm="Tens a certeza que queres limpar o carrinho?"
                            style="background:transparent;color:#64748b;border:1px solid #1e1e30;border-radius:6px;padding:0.35rem 0.9rem;font-size:0.8rem;cursor:pointer;transition:all .15s;"
                            onmouseover="this.style.color='#f87171';this.style.borderColor='rgba(239,68,68,.3)'"
                            onmouseout="this.style.color='#64748b';this.style.borderColor='#1e1e30'">
                        🗑 Limpar tudo
                    </button>
                </div>

                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                    @foreach($items as $item)
                        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;">
                            {{-- Preview --}}
                            <div style="flex-shrink:0;">
                                <x-tshirt-preview
                                    :colorCode="$editColors[$item['index']] ?? $item['color_code']"
                                    :imageUrl="$item['image'] ? $item['image']->image_url : null"
                                    size="80px" />
                            </div>

                            {{-- Info + controls --}}
                            <div style="flex:1;min-width:0;">
                                <h3 style="color:#e2e8f0;font-size:0.95rem;font-weight:600;margin:0 0 0.75rem;">
                                    {{ $item['image']?->name ?? 'Design removido' }}
                                </h3>

                                <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:0.75rem;align-items:end;">
                                    {{-- Color --}}
                                    <div>
                                        <label style="display:block;color:#64748b;font-size:0.72rem;font-weight:500;margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:.04em;">Cor</label>
                                        <select wire:model="editColors.{{ $item['index'] }}" wire:change="updateItem({{ $item['index'] }})"
                                                style="width:100%;background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;padding:0.45rem 0.6rem;color:#e2e8f0;font-size:0.82rem;outline:none;">
                                            @foreach($colors as $color)
                                                <option value="{{ $color->code }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Size --}}
                                    <div>
                                        <label style="display:block;color:#64748b;font-size:0.72rem;font-weight:500;margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:.04em;">Tamanho</label>
                                        <select wire:model="editSizes.{{ $item['index'] }}" wire:change="updateItem({{ $item['index'] }})"
                                                style="width:100%;background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;padding:0.45rem 0.6rem;color:#e2e8f0;font-size:0.82rem;outline:none;">
                                            @foreach(['XS','S','M','L','XL'] as $s)
                                                <option value="{{ $s }}">{{ $s }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Qty --}}
                                    <div>
                                        <label style="display:block;color:#64748b;font-size:0.72rem;font-weight:500;margin-bottom:0.35rem;text-transform:uppercase;letter-spacing:.04em;">Qtd</label>
                                        <div style="display:flex;align-items:center;gap:0.3rem;">
                                            <button wire:click="$set('editQtys.{{ $item['index'] }}', {{ max(0, ($editQtys[$item['index']] ?? 1) - 1) }})"
                                                    wire:change="updateItem({{ $item['index'] }})"
                                                    style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:4px;width:28px;height:28px;color:#94a3b8;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">−</button>
                                            <input type="number" wire:model="editQtys.{{ $item['index'] }}" wire:change="updateItem({{ $item['index'] }})"
                                                   min="0" max="99"
                                                   style="width:40px;background:#1a1a2e;border:1px solid #1e1e30;border-radius:4px;padding:0.3rem;color:#e2e8f0;font-size:0.85rem;text-align:center;outline:none;">
                                            <button wire:click="$set('editQtys.{{ $item['index'] }}', {{ min(99, ($editQtys[$item['index']] ?? 1) + 1) }})"
                                                    wire:change="updateItem({{ $item['index'] }})"
                                                    style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:4px;width:28px;height:28px;color:#94a3b8;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Price + remove --}}
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.5rem;flex-shrink:0;">
                                <span style="color:{{ $item['has_discount'] ? '#4ade80' : '#a78bfa' }};font-weight:700;font-size:1rem;">
                                    €{{ number_format($item['sub_total'], 2) }}
                                </span>
                                <span style="color:#64748b;font-size:0.75rem;">{{ $item['qty'] }}x €{{ number_format($item['unit_price'], 2) }}</span>
                                @if($item['has_discount'])
                                    <span style="color:#4ade80;font-size:0.7rem;">✓ Desc. quantidade</span>
                                @endif
                                <button wire:click="remove({{ $item['index'] }})"
                                        style="background:transparent;border:none;color:#64748b;cursor:pointer;font-size:0.8rem;padding:0.25rem;"
                                        onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='#64748b'"
                                        title="Remover item">🗑</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order summary --}}
            <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.5rem;position:sticky;top:80px;">
                <h3 style="color:#e2e8f0;font-size:1rem;font-weight:600;margin:0 0 1.25rem;">Resumo da encomenda</h3>

                <div style="display:flex;flex-direction:column;gap:0.5rem;margin-bottom:1rem;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#64748b;font-size:0.85rem;">Artigos ({{ count($items) }})</span>
                        <span style="color:#94a3b8;font-size:0.85rem;">€{{ number_format($total, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#64748b;font-size:0.85rem;">Envio</span>
                        <span style="color:#4ade80;font-size:0.85rem;">Grátis</span>
                    </div>
                </div>

                <div style="border-top:1px solid #1e1e30;padding-top:0.75rem;margin-bottom:1.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:#e2e8f0;font-weight:600;">Total</span>
                        <span style="color:#a78bfa;font-weight:700;font-size:1.2rem;">€{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('checkout') }}"
                           style="display:block;text-align:center;background:#7c3aed;color:white;text-decoration:none;border-radius:8px;padding:0.75rem;font-size:0.9rem;font-weight:600;transition:background .2s;"
                           onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                            Finalizar compra →
                        </a>
                    @else
                        <p style="color:#64748b;font-size:0.8rem;text-align:center;margin:0 0 0.75rem;">Só clientes podem fazer encomendas.</p>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       style="display:block;text-align:center;background:#7c3aed;color:white;text-decoration:none;border-radius:8px;padding:0.75rem;font-size:0.9rem;font-weight:600;">
                        Entrar para finalizar →
                    </a>
                    <p style="color:#64748b;font-size:0.75rem;text-align:center;margin:0.5rem 0 0;">O carrinho mantém-se após o login.</p>
                @endauth

                <a href="{{ route('catalog') }}"
                   style="display:block;text-align:center;color:#64748b;text-decoration:none;font-size:0.82rem;margin-top:0.75rem;padding:0.5rem;"
                   onmouseover="this.style.color='#a78bfa'" onmouseout="this.style.color='#64748b'">
                    ← Continuar a comprar
                </a>
            </div>
        </div>
    @endif
</div>
