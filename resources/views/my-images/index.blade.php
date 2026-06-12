@extends('layouts.app', ['title' => 'As minhas imagens'])

@section('content')
    <div class="max-w-7xl mx-auto" x-data="{
        showModal: false,
        editingId: null,
        editName: '',
        editDesc: '',
        editCatId: '',
        formAction: '{{ route('my-images.store') }}',
        deleteId: null
    }">

        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-[0.72rem] font-semibold uppercase tracking-[0.1em] text-fs-muted m-0 mb-1">Biblioteca pessoal</p>
                <h1 class="text-[1.6rem] font-bold text-fs-dark m-0">As minhas imagens</h1>
            </div>
            <button
                @click="showModal = true; editingId = null; editName = ''; editDesc = ''; editCatId = ''; formAction = '{{ route('my-images.store') }}';"
                class="bg-fs-dark hover:opacity-80 text-fs-light px-4 py-2 text-[0.78rem] font-semibold tracking-[0.06em] uppercase rounded-[1px] transition-opacity">
                + Nova imagem
            </button>
        </div>

        {{-- Pesquisa --}}
        <div class="mb-8">
            <form method="GET" action="{{ route('my-images') }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar por nome…"
                    class="w-full sm:w-72 bg-transparent border-0 border-b border-fs-border focus:border-fs-dark focus:ring-0 py-2 text-fs-dark text-[0.9rem] transition-colors outline-none placeholder:text-fs-muted">
            </form>
        </div>

        {{-- Grelha de Imagens --}}
        @if ($images->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 mb-8">
                @foreach ($images as $image)
                    <div class="bg-white border border-fs-border rounded-[2px] flex flex-col overflow-hidden group hover:border-fs-dark transition-colors">
                        <div class="aspect-square bg-fs-bg overflow-hidden">
                            <img src="{{ $image->resolved_url }}" alt="{{ $image->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-3 flex-1 flex flex-col border-t border-fs-border">
                            <p class="text-fs-dark text-[0.85rem] font-semibold truncate mb-0.5 m-0">{{ $image->name }}</p>
                            <p class="text-fs-gray text-[0.75rem] truncate m-0">{{ $image->category?->name ?? 'Sem categoria' }}</p>
                            @if ($image->description)
                                <p class="text-fs-muted text-[0.72rem] mt-1.5 line-clamp-2 m-0">{{ $image->description }}</p>
                            @endif
                            <div class="flex gap-2 mt-auto pt-3">
                                <button
                                    @click="editingId = {{ $image->id }}; editName = '{{ addslashes($image->name) }}'; editDesc = '{{ addslashes($image->description ?? '') }}'; editCatId = '{{ $image->category_id }}'; formAction = '{{ route('my-images.update', $image->id) }}'; showModal = true;"
                                    class="flex-1 bg-white hover:bg-fs-bg text-fs-dark border border-fs-border py-1.5 text-[0.72rem] font-semibold uppercase tracking-[0.05em] rounded-[1px] transition-colors">
                                    Editar
                                </button>
                                <button @click="deleteId = {{ $image->id }}"
                                    class="bg-fs-red/10 hover:bg-fs-red/20 text-fs-red border border-fs-red/20 px-3 py-1.5 rounded-[1px] transition-colors text-[0.85rem] leading-none">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>{{ $images->links() }}</div>
        @else
            <div class="bg-white border border-fs-border rounded-[2px] p-12 text-center">
                <p class="text-fs-gray text-[0.95rem] m-0">Ainda não tens imagens.</p>
            </div>
        @endif

        {{-- Modal Criar / Editar --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 bg-fs-dark/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white border border-fs-border rounded-[2px] w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
                <div class="flex items-center justify-between p-5 border-b border-fs-border bg-fs-bg">
                    <h2 class="text-[0.95rem] font-bold text-fs-dark m-0" x-text="editingId ? 'Editar imagem' : 'Nova imagem'"></h2>
                    <button @click="showModal = false" class="text-fs-muted hover:text-fs-dark text-xl leading-none">×</button>
                </div>

                <form method="POST" :action="formAction" enctype="multipart/form-data"
                    class="p-5 space-y-4 overflow-y-auto">
                    @csrf
                    <template x-if="editingId"><input type="hidden" name="_method" value="PUT"></template>

                    <div>
                        <label class="block text-[0.72rem] font-semibold uppercase tracking-[0.06em] text-fs-gray mb-1.5">Nome *</label>
                        <input type="text" name="name" x-model="editName"
                            class="w-full bg-white border border-fs-border rounded-[1px] px-3 py-2 text-[0.9rem] text-fs-dark outline-none focus:border-fs-dark">
                    </div>
                    <div>
                        <label class="block text-[0.72rem] font-semibold uppercase tracking-[0.06em] text-fs-gray mb-1.5">Descrição</label>
                        <textarea name="description" x-model="editDesc" rows="3"
                            class="w-full bg-white border border-fs-border rounded-[1px] px-3 py-2 text-[0.9rem] text-fs-dark outline-none focus:border-fs-dark"></textarea>
                    </div>
                    <div>
                        <label class="block text-[0.72rem] font-semibold uppercase tracking-[0.06em] text-fs-gray mb-1.5">Categoria</label>
                        <select name="category_id" x-model="editCatId"
                            class="w-full bg-white border border-fs-border rounded-[1px] px-3 py-2 text-[0.9rem] text-fs-dark outline-none focus:border-fs-dark">
                            <option value="">— Sem categoria —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[0.72rem] font-semibold uppercase tracking-[0.06em] text-fs-gray mb-1.5">Imagem</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-[0.85rem] text-fs-dark">
                        @error('image')
                            <p class="text-fs-red text-[0.75rem] mt-1 m-0">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex gap-3 pt-3 border-t border-fs-border">
                        <button type="button" @click="showModal = false"
                            class="flex-1 bg-white hover:bg-fs-bg border border-fs-border py-2 text-[0.78rem] font-semibold uppercase tracking-[0.05em] rounded-[1px] text-fs-dark transition-colors">Cancelar</button>
                        <button type="submit"
                            class="flex-1 bg-fs-dark hover:opacity-80 text-fs-light py-2 text-[0.78rem] font-semibold uppercase tracking-[0.05em] rounded-[1px] transition-opacity">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Eliminar --}}
        @foreach ($images as $image)
            <div x-show="deleteId === {{ $image->id }}" x-cloak
                class="fixed inset-0 bg-fs-dark/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white border border-fs-border rounded-[2px] w-full max-w-sm p-6 text-center">
                    <h2 class="text-[1rem] font-bold text-fs-dark mb-2 m-0">Eliminar imagem?</h2>
                    <p class="text-fs-gray text-[0.85rem] mb-5">Esta ação não pode ser desfeita.</p>
                    <div class="flex gap-3">
                        <button @click="deleteId = null"
                            class="flex-1 py-2 bg-fs-bg border border-fs-border rounded-[1px] font-semibold text-[0.78rem] uppercase text-fs-dark transition-colors hover:border-fs-dark">Cancelar</button>
                        <form action="{{ route('my-images.destroy', $image->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-2 bg-fs-red hover:opacity-80 text-white rounded-[1px] font-semibold text-[0.78rem] uppercase transition-opacity">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
