<div>
    <div style="margin-bottom:2rem;">
        <h1 style="color:#1a1a1a;font-size:1.6rem;font-weight:700;margin:0 0 0.25rem;">Finalizar compra</h1>
        <p style="color:#aaa;font-size:0.9rem;margin:0;">Confirma os teus dados e método de pagamento</p>
    </div>

    @if(empty($items))
        <div style="text-align:center;padding:4rem 2rem;background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;">
            <p style="color:#888;margin:0 0 1rem;">O teu carrinho está vazio.</p>
            <a href="{{ route('catalog') }}" style="background:#7c6fa0;color:white;text-decoration:none;border-radius:8px;padding:0.65rem 1.5rem;font-size:0.9rem;font-weight:600;">Ver catálogo →</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start;">

            {{-- Checkout form --}}
            <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.75rem;">
                <form wire:submit="submit">
                    {{-- Payment error --}}
                    @if($paymentError)
                        <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;padding:0.85rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.875rem;">
                            ⚠ {{ $paymentError }}
                        </div>
                    @endif

                    {{-- Billing info --}}
                    <h3 style="color:#1a1a1a;font-size:0.95rem;font-weight:600;margin:0 0 1rem;padding-bottom:0.75rem;border-bottom:1px solid #e0ddd8;">Dados de faturação</h3>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                        <div>
                            <label style="display:block;color:#888;font-size:0.78rem;font-weight:500;margin-bottom:0.4rem;">NIF *</label>
                            <input wire:model="nif" type="text" maxlength="9" placeholder="123456789"
                                   style="width:100%;background:#ffffff;border:1px solid {{ $errors->has('nif') ? '#ef4444' : '#e0ddd8' }};border-radius:6px;padding:0.6rem 0.75rem;color:#1a1a1a;font-size:0.9rem;outline:none;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='{{ $errors->has('nif') ? '#ef4444' : '#e0ddd8' }}'">
                            @error('nif') <p style="color:#ef4444;font-size:0.73rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label style="display:block;color:#888;font-size:0.78rem;font-weight:500;margin-bottom:0.4rem;">Nome</label>
                            <input type="text" value="{{ auth()->user()->name }}" readonly
                                   style="width:100%;background:#0d0d1a;border:1px solid #e0ddd8;border-radius:6px;padding:0.6rem 0.75rem;color:#aaa;font-size:0.9rem;outline:none;box-sizing:border-box;">
                        </div>
                    </div>

                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block;color:#888;font-size:0.78rem;font-weight:500;margin-bottom:0.4rem;">Morada de entrega *</label>
                        <textarea wire:model="address" rows="2" placeholder="Rua, número, código postal, cidade"
                                  style="width:100%;background:#ffffff;border:1px solid {{ $errors->has('address') ? '#ef4444' : '#e0ddd8' }};border-radius:6px;padding:0.6rem 0.75rem;color:#1a1a1a;font-size:0.9rem;outline:none;box-sizing:border-box;resize:vertical;"
                                  onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='{{ $errors->has('address') ? '#ef4444' : '#e0ddd8' }}'"></textarea>
                        @error('address') <p style="color:#ef4444;font-size:0.73rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Payment --}}
                    <h3 style="color:#1a1a1a;font-size:0.95rem;font-weight:600;margin:0 0 1rem;padding-bottom:0.75rem;border-bottom:1px solid #e0ddd8;">Método de pagamento</h3>

                    <div style="display:flex;gap:0.75rem;margin-bottom:1rem;">
                        @foreach(['Visa','PayPal','MB WAY'] as $method)
                            <button type="button" wire:click="$set('paymentType', '{{ $method }}')"
                                    style="flex:1;background:{{ $paymentType === $method ? 'rgba(124,111,160,.2)' : '#ffffff' }};color:{{ $paymentType === $method ? '#7c6fa0' : '#aaa' }};border:1px solid {{ $paymentType === $method ? '#7c6fa0' : '#e0ddd8' }};border-radius:8px;padding:0.65rem 0.5rem;font-size:0.82rem;font-weight:500;cursor:pointer;transition:all .15s;">
                                {{ $method === 'Visa' ? '💳 Visa' : ($method === 'PayPal' ? '🅿 PayPal' : '📱 MB WAY') }}
                            </button>
                        @endforeach
                    </div>

                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block;color:#888;font-size:0.78rem;font-weight:500;margin-bottom:0.4rem;">
                            @if($paymentType === 'Visa') Número do cartão (16 dígitos)
                            @elseif($paymentType === 'PayPal') Email PayPal
                            @else Número de telemóvel (9 dígitos)
                            @endif *
                        </label>
                        <input wire:model="paymentRef" type="{{ $paymentType === 'PayPal' ? 'email' : 'text' }}"
                               placeholder="{{ $paymentType === 'Visa' ? '4XXXXXXXXXXXXXXX' : ($paymentType === 'PayPal' ? 'email@paypal.com' : '9XXXXXXXX') }}"
                               style="width:100%;background:#ffffff;border:1px solid {{ $errors->has('paymentRef') ? '#ef4444' : '#e0ddd8' }};border-radius:6px;padding:0.6rem 0.75rem;color:#1a1a1a;font-size:0.9rem;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='{{ $errors->has('paymentRef') ? '#ef4444' : '#e0ddd8' }}'">
                        @error('paymentRef') <p style="color:#ef4444;font-size:0.73rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Notes --}}
                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block;color:#888;font-size:0.78rem;font-weight:500;margin-bottom:0.4rem;">Notas (opcional)</label>
                        <textarea wire:model="notes" rows="2" placeholder="Instruções especiais para a encomenda..."
                                  style="width:100%;background:#ffffff;border:1px solid #e0ddd8;border-radius:6px;padding:0.6rem 0.75rem;color:#1a1a1a;font-size:0.9rem;outline:none;box-sizing:border-box;resize:vertical;"
                                  onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'"></textarea>
                    </div>

                    <button type="submit"
                            style="width:100%;background:#7c6fa0;color:white;border:none;border-radius:8px;padding:0.85rem;font-size:0.95rem;font-weight:700;cursor:pointer;transition:background .2s;"
                            onmouseover="this.style.background='#6b5f90'" onmouseout="this.style.background='#7c6fa0'">
                        <span wire:loading.remove>🔒 Confirmar e pagar €{{ number_format($total, 2) }}</span>
                        <span wire:loading>A processar pagamento...</span>
                    </button>
                </form>
            </div>

            {{-- Order summary --}}
            <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.5rem;position:sticky;top:80px;">
                <h3 style="color:#1a1a1a;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Resumo ({{ count($items) }} artigos)</h3>
                <div style="display:flex;flex-direction:column;gap:0.6rem;margin-bottom:1rem;">
                    @foreach($items as $item)
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <x-tshirt-preview :colorCode="$item['color_code']" :imageUrl="$item['image'] ? $item['image']->image_url : null" size="40px" />
                            <div style="flex:1;min-width:0;">
                                <p style="color:#888;font-size:0.78rem;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item['image']?->name }}</p>
                                <p style="color:#aaa;font-size:0.72rem;margin:0;">{{ $item['size'] }} · {{ $item['qty'] }}x</p>
                            </div>
                            <span style="color:{{ $item['has_discount'] ? '#4ade80' : '#7c6fa0' }};font-size:0.82rem;font-weight:600;">€{{ number_format($item['sub_total'], 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div style="border-top:1px solid #e0ddd8;padding-top:0.75rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:#1a1a1a;font-weight:600;font-size:0.95rem;">Total</span>
                        <span style="color:#7c6fa0;font-weight:700;font-size:1.1rem;">€{{ number_format($total, 2) }}</span>
                    </div>
                    <p style="color:#aaa;font-size:0.72rem;margin:0.5rem 0 0;">Pagamento simulado — não são debitados valores reais.</p>
                </div>
            </div>
        </div>
    @endif
</div>
