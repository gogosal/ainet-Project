<div>
    <div style="margin-bottom:2rem;">
        <div style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Carrinho</div>
        <h1 style="font-size:1.6rem;font-weight:300;letter-spacing:-.02em;color:#1a1a1a;margin:0;">A tua <em style="font-style:italic;font-weight:700;">selecção.</em></h1>
    </div>

    @if(empty($items))
        <div style="text-align:center;padding:5rem 2rem;border:1px solid #e0ddd8;border-radius:2px;background:#eeecea;">
            <svg style="width:48px;height:48px;margin:0 auto 1rem;display:block;stroke:#d8d5d0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h2 style="color:#888;font-size:1rem;font-weight:400;margin:0 0 .5rem;">O teu carrinho está vazio</h2>
            <p style="color:#b8b4ae;font-size:.82rem;margin:0 0 1.5rem;">Explora o catálogo e escolhe os teus designs.</p>
            <a href="{{ route('catalog') }}"
               style="display:inline-block;background:#1a1a1a;color:#f5f4f1;text-decoration:none;padding:.65rem 1.5rem;font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;border-radius:1px;transition:background .15s;"
               onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                Ver catálogo →
            </a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;">

            {{-- Items --}}
            <div>
                <div style="display:flex;justify-content:flex-end;margin-bottom:.75rem;">
                    <button wire:click="clear" wire:confirm="Tens a certeza que queres limpar o carrinho?"
                            style="background:transparent;color:#b8b4ae;border:none;font-size:.68rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;transition:color .15s;font-family:inherit;"
                            onmouseover="this.style.color='#c0392b'" onmouseout="this.style.color='#b8b4ae'">
                        Limpar tudo
                    </button>
                </div>

                <div style="display:flex;flex-direction:column;gap:.75rem;">
                    @foreach($items as $item)
                        <div style="background:#fff;border:1px solid #e0ddd8;border-radius:2px;padding:1.1rem;display:flex;gap:1.1rem;align-items:flex-start;">
                            <div style="flex-shrink:0;">
                                <x-tshirt-preview
                                    :colorCode="$editColors[$item['index']] ?? $item['color_code']"
                                    :imageUrl="$item['image'] ? $item['image']->image_url : null"
                                    size="76px" />
                            </div>

                            <div style="flex:1;min-width:0;">
                                <h3 style="color:#1a1a1a;font-size:.9rem;font-weight:600;margin:0 0 .75rem;">
                                    {{ $item['image']?->name ?? 'Design removido' }}
                                </h3>

                                <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:.75rem;align-items:end;">
                                    <div>
                                        <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.4rem;">Cor</label>
                                        <select wire:model="editColors.{{ $item['index'] }}" wire:change="updateItem({{ $item['index'] }})"
                                                style="width:100%;background:transparent;border:none;border-bottom:1px solid #ccc9c3;padding:.35rem 0;color:#1a1a1a;font-size:.8rem;outline:none;font-family:inherit;cursor:pointer;">
                                            @foreach($colors as $color)
                                                <option value="{{ $color->code }}">{{ $color->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.4rem;">Tamanho</label>
                                        <select wire:model="editSizes.{{ $item['index'] }}" wire:change="updateItem({{ $item['index'] }})"
                                                style="width:100%;background:transparent;border:none;border-bottom:1px solid #ccc9c3;padding:.35rem 0;color:#1a1a1a;font-size:.8rem;outline:none;font-family:inherit;cursor:pointer;">
                                            @foreach(['XS','S','M','L','XL'] as $s)
                                                <option value="{{ $s }}">{{ $s }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label style="display:block;font-size:.58rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.4rem;">Qtd</label>
                                        <div style="display:flex;align-items:center;gap:.3rem;">
                                            <button wire:click="decrementQty({{ $item['index'] }})"
                                                    style="background:transparent;border:1px solid #d8d5d0;width:26px;height:26px;color:#888;cursor:pointer;font-size:.9rem;display:flex;align-items:center;justify-content:center;border-radius:1px;font-family:inherit;transition:all .15s;"
                                                    onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0'">−</button>
                                            <input type="number" wire:model="editQtys.{{ $item['index'] }}" wire:change="updateItem({{ $item['index'] }})"
                                                   min="0" max="99"
                                                   style="width:38px;background:transparent;border:none;border-bottom:1px solid #ccc9c3;padding:.3rem;color:#1a1a1a;font-size:.82rem;text-align:center;outline:none;font-family:inherit;">
                                            <button wire:click="$set('editQtys.{{ $item['index'] }}', {{ min(99, ($editQtys[$item['index']] ?? 1) + 1) }})"
                                                    wire:change="updateItem({{ $item['index'] }})"
                                                    style="background:transparent;border:1px solid #d8d5d0;width:26px;height:26px;color:#888;cursor:pointer;font-size:.9rem;display:flex;align-items:center;justify-content:center;border-radius:1px;font-family:inherit;transition:all .15s;"
                                                    onmouseover="this.style.borderColor='#1a1a1a'" onmouseout="this.style.borderColor='#d8d5d0'">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.4rem;flex-shrink:0;">
                                <span style="color:{{ $item['has_discount'] ? '#2d6a4f' : '#1a1a1a' }};font-weight:700;font-size:.95rem;">
                                    €{{ number_format($item['sub_total'], 2) }}
                                </span>
                                <span style="color:#b8b4ae;font-size:.7rem;">{{ $item['qty'] }}× €{{ number_format($item['unit_price'], 2) }}</span>
                                @if($item['has_discount'])
                                    <span style="color:#2d6a4f;font-size:.68rem;">✓ Desc.</span>
                                @endif
                                <button wire:click="remove({{ $item['index'] }})"
                                        style="background:transparent;border:none;color:#c8c4be;cursor:pointer;font-size:.72rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;padding:.2rem;font-family:inherit;transition:color .15s;"
                                        onmouseover="this.style.color='#c0392b'" onmouseout="this.style.color='#c8c4be'">
                                    Remover
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Summary --}}
            <div style="background:#eeecea;border:1px solid #e0ddd8;border-radius:2px;padding:1.35rem;position:sticky;top:72px;">
                <div style="font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:1.1rem;">Resumo</div>

                <div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:1rem;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#888;font-size:.82rem;">Artigos ({{ count($items) }})</span>
                        <span style="color:#1a1a1a;font-size:.82rem;">€{{ number_format($total, 2) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:#888;font-size:.82rem;">Envio</span>
                        <span style="color:#2d6a4f;font-size:.82rem;">Grátis</span>
                    </div>
                </div>

                <div style="border-top:1px solid #e0ddd8;padding-top:.75rem;margin-bottom:1.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:baseline;">
                        <span style="font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#888;">Total</span>
                        <span style="color:#1a1a1a;font-weight:700;font-size:1.15rem;">€{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->isClient())
                        <a href="{{ route('checkout') }}"
                           style="display:block;text-align:center;background:#1a1a1a;color:#f5f4f1;text-decoration:none;padding:.7rem;font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;border-radius:1px;transition:background .15s;margin-bottom:.6rem;"
                           onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                            Finalizar compra →
                        </a>
                    @else
                        <p style="color:#b8b4ae;font-size:.75rem;text-align:center;margin:0 0 .75rem;">Só clientes podem fazer encomendas.</p>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       style="display:block;text-align:center;background:#1a1a1a;color:#f5f4f1;text-decoration:none;padding:.7rem;font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;border-radius:1px;margin-bottom:.6rem;"
                       onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                        Entrar para finalizar →
                    </a>
                    <p style="color:#c8c4be;font-size:.7rem;text-align:center;margin:0 0 .75rem;">O carrinho mantém-se após o login.</p>
                @endauth

                <a href="{{ route('catalog') }}"
                   style="display:block;text-align:center;color:#b8b4ae;text-decoration:none;font-size:.7rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;padding:.4rem;transition:color .15s;"
                   onmouseover="this.style.color='#7c6fa0'" onmouseout="this.style.color='#b8b4ae'">
                    ← Continuar a comprar
                </a>
            </div>
        </div>
    @endif
</div>
