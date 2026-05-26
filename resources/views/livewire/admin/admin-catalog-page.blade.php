<div>
    {{-- Flash --}}
    @if(session('success'))
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#4ade80;padding:0.75rem;border-radius:6px;margin-bottom:1rem;font-size:0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;flex-wrap:wrap;">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Pesquisar por nome…"
            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:220px;box-sizing:border-box;font-size:0.9rem;outline:none;" />

        <select wire:model.live="categoryFilter"
            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;font-size:0.9rem;outline:none;">
            <option value="">Todas as categorias</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <button wire:click="openCreate"
            style="background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.55rem 1.1rem;cursor:pointer;font-size:0.85rem;font-weight:600;margin-left:auto;">
            + Adicionar imagem
        </button>
    </div>

    {{-- Grid --}}
    @if($images->isEmpty())
        <div style="text-align:center;color:#94a3b8;padding:3rem;background:#111120;border:1px solid #1e1e30;border-radius:10px;">
            Nenhuma imagem encontrada.
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:1rem;margin-bottom:1.5rem;">
            @foreach($images as $img)
                <div style="background:#111120;border:1px solid #1e1e30;border-radius:10px;overflow:hidden;display:flex;flex-direction:column;">
                    <div style="height:160px;background:#0d0d18;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                        @if($img->image_url)
                            <img src="{{ Storage::url($img->image_url) }}" alt="{{ $img->name }}"
                                style="max-height:160px;max-width:100%;object-fit:cover;" />
                        @else
                            <span style="color:#64748b;font-size:2rem;">👕</span>
                        @endif
                    </div>
                    <div style="padding:0.75rem;flex:1;display:flex;flex-direction:column;gap:0.3rem;">
                        <div style="color:#e2e8f0;font-size:0.9rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $img->name }}</div>
                        <div style="color:#94a3b8;font-size:0.75rem;">
                            {{ $img->category?->name ?? '—' }}
                        </div>
                        @if($img->description)
                            <div style="color:#64748b;font-size:0.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $img->description }}</div>
                        @endif
                        <div style="display:flex;gap:0.5rem;margin-top:auto;padding-top:0.5rem;">
                            <button wire:click="openEdit({{ $img->id }})"
                                style="background:#1a1a2e;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.4rem 0.75rem;cursor:pointer;font-size:0.8rem;flex:1;">
                                Editar
                            </button>
                            <button wire:click="confirmDelete({{ $img->id }})"
                                style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:6px;padding:0.4rem 0.75rem;cursor:pointer;font-size:0.8rem;">
                                Apagar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="color:#94a3b8;">
            {{ $images->links() }}
        </div>
    @endif

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;width:100%;max-width:480px;padding:1.5rem;max-height:90vh;overflow-y:auto;">
                <h2 style="color:#e2e8f0;font-size:1rem;font-weight:700;margin:0 0 1.25rem;">
                    {{ $editingId ? 'Editar imagem' : 'Nova imagem de catálogo' }}
                </h2>

                <div style="display:flex;flex-direction:column;gap:1rem;">
                    {{-- Name --}}
                    <div>
                        <label style="color:#94a3b8;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Nome *</label>
                        <input wire:model="modalName" type="text" placeholder="Nome da imagem"
                            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;" />
                        @error('modalName') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label style="color:#94a3b8;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Descrição</label>
                        <textarea wire:model="modalDescription" rows="3" placeholder="Descrição opcional"
                            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;resize:vertical;"></textarea>
                        @error('modalDescription') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label style="color:#94a3b8;font-size:0.8rem;display:block;margin-bottom:0.4rem;">Categoria</label>
                        <select wire:model="modalCategoryId"
                            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.6rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.9rem;outline:none;">
                            <option value="">Sem categoria</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('modalCategoryId') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                    </div>

                    {{-- Image --}}
                    <div>
                        <label style="color:#94a3b8;font-size:0.8rem;display:block;margin-bottom:0.4rem;">
                            Imagem {{ $editingId ? '(deixe vazio para manter)' : '*' }}
                        </label>
                        <input wire:model="modalImage" type="file" accept="image/*"
                            style="background:#1a1a2e;border:1px solid #1e1e30;color:#e2e8f0;border-radius:6px;padding:0.5rem 0.75rem;width:100%;box-sizing:border-box;font-size:0.85rem;outline:none;" />
                        @error('modalImage') <div style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</div> @enderror
                        @if($modalImage)
                            <div style="margin-top:0.5rem;">
                                <img src="{{ $modalImage->temporaryUrl() }}" style="max-height:80px;border-radius:4px;border:1px solid #1e1e30;" />
                            </div>
                        @endif
                    </div>
                </div>

                <div style="display:flex;gap:0.75rem;margin-top:1.5rem;">
                    <button wire:click="save" wire:loading.attr="disabled"
                        style="background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.55rem 1.1rem;cursor:pointer;font-size:0.85rem;font-weight:600;flex:1;">
                        <span wire:loading wire:target="save">A guardar…</span>
                        <span wire:loading.remove wire:target="save">Guardar</span>
                    </button>
                    <button wire:click="$set('showModal', false)"
                        style="background:#1a1a2e;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.55rem 1rem;cursor:pointer;font-size:0.85rem;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirm Modal --}}
    @if($deleteId)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;width:100%;max-width:380px;padding:1.5rem;">
                <h2 style="color:#e2e8f0;font-size:1rem;font-weight:700;margin:0 0 0.75rem;">Confirmar eliminação</h2>
                <p style="color:#94a3b8;font-size:0.875rem;margin:0 0 1.25rem;">Tem a certeza que deseja eliminar esta imagem? Esta ação é irreversível.</p>
                <div style="display:flex;gap:0.75rem;">
                    <button wire:click="deleteImage"
                        style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;font-weight:600;flex:1;">
                        Eliminar
                    </button>
                    <button wire:click="cancelDelete"
                        style="background:#1a1a2e;color:#94a3b8;border:1px solid #1e1e30;border-radius:6px;padding:0.5rem 1rem;cursor:pointer;font-size:0.85rem;flex:1;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
