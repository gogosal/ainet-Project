<div>

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;">
        <div>
            <div style="font-size:.58rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#c8c4be;margin-bottom:.3rem;">Biblioteca pessoal</div>
            <h1 style="font-size:1.5rem;font-weight:300;letter-spacing:-.03em;color:#1a1a1a;margin:0;">As minhas <em style="font-weight:700;font-style:italic;">imagens</em></h1>
        </div>
        <button wire:click="openCreate"
            style="display:inline-flex;align-items:center;gap:.45rem;background:#1a1a1a;color:#f5f4f1;border:none;padding:.5rem 1rem;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;border-radius:1px;transition:background .15s;font-family:inherit;"
            onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
            + Nova imagem
        </button>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div style="background:rgba(34,197,94,.08);border:1px solid rgba(22,163,74,.25);color:#16a34a;padding:.7rem 1rem;border-radius:1px;margin-bottom:1.25rem;font-size:.82rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <div style="margin-bottom:1.5rem;">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Pesquisar por nome…"
            style="background:transparent;border:none;border-bottom:1px solid #ccc9c3;padding:.4rem 0;color:#1a1a1a;font-size:.88rem;outline:none;font-family:inherit;transition:border-color .2s;width:280px;box-sizing:border-box;"
            onfocus="this.style.borderBottomColor='#7c6fa0'" onblur="this.style.borderBottomColor='#ccc9c3'">
    </div>

    {{-- Grid --}}
    @if ($images->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1px;background:#e0ddd8;border:1px solid #e0ddd8;margin-bottom:1.5rem;">
            @foreach ($images as $image)
                @php
                    $bare = basename($image->image_url);
                    $imgSrc = file_exists(public_path('storage/tshirt_images/' . $bare))
                        ? asset('storage/tshirt_images/' . $bare)
                        : route('private-image', $bare);
                @endphp
                <div style="background:#fff;display:flex;flex-direction:column;">
                    <div style="aspect-ratio:1;overflow:hidden;background:#f5f4f1;">
                        <img src="{{ $imgSrc }}" alt="{{ $image->name }}"
                            style="width:100%;height:100%;object-fit:cover;display:block;"
                            onerror="this.style.display='none'">
                    </div>
                    <div style="padding:.8rem;flex:1;display:flex;flex-direction:column;gap:.3rem;border-top:1px solid #e0ddd8;">
                        <p style="color:#1a1a1a;font-size:.82rem;font-weight:600;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $image->name }}</p>
                        <p style="color:#b8b4ae;font-size:.72rem;margin:0;">{{ $image->category?->name ?? 'Sem categoria' }}</p>
                        @if ($image->description)
                            <p style="color:#aaa;font-size:.72rem;margin:0;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $image->description }}</p>
                        @endif
                        <div style="display:flex;gap:.5rem;margin-top:auto;padding-top:.5rem;">
                            <button wire:click="openEdit({{ $image->id }})"
                                style="flex:1;background:#fff;color:#888;border:1px solid #e0ddd8;padding:.35rem 0;font-size:.72rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;border-radius:1px;font-family:inherit;transition:all .15s;"
                                onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'"
                                onmouseout="this.style.borderColor='#e0ddd8';this.style.color='#888'">Editar</button>
                            <button wire:click="confirmDelete({{ $image->id }})"
                                style="background:rgba(239,68,68,.08);color:#dc2626;border:1px solid rgba(239,68,68,.2);padding:.35rem .65rem;font-size:.72rem;cursor:pointer;border-radius:1px;font-family:inherit;transition:all .15s;"
                                onmouseover="this.style.background='rgba(239,68,68,.15)'"
                                onmouseout="this.style.background='rgba(239,68,68,.08)'">&times;</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="color:#888;">{{ $images->links() }}</div>
    @else
        <div style="background:#fff;border:1px solid #e0ddd8;padding:3rem;text-align:center;">
            <p style="color:#b8b4ae;font-size:.9rem;margin:0 0 1rem;">
                @if ($search) Nenhuma imagem encontrada para "{{ $search }}".
                @else Ainda não tens imagens. Adiciona a tua primeira!
                @endif
            </p>
            @if (!$search)
                <button wire:click="openCreate"
                    style="background:#1a1a1a;color:#f5f4f1;border:none;padding:.5rem 1.1rem;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;border-radius:1px;font-family:inherit;"
                    onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                    + Nova imagem
                </button>
            @endif
        </div>
    @endif

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:100;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#fff;border:1px solid #e0ddd8;border-radius:2px;padding:2rem;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <h2 style="color:#1a1a1a;font-size:1rem;font-weight:600;margin:0;">{{ $editingId ? 'Editar imagem' : 'Nova imagem' }}</h2>
                    <button wire:click="$set('showModal', false)" style="background:none;border:none;color:#aaa;font-size:1.4rem;cursor:pointer;line-height:1;padding:0;" onmouseover="this.style.color='#1a1a1a'" onmouseout="this.style.color='#aaa'">&times;</button>
                </div>
                <form wire:submit="saveImage" style="display:flex;flex-direction:column;gap:1.25rem;">
                    <div>
                        <label style="display:block;font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Nome *</label>
                        <input type="text" wire:model="modalName" style="width:100%;background:transparent;border:none;border-bottom:1px solid #ccc9c3;padding:.4rem 0;color:#1a1a1a;font-size:.9rem;outline:none;font-family:inherit;box-sizing:border-box;">
                        @error('modalName') <p style="color:#dc2626;font-size:.72rem;margin:.3rem 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Descrição</label>
                        <textarea wire:model="modalDescription" rows="3" style="width:100%;background:transparent;border:1px solid #e0ddd8;padding:.5rem .75rem;color:#1a1a1a;font-size:.88rem;outline:none;resize:vertical;font-family:inherit;box-sizing:border-box;border-radius:1px;"></textarea>
                    </div>
                    <div>
                        <label style="display:block;font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Categoria</label>
                        <select wire:model="modalCategoryId" style="width:100%;background:#fff;border:1px solid #e0ddd8;color:#1a1a1a;padding:.5rem .75rem;font-size:.88rem;outline:none;border-radius:1px;font-family:inherit;box-sizing:border-box;">
                            <option value="">— Sem categoria —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:.62rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.5rem;">Imagem {{ $editingId ? '(manter atual se vazio)' : '*' }}</label>
                        <input type="file" wire:model="modalImage" accept="image/*" style="width:100%;font-size:.85rem;color:#888;font-family:inherit;">
                        @error('modalImage') <p style="color:#dc2626;font-size:.72rem;margin:.3rem 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div style="display:flex;gap:.75rem;padding-top:.5rem;">
                        <button type="button" wire:click="$set('showModal', false)" style="flex:1;background:#fff;color:#888;border:1px solid #e0ddd8;padding:.6rem;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;border-radius:1px;font-family:inherit;">Cancelar</button>
                        <button type="submit" style="flex:1;background:#1a1a1a;color:#f5f4f1;border:none;padding:.6rem;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;border-radius:1px;font-family:inherit;"
                            onmouseover="this.style.background='#333'" onmouseout="this.style.background='#1a1a1a'">
                            {{ $editingId ? 'Guardar' : 'Adicionar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if ($deleteId)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:100;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#fff;border:1px solid #e0ddd8;border-radius:2px;padding:2rem;width:100%;max-width:380px;">
                <h2 style="color:#1a1a1a;font-size:.95rem;font-weight:600;margin:0 0 .75rem;">Eliminar imagem?</h2>
                <p style="color:#888;font-size:.85rem;margin:0 0 1.5rem;">Esta ação não pode ser desfeita.</p>
                <div style="display:flex;gap:.75rem;">
                    <button wire:click="cancelDelete" style="flex:1;background:#fff;color:#888;border:1px solid #e0ddd8;padding:.6rem;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;border-radius:1px;font-family:inherit;">Cancelar</button>
                    <button wire:click="deleteImage" style="flex:1;background:#dc2626;color:#fff;border:none;padding:.6rem;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;border-radius:1px;font-family:inherit;"
                        onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">Eliminar</button>
                </div>
            </div>
        </div>
    @endif

</div>
