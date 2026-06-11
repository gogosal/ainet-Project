<div>
    {{-- Flash --}}
    @if(session('success'))
        <div style="background:rgba(34,197,94,.08);border:1px solid rgba(22,163,74,.25);color:#16a34a;padding:0.75rem;border-radius:1px;margin-bottom:1rem;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div style="display:flex;justify-content:flex-end;margin-bottom:1.25rem;">
        <button wire:click="openCreate"
            style="background:#7c6fa0;color:white;border:none;border-radius:1px;padding:0.55rem 1.1rem;cursor:pointer;font-size:0.85rem;font-weight:600;">
            + Nova categoria
        </button>
    </div>

    {{-- Table --}}
    <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:10px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid #e0ddd8;">
                    <th style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:left;">Imagem</th>
                    <th style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:left;">Nome</th>
                    <th style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:center;">Imagens</th>
                    <th style="color:#888;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;padding:0.75rem 1rem;text-align:right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                    <tr style="border-bottom:1px solid #e0ddd8;">
                        <td style="padding:0.75rem 1rem;">
                            @if($cat->image_url)
                                @php $catImgUrl = str_contains($cat->image_url, '/') ? Storage::url($cat->image_url) : asset('storage/categories/' . $cat->image_url); @endphp
                                <img src="{{ $catImgUrl }}" alt="{{ $cat->name }}"
                                    style="width:48px;height:48px;object-fit:cover;border-radius:1px;border:1px solid #e0ddd8;" />
                            @else
                                <div style="width:48px;height:48px;background:#ffffff;border-radius:1px;border:1px solid #e0ddd8;display:flex;align-items:center;justify-content:center;color:#aaa;">🏷️</div>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;color:#1a1a1a;font-size:0.9rem;font-weight:500;">{{ $cat->name }}</td>
                        <td style="padding:0.75rem 1rem;text-align:center;">
                            <span style="background:rgba(124,111,160,.12);color:#7c6fa0;border-radius:1px;padding:0.2rem 0.6rem;font-size:0.78rem;font-weight:600;">
                                {{ $cat->tshirt_images_count }}
                            </span>
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button wire:click="openEdit({{ $cat->id }})"
                                    style="background:#ffffff;color:#888;border:1px solid #e0ddd8;border-radius:1px;padding:0.4rem 0.75rem;cursor:pointer;font-size:0.8rem;">
                                    Editar
                                </button>
                                <button wire:click="confirmDelete({{ $cat->id }})"
                                    style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:1px;padding:0.4rem 0.75rem;cursor:pointer;font-size:0.8rem;">
                                    Apagar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:#888;padding:2.5rem;font-size:0.875rem;">
                            Nenhuma categoria encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;width:100%;max-width:420px;padding:1.5rem;">
                <h2 style="color:#1a1a1a;font-size:1rem;font-weight:700;margin:0 0 1.25rem;">
                    {{ $editingId ? 'Editar categoria' : 'Nova categoria' }}
                </h2>

                <div style="display:flex;flex-direction:column;gap:1rem;">
                    {{-- Name --}}
                    <div>
                        <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Nome *</label>
                        <input wire:model="modalName" type="text" placeholder="Nome da categoria"
                            style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;" />
                        @error('modalName') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Image --}}
                    <div>
                        <label style="color:#888;font-size:0.8rem;display:block;margin-bottom:0.4rem;">
                            Imagem {{ $editingId ? '(deixe vazio para manter)' : '*' }}
                        </label>
                        <input wire:model="modalImage" type="file" accept="image/*"
                            style="background:#ffffff;border:1px solid #e0ddd8;color:#1a1a1a;border-radius:1px;padding:0.5rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.85rem;outline:none;" />
                        @error('modalImage') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                        @if($modalImage)
                            <div style="margin-top:0.5rem;">
                                <img src="{{ $modalImage->temporaryUrl() }}" style="max-height:80px;border-radius:1px;border:1px solid #e0ddd8;" />
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
    @if($deleteId)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;width:100%;max-width:380px;padding:1.5rem;">
                <h2 style="color:#1a1a1a;font-size:1rem;font-weight:700;margin:0 0 0.75rem;">Confirmar eliminação</h2>
                <p style="color:#888;font-size:0.875rem;margin:0 0 1.25rem;">Tem a certeza que deseja eliminar esta categoria? Esta ação é irreversível.</p>
                <div style="display:flex;gap:0.75rem;">
                    <button wire:click="deleteCategory"
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
