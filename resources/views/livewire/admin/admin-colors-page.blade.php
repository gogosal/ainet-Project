<div>
    {{-- Flash --}}
    @if (session('success'))
        <div
            style="background:rgba(34,197,94,.08);border:1px solid rgba(22,163,74,.25);color:#16a34a;padding:0.75rem;border-radius:1px;margin-bottom:1rem;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem;">
        <button wire:click="openCreate"
            style="background:#7c6fa0;color:white;border:none;border-radius:1px;padding:0.55rem 1.1rem;cursor:pointer;font-size:0.85rem;font-weight:600;">
            + Nova cor
        </button>
    </div>

    {{-- Table --}}
    <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:10px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid #e0ddd8;">
                    <th
                        style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:left;">
                        Cor</th>
                    <th
                        style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:left;">
                        Nome</th>
                    <th
                        style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:left;">
                        Código</th>
                    <th
                        style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:left;">
                        Imagem base</th>
                    <th
                        style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:right;">
                        Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colors as $color)
                    @php
                        $baseCode = ltrim($color->code, '#');
                        $baseFile = null;
                        foreach (['.jpg', '.jpeg', '.png'] as $ext) {
                            if (file_exists(public_path('storage/tshirt_base/' . $baseCode . $ext))) {
                                $baseFile = $baseCode . $ext;
                                break;
                            }
                        }
                    @endphp
                    <tr style="border-bottom:1px solid #e0ddd8;">
                        <td style="padding:0.75rem 1rem;">
                            <div style="width:40px;height:40px;border-radius:1px;background:#{{ $baseCode }};border:1px solid rgba(0,0,0,.12);box-shadow:inset 0 0 0 1px rgba(255,255,255,.2);">
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem;color:#1a1a1a;font-size:0.9rem;font-weight:500;">
                            {{ $color->name }}</td>
                        <td style="padding:0.75rem 1rem;">
                            <span style="background:#ffffff;border:1px solid #e0ddd8;color:#7c6fa0;border-radius:1px;padding:0.2rem 0.5rem;font-size:0.78rem;font-family:monospace;">{{ $color->code }}</span>
                        </td>
                        <td style="padding:0.75rem 1rem;">
                            @if ($baseFile)
                                <img src="/storage/tshirt_base/{{ $baseFile }}" alt="{{ $color->name }}"
                                    style="height:44px;width:auto;border-radius:1px;border:1px solid #e0ddd8;" />
                            @else
                                <span style="color:#aaa;font-size:0.78rem;">Sem imagem</span>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button wire:click="openEdit('{{ $color->code }}')"
                                    style="background:#ffffff;color:#888;border:1px solid #e0ddd8;border-radius:1px;padding:0.4rem 0.75rem;cursor:pointer;font-size:0.8rem;">
                                    Editar
                                </button>
                                <button wire:click="confirmDelete('{{ $color->code }}')"
                                    style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:1px;padding:0.4rem 0.75rem;cursor:pointer;font-size:0.8rem;">
                                    Apagar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#888;padding:2.5rem;font-size:0.875rem;">
                            Nenhuma cor encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div
            style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div
                style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;width:100%;max-width:420px;padding:1.5rem;">
                <h2 style="color:#1a1a1a;font-size:1rem;font-weight:700;margin:0 0 1.25rem;">
                    {{ $editingCode ? 'Editar cor' : 'Nova cor' }}
                </h2>

                <div style="display:flex;flex-direction:column;gap:1rem;">
                    {{-- Code (only on create) --}}
                    @if (!$editingCode)
                        <div>
                            <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Código hex
                                *</label>
                            <div style="display:flex;gap:0.5rem;align-items:center;">
                                <input wire:model.live="modalCode" type="color"
                                    style="width:44px;height:38px;padding:2px;background:#ffffff;border:1px solid #e0ddd8;border-radius:1px;cursor:pointer;" />
                                <input wire:model.live="modalCode" type="text" placeholder="#rrggbb"
                                    style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;flex:1;box-sizing:border-box;font-size:0.9rem;outline:none;font-family:monospace;" />
                            </div>
                            @error('modalCode')
                                <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div>
                            <label
                                style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Código</label>
                            <div style="display:flex;gap:0.5rem;align-items:center;">
                                <div
                                    style="width:36px;height:36px;border-radius:1px;background:{{ $modalCode }};border:2px solid rgba(255,255,255,.1);flex-shrink:0;">
                                </div>
                                <span
                                    style="background:#ffffff;border:1px solid #e0ddd8;color:#888;border-radius:1px;padding:0.6rem 0.75rem;font-family:monospace;font-size:0.9rem;">{{ $modalCode }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Name --}}
                    <div>
                        <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Nome *</label>
                        <input wire:model="modalName" type="text" placeholder="Ex: Branco"
                            style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;" />
                        @error('modalName')
                            <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Base image --}}
                    <div>
                        <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Imagem base da
                            t-shirt (opcional)</label>
                        <input wire:model="modalImage" type="file" accept="image/*"
                            style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.5rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.85rem;outline:none;" />
                        @error('modalImage')
                            <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div>
                        @enderror
                        @if ($modalImage)
                            <div style="margin-top:0.5rem;">
                                <img src="{{ $modalImage->temporaryUrl() }}"
                                    style="max-height:80px;border-radius:1px;border:1px solid #e0ddd8;" />
                            </div>
                        @endif
                    </div>
                </div>

                <div style="display:flex;gap:0.75rem;margin-top:1.5rem;">
                    <button wire:click="save" wire:loading.attr="disabled"
                        style="background:#7c6fa0;color:white;border:none;border-radius:1px;padding:0.55rem 1.1rem;cursor:pointer;font-size:0.85rem;font-weight:600;flex:1;">
                        <span wire:loading wire:target="save">A guardar…</span>
                        <span wire:loading.remove wire:target="save">Guardar</span>
                    </button>
                    <button wire:click="$set('showModal', false)"
                        style="background:#ffffff;color:#888;border:1px solid #e0ddd8;border-radius:1px;padding:0.55rem 1rem;cursor:pointer;font-size:0.85rem;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirm Modal --}}
    @if ($deleteCode)
        <div
            style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div
                style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;width:100%;max-width:380px;padding:1.5rem;">
                <h2 style="color:#1a1a1a;font-size:1rem;font-weight:700;margin:0 0 0.75rem;">Confirmar eliminação</h2>
                <p style="color:#888;font-size:0.875rem;margin:0 0 1.25rem;">Tem a certeza que deseja eliminar a cor
                    <strong style="color:#1a1a1a;">{{ $deleteCode }}</strong>? Esta ação é irreversível.</p>
                <div style="display:flex;gap:0.75rem;">
                    <button wire:click="deleteColor"
                        style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:1px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;flex:1;">
                        Eliminar
                    </button>
                    <button wire:click="cancelDelete"
                        style="background:#ffffff;color:#888;border:1px solid #e0ddd8;border-radius:1px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;flex:1;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
