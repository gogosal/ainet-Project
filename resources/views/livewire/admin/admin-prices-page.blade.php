<div>
    {{-- Flash --}}
    @if(session('success'))
        <div style="background:rgba(34,197,94,.08);border:1px solid rgba(22,163,74,.25);color:#16a34a;padding:0.75rem;border-radius:1px;margin-bottom:1.25rem;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    <div>
        <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.75rem;">
            <h2 style="color:#1a1a1a;font-size:1rem;font-weight:700;margin:0 0 1.5rem;padding-bottom:1rem;border-bottom:1px solid #e0ddd8;">
                Configuração de preços
            </h2>

            {{-- 2-column grid --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

                {{-- Unit price catalog --}}
                <div>
                    <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Preço unitário — catálogo (€)</label>
                    <input wire:model="unitPriceCatalog" type="number" step="0.01" min="0"
                        style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;" />
                    @error('unitPriceCatalog') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                </div>

                {{-- Unit price own --}}
                <div>
                    <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Preço unitário — própria (€)</label>
                    <input wire:model="unitPriceOwn" type="number" step="0.01" min="0"
                        style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;" />
                    @error('unitPriceOwn') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                </div>

                {{-- Catalog discount price --}}
                <div>
                    <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Preço com desconto — catálogo (€)</label>
                    <input wire:model="unitPriceCatalogDiscount" type="number" step="0.01" min="0"
                        style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;" />
                    @error('unitPriceCatalogDiscount') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                </div>

                {{-- Own discount price --}}
                <div>
                    <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Preço com desconto — própria (€)</label>
                    <input wire:model="unitPriceOwnDiscount" type="number" step="0.01" min="0"
                        style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;" />
                    @error('unitPriceOwnDiscount') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                </div>
            </div>

            {{-- Qty discount (full width) --}}
            <div style="margin-bottom:1.75rem;">
                <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Quantidade mínima para desconto (unidades)</label>
                <input wire:model="qtyDiscount" type="number" step="1" min="1"
                    style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;width:100%;max-width:200px;box-sizing:border-box;font-size:0.9rem;outline:none;" />
                @error('qtyDiscount') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                <p style="color:#aaa;font-size:0.78rem;margin:0.4rem 0 0;">A partir desta quantidade, os preços com desconto são aplicados automaticamente.</p>
            </div>

            {{-- Save --}}
            <div style="border-top:1px solid #e0ddd8;padding-top:1.25rem;">
                <button wire:click="save" wire:loading.attr="disabled"
                    style="background:#7c6fa0;color:white;border:none;border-radius:1px;padding:0.6rem 1.5rem;cursor:pointer;font-size:0.9rem;font-weight:600;">
                    <span wire:loading wire:target="save">A guardar…</span>
                    <span wire:loading.remove wire:target="save">Guardar preços</span>
                </button>
            </div>
        </div>
    </div>
</div>
