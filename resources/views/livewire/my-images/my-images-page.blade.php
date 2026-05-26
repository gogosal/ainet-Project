<div style="max-width:1280px; margin:2.5rem auto; padding:0 1.5rem;">

    {{-- Header Row --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem;">
        <h1 style="color:#e2e8f0; font-size:1.75rem; font-weight:700; margin:0;">As minhas imagens</h1>
        <button wire:click="openCreate"
                style="background:#7c3aed; color:#fff; padding:0.6rem 1.25rem; border-radius:0.5rem; font-size:0.9rem; font-weight:600; border:none; cursor:pointer; display:flex; align-items:center; gap:0.4rem;"
                onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
            <span style="font-size:1.1rem; line-height:1;">+</span> Nova imagem
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div style="background:#14532d; border:1px solid #16a34a; color:#86efac; padding:0.85rem 1.25rem; border-radius:0.5rem; margin-bottom:1.5rem; font-size:0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search Bar --}}
    <div style="margin-bottom:1.75rem;">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="Pesquisar por nome..."
               style="width:100%; max-width:400px; background:#111120; border:1px solid #1e1e30; color:#e2e8f0; padding:0.6rem 1rem; border-radius:0.45rem; font-size:0.9rem; outline:none; box-sizing:border-box;"
               onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
    </div>

    {{-- Images Grid --}}
    @if ($images->count() > 0)
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:1.25rem; margin-bottom:2rem;">
            @foreach ($images as $image)
                <div style="background:#111120; border:1px solid #1e1e30; border-radius:0.75rem; overflow:hidden; display:flex; flex-direction:column;">
                    {{-- Thumbnail --}}
                    <div style="aspect-ratio:1; overflow:hidden; background:#0a0a0f;">
                        <img src="{{ route('private-image', ['path' => $image->image_url]) }}"
                             alt="{{ $image->name }}"
                             style="width:100%; height:100%; object-fit:cover;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div style="display:none; width:100%; height:100%; align-items:center; justify-content:center; color:#94a3b8; font-size:0.8rem;">
                            Sem imagem
                        </div>
                    </div>

                    {{-- Info --}}
                    <div style="padding:0.85rem; flex:1; display:flex; flex-direction:column; gap:0.4rem;">
                        <p style="color:#e2e8f0; font-size:0.9rem; font-weight:600; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $image->name }}">
                            {{ $image->name }}
                        </p>
                        @if ($image->category)
                            <p style="color:#a78bfa; font-size:0.78rem; margin:0;">{{ $image->category->name }}</p>
                        @else
                            <p style="color:#4a5568; font-size:0.78rem; margin:0;">Sem categoria</p>
                        @endif
                        @if ($image->description)
                            <p style="color:#94a3b8; font-size:0.78rem; margin:0; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                                {{ $image->description }}
                            </p>
                        @endif

                        {{-- Actions --}}
                        <div style="display:flex; gap:0.5rem; margin-top:auto; padding-top:0.5rem;">
                            <button wire:click="openEdit({{ $image->id }})"
                                    style="flex:1; background:#1e1e30; color:#a78bfa; border:1px solid #2d2d45; padding:0.4rem 0; border-radius:0.35rem; font-size:0.8rem; font-weight:500; cursor:pointer;"
                                    onmouseover="this.style.background='#2d2d45'" onmouseout="this.style.background='#1e1e30'">
                                Editar
                            </button>
                            <button wire:click="confirmDelete({{ $image->id }})"
                                    style="flex:1; background:#1e1e30; color:#f87171; border:1px solid #2d2d45; padding:0.4rem 0; border-radius:0.35rem; font-size:0.8rem; font-weight:500; cursor:pointer;"
                                    onmouseover="this.style.background='#2d1515'" onmouseout="this.style.background='#1e1e30'">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="margin-top:1rem;">
            {{ $images->links() }}
        </div>
    @else
        <div style="background:#111120; border:1px solid #1e1e30; border-radius:0.75rem; padding:3rem; text-align:center;">
            <p style="color:#94a3b8; font-size:1rem; margin:0 0 1rem;">
                @if ($search)
                    Nenhuma imagem encontrada para "{{ $search }}".
                @else
                    Ainda não tens imagens. Adiciona a tua primeira!
                @endif
            </p>
            @if (!$search)
                <button wire:click="openCreate"
                        style="background:#7c3aed; color:#fff; padding:0.6rem 1.25rem; border-radius:0.5rem; font-size:0.9rem; font-weight:600; border:none; cursor:pointer;"
                        onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                    + Nova imagem
                </button>
            @endif
        </div>
    @endif

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:100; display:flex; align-items:center; justify-content:center; padding:1rem;">
            <div style="background:#111120; border:1px solid #1e1e30; border-radius:0.75rem; padding:2rem; width:100%; max-width:520px; max-height:90vh; overflow-y:auto;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                    <h2 style="color:#e2e8f0; font-size:1.1rem; font-weight:600; margin:0;">
                        {{ $editingId ? 'Editar imagem' : 'Nova imagem' }}
                    </h2>
                    <button wire:click="$set('showModal', false)"
                            style="background:none; border:none; color:#94a3b8; font-size:1.4rem; cursor:pointer; line-height:1; padding:0;"
                            onmouseover="this.style.color='#e2e8f0'" onmouseout="this.style.color='#94a3b8'">
                        &times;
                    </button>
                </div>

                <form wire:submit="saveImage">
                    {{-- Name --}}
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block; color:#94a3b8; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Nome *</label>
                        <input type="text" wire:model="modalName"
                               style="width:100%; background:#1a1a2e; border:1px solid #1e1e30; color:#e2e8f0; padding:0.6rem 0.85rem; border-radius:0.45rem; font-size:0.9rem; outline:none; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
                        @error('modalName')
                            <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block; color:#94a3b8; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Descrição</label>
                        <textarea wire:model="modalDescription" rows="3"
                                  style="width:100%; background:#1a1a2e; border:1px solid #1e1e30; color:#e2e8f0; padding:0.6rem 0.85rem; border-radius:0.45rem; font-size:0.9rem; outline:none; resize:vertical; box-sizing:border-box;"
                                  onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'"></textarea>
                        @error('modalDescription')
                            <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block; color:#94a3b8; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Categoria</label>
                        <select wire:model="modalCategoryId"
                                style="width:100%; background:#1a1a2e; border:1px solid #1e1e30; color:#e2e8f0; padding:0.6rem 0.85rem; border-radius:0.45rem; font-size:0.9rem; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
                            <option value="">— Sem categoria —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('modalCategoryId')
                            <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div style="margin-bottom:1.75rem;">
                        <label style="display:block; color:#94a3b8; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">
                            Imagem {{ $editingId ? '(deixar em branco para manter a atual)' : '*' }}
                        </label>
                        <input type="file" wire:model="modalImage" accept="image/*"
                               style="width:100%; background:#1a1a2e; border:1px solid #1e1e30; color:#e2e8f0; padding:0.6rem 0.85rem; border-radius:0.45rem; font-size:0.9rem; outline:none; box-sizing:border-box;">
                        @error('modalImage')
                            <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                        @enderror
                        @if ($modalImage)
                            <p style="color:#94a3b8; font-size:0.78rem; margin-top:0.4rem; margin-bottom:0;">Ficheiro selecionado</p>
                        @endif
                    </div>

                    {{-- Buttons --}}
                    <div style="display:flex; gap:0.75rem;">
                        <button type="button" wire:click="$set('showModal', false)"
                                style="flex:1; background:#1e1e30; color:#94a3b8; border:1px solid #2d2d45; padding:0.65rem; border-radius:0.45rem; font-size:0.9rem; font-weight:500; cursor:pointer;"
                                onmouseover="this.style.background='#2d2d45'" onmouseout="this.style.background='#1e1e30'">
                            Cancelar
                        </button>
                        <button type="submit"
                                style="flex:1; background:#7c3aed; color:#fff; border:none; padding:0.65rem; border-radius:0.45rem; font-size:0.9rem; font-weight:600; cursor:pointer;"
                                onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                            {{ $editingId ? 'Guardar alterações' : 'Adicionar imagem' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($deleteId)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:100; display:flex; align-items:center; justify-content:center; padding:1rem;">
            <div style="background:#111120; border:1px solid #1e1e30; border-radius:0.75rem; padding:2rem; width:100%; max-width:400px;">
                <h2 style="color:#e2e8f0; font-size:1.1rem; font-weight:600; margin-bottom:0.75rem;">Confirmar eliminação</h2>
                <p style="color:#94a3b8; font-size:0.9rem; margin-bottom:1.5rem;">
                    Tens a certeza que queres eliminar esta imagem? Esta ação não pode ser desfeita.
                </p>
                <div style="display:flex; gap:0.75rem;">
                    <button wire:click="cancelDelete"
                            style="flex:1; background:#1e1e30; color:#94a3b8; border:1px solid #2d2d45; padding:0.65rem; border-radius:0.45rem; font-size:0.9rem; font-weight:500; cursor:pointer;"
                            onmouseover="this.style.background='#2d2d45'" onmouseout="this.style.background='#1e1e30'">
                        Cancelar
                    </button>
                    <button wire:click="deleteImage"
                            style="flex:1; background:#dc2626; color:#fff; border:none; padding:0.65rem; border-radius:0.45rem; font-size:0.9rem; font-weight:600; cursor:pointer;"
                            onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
