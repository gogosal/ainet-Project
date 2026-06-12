@extends('layouts.admin', ['title' => 'Imagens'])

@section('content')

<div x-data="{
    showModal: false,
    editingId: null,
    editName: '',
    editDesc: '',
    editCatId: '',
    deleteId: null
}">

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('admin.catalog') }}" class="flex items-center gap-3 mb-5 flex-wrap">
        <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar por nome…"
            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-[220px] text-[0.9rem] outline-none" />

        <select name="category" class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] text-[0.9rem] outline-none">
            <option value="">Todas as categorias</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected($categoryFilter == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-white border border-fs-border text-fs-gray rounded-[1px] px-4 py-[0.6rem] text-[0.85rem] cursor-pointer transition-colors duration-150 hover:border-fs-dark hover:text-fs-dark">
            Filtrar
        </button>

        <button type="button" @click="showModal = true; editingId = null; editName = ''; editDesc = ''; editCatId = '';"
            class="bg-fs-purple text-white border-none rounded-[1px] px-[1.1rem] py-[0.55rem] cursor-pointer text-[0.85rem] font-semibold ml-auto transition-colors duration-150 hover:bg-[#6b5f8e]">
            + Adicionar imagem
        </button>
    </form>

    {{-- Grid --}}
    @if($images->isEmpty())
        <div class="text-center text-fs-gray p-12 bg-white border border-fs-border rounded-[2px]">
            Nenhuma imagem encontrada.
        </div>
    @else
        <div class="grid gap-4 mb-6" style="grid-template-columns:repeat(auto-fill,minmax(210px,1fr));">
            @foreach($images as $img)
                @php
                $catalogImgUrl = str_contains($img->image_url, '/') ? \Illuminate\Support\Facades\Storage::url($img->image_url) : asset('storage/tshirt_images/' . $img->image_url);
                @endphp
                <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden flex flex-col">
                    <div class="h-40 bg-fs-light flex items-center justify-center overflow-hidden">
                        @if($img->image_url)
                            <img src="{{ $catalogImgUrl }}" alt="{{ $img->name }}"
                                class="max-h-40 max-w-full object-cover" />
                        @else
                            <span class="text-[#aaa] text-4xl">👕</span>
                        @endif
                    </div>
                    <div class="p-3 flex-1 flex flex-col gap-[0.3rem]">
                        <div class="text-fs-dark text-[0.9rem] font-semibold whitespace-nowrap overflow-hidden text-ellipsis">{{ $img->name }}</div>
                        <div class="text-fs-gray text-[0.75rem]">{{ $img->category?->name ?? '—' }}</div>
                        @if($img->description)
                            <div class="text-[#aaa] text-[0.75rem] whitespace-nowrap overflow-hidden text-ellipsis">{{ $img->description }}</div>
                        @endif
                        <div class="flex gap-2 mt-auto pt-2">
                            <button type="button"
                                @click="showModal = true; editingId = {{ $img->id }}; editName = '{{ addslashes($img->name) }}'; editDesc = '{{ addslashes($img->description ?? '') }}'; editCatId = '{{ $img->category_id ?? '' }}';"
                                class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-3 py-[0.4rem] cursor-pointer text-[0.8rem] flex-1 transition-colors duration-150 hover:border-fs-dark hover:text-fs-dark">
                                Editar
                            </button>
                            <button type="button"
                                @click="deleteId = {{ $img->id }}"
                                class="bg-[rgba(239,68,68,0.1)] text-[#f87171] border border-[rgba(239,68,68,0.3)] rounded-[1px] px-3 py-[0.4rem] cursor-pointer text-[0.8rem] transition-colors duration-150 hover:bg-[rgba(239,68,68,0.2)]">
                                Apagar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center gap-[0.4rem] py-4">
            @if($images->onFirstPage())
                <span class="bg-fs-bg text-[#c8c4be] border border-fs-border px-[0.9rem] py-[0.4rem] text-[0.8rem] rounded-[1px]">←</span>
            @else
                <a href="{{ $images->previousPageUrl() }}" class="bg-white text-fs-gray border border-fs-border px-[0.9rem] py-[0.4rem] text-[0.8rem] rounded-[1px] no-underline transition-all duration-150 hover:border-fs-dark hover:text-fs-dark">←</a>
            @endif

            @foreach($images->getUrlRange(max(1, $images->currentPage()-2), min($images->lastPage(), $images->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}"
                   class="px-3 py-[0.4rem] text-[0.8rem] rounded-[1px] no-underline border transition-all duration-150
                          {{ $images->currentPage() === $page ? 'bg-fs-dark text-fs-light border-fs-dark' : 'bg-white text-fs-gray border-fs-border hover:border-fs-dark hover:text-fs-dark' }}">
                    {{ $page }}
                </a>
            @endforeach

            @if($images->hasMorePages())
                <a href="{{ $images->nextPageUrl() }}" class="bg-white text-fs-gray border border-fs-border px-[0.9rem] py-[0.4rem] text-[0.8rem] rounded-[1px] no-underline transition-all duration-150 hover:border-fs-dark hover:text-fs-dark">→</a>
            @else
                <span class="bg-fs-bg text-[#c8c4be] border border-fs-border px-[0.9rem] py-[0.4rem] text-[0.8rem] rounded-[1px]">→</span>
            @endif
        </div>
    @endif

    {{-- Create / Edit Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-[rgba(0,0,0,0.7)] z-50 flex items-center justify-center p-4">
        <div class="bg-white border border-fs-border rounded-[2px] w-full max-w-[480px] p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-fs-dark text-base font-bold m-0 mb-5">
                <span x-show="editingId">Editar imagem</span>
                <span x-show="!editingId">Nova imagem de catálogo</span>
            </h2>

            {{-- CREATE form --}}
            <div x-show="!editingId">
                <form method="POST" action="{{ route('admin.catalog.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Nome *</label>
                        <input type="text" name="name" placeholder="Nome da imagem"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" required />
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Descrição</label>
                        <textarea name="description" rows="3" placeholder="Descrição opcional"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none resize-y"></textarea>
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Categoria</label>
                        <select name="category_id"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none">
                            <option value="">Sem categoria</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Imagem *</label>
                        <input type="file" name="image" accept="image/*"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.5rem] w-full text-[0.85rem] outline-none" required />
                    </div>
                    <div class="flex gap-3 mt-2">
                        <button type="submit"
                            class="bg-fs-purple text-white border-none rounded-[1px] px-[1.1rem] py-[0.55rem] cursor-pointer text-[0.85rem] font-semibold flex-1 transition-colors duration-150 hover:bg-[#6b5f8e]">
                            Guardar
                        </button>
                        <button type="button" @click="showModal = false"
                            class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-4 py-[0.55rem] cursor-pointer text-[0.85rem] transition-colors duration-150 hover:border-fs-dark hover:text-fs-dark">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>

            {{-- EDIT forms (one per image, shown by editingId) --}}
            <div x-show="editingId">
                @foreach($images as $img)
                <form x-show="editingId === {{ $img->id }}"
                      method="POST" action="{{ route('admin.catalog.update', $img->id) }}"
                      enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Nome *</label>
                        <input type="text" name="name" value="{{ $img->name }}"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" required />
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Descrição</label>
                        <textarea name="description" rows="3"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none resize-y">{{ $img->description }}</textarea>
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Categoria</label>
                        <select name="category_id"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none">
                            <option value="">Sem categoria</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected($img->category_id == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Imagem (deixe vazio para manter)</label>
                        <input type="file" name="image" accept="image/*"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.5rem] w-full text-[0.85rem] outline-none" />
                    </div>
                    <div class="flex gap-3 mt-2">
                        <button type="submit"
                            class="bg-fs-purple text-white border-none rounded-[1px] px-[1.1rem] py-[0.55rem] cursor-pointer text-[0.85rem] font-semibold flex-1 transition-colors duration-150 hover:bg-[#6b5f8e]">
                            Guardar
                        </button>
                        <button type="button" @click="showModal = false"
                            class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-4 py-[0.55rem] cursor-pointer text-[0.85rem] transition-colors duration-150 hover:border-fs-dark hover:text-fs-dark">
                            Cancelar
                        </button>
                    </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Delete Confirm Modal --}}
    <div x-show="deleteId !== null" x-cloak
         class="fixed inset-0 bg-[rgba(0,0,0,0.7)] z-50 flex items-center justify-center p-4">
        <div class="bg-white border border-fs-border rounded-[2px] w-full max-w-[380px] p-6">
            <h2 class="text-fs-dark text-base font-bold m-0 mb-3">Confirmar eliminação</h2>
            <p class="text-fs-gray text-[0.875rem] m-0 mb-5">Tem a certeza que deseja eliminar esta imagem? Esta ação é irreversível.</p>

            @foreach($images as $img)
            <form x-show="deleteId === {{ $img->id }}"
                  method="POST" action="{{ route('admin.catalog.destroy', $img->id) }}"
                  class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-[rgba(239,68,68,0.15)] text-[#f87171] border border-[rgba(239,68,68,0.3)] rounded-[1px] px-4 py-[0.5rem] cursor-pointer text-[0.85rem] font-semibold flex-1 transition-colors duration-150 hover:bg-[rgba(239,68,68,0.25)]">
                    Eliminar
                </button>
                <button type="button" @click="deleteId = null"
                    class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-4 py-[0.5rem] cursor-pointer text-[0.85rem] flex-1 transition-colors duration-150 hover:border-fs-dark hover:text-fs-dark">
                    Cancelar
                </button>
            </form>
            @endforeach
        </div>
    </div>

</div>

@endsection
