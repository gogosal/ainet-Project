@extends('layouts.app', ['title' => 'As minhas imagens'])

@section('content')
<div
    x-data="{
        showModal: false,
        editingId: null,
        editName: '',
        editDesc: '',
        editCatId: '',
        deleteId: null,
        formAction: '{{ route('my-images.store') }}'
    }"
>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-[1.75rem]">
        <div>
            <div class="text-[0.58rem] font-bold tracking-[0.18em] uppercase text-fs-muted mb-[0.3rem]">Biblioteca pessoal</div>
            <h1 class="text-[1.5rem] font-light tracking-[-0.03em] text-fs-dark m-0">
                As minhas <em class="font-bold italic">imagens</em>
            </h1>
        </div>
        <button
            @click="showModal = true; editingId = null; editName = ''; editDesc = ''; editCatId = ''; formAction = '{{ route('my-images.store') }}';"
            class="inline-flex items-center gap-[0.45rem] bg-fs-dark text-fs-light border-0 px-4 py-2 text-[0.68rem] font-bold tracking-[0.1em] uppercase cursor-pointer rounded-[1px] transition-colors duration-150 hover:bg-[#333]"
        >
            + Nova imagem
        </button>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="bg-green-500/[0.08] border border-green-600/25 text-green-600 px-4 py-[0.7rem] rounded-[1px] mb-[1.25rem] text-[0.82rem]">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="mb-6">
        <form method="GET" action="{{ route('my-images') }}">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Pesquisar por nome…"
                class="bg-transparent border-0 border-b border-fs-mid px-0 py-[0.4rem] text-fs-dark text-[0.88rem] outline-none font-[inherit] w-[280px] box-border transition-colors duration-200 focus:border-b-fs-purple"
            >
        </form>
    </div>

    {{-- Grid --}}
    @if ($images->count() > 0)
        <div class="grid [grid-template-columns:repeat(auto-fill,minmax(200px,1fr))] gap-px bg-fs-border border border-fs-border mb-6">
            @foreach ($images as $image)
                @php
                    $bare = basename($image->image_url);
                    $imgSrc = file_exists(public_path('storage/tshirt_images/' . $bare))
                        ? asset('storage/tshirt_images/' . $bare)
                        : route('private-image', $bare);
                @endphp
                <div class="bg-white flex flex-col">
                    <div class="aspect-square overflow-hidden bg-fs-light">
                        <img
                            src="{{ $imgSrc }}"
                            alt="{{ $image->name }}"
                            class="w-full h-full object-cover block"
                            onerror="this.style.display='none'"
                        >
                    </div>
                    <div class="p-[0.8rem] flex-1 flex flex-col gap-[0.3rem] border-t border-fs-border">
                        <p class="text-fs-dark text-[0.82rem] font-semibold m-0 whitespace-nowrap overflow-hidden text-ellipsis">{{ $image->name }}</p>
                        <p class="text-fs-muted text-[0.72rem] m-0">{{ $image->category?->name ?? 'Sem categoria' }}</p>
                        @if ($image->description)
                            <p class="text-[#aaa] text-[0.72rem] m-0 overflow-hidden [display:-webkit-box] [-webkit-line-clamp:2] [-webkit-box-orient:vertical]">{{ $image->description }}</p>
                        @endif
                        <div class="flex gap-2 mt-auto pt-2">
                            <button
                                @click="
                                    editingId = {{ $image->id }};
                                    editName = @js($image->name);
                                    editDesc = @js($image->description ?? '');
                                    editCatId = @js((string)($image->category_id ?? ''));
                                    formAction = '{{ route('my-images.update', $image->id) }}';
                                    showModal = true;
                                "
                                class="flex-1 bg-white text-fs-gray border border-fs-border py-[0.35rem] text-[0.72rem] font-semibold tracking-[0.06em] uppercase cursor-pointer rounded-[1px] font-[inherit] transition-all duration-150 hover:border-fs-dark hover:text-fs-dark"
                            >Editar</button>
                            <button
                                @click="deleteId = {{ $image->id }}"
                                class="bg-red-500/[0.08] text-red-600 border border-red-500/20 py-[0.35rem] px-[0.65rem] text-[0.72rem] cursor-pointer rounded-[1px] font-[inherit] transition-all duration-150 hover:bg-red-500/[0.15]"
                            >&times;</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-fs-gray">{{ $images->links() }}</div>
    @else
        <div class="bg-white border border-fs-border p-12 text-center">
            <p class="text-fs-muted text-[0.9rem] m-0 mb-4">
                @if ($search) Nenhuma imagem encontrada para "{{ $search }}".
                @else Ainda não tens imagens. Adiciona a tua primeira!
                @endif
            </p>
            @if (!$search)
                <button
                    @click="showModal = true; editingId = null; editName = ''; editDesc = ''; editCatId = ''; formAction = '{{ route('my-images.store') }}';"
                    class="bg-fs-dark text-fs-light border-0 py-2 px-[1.1rem] text-[0.72rem] font-bold tracking-[0.1em] uppercase cursor-pointer rounded-[1px] font-[inherit] hover:bg-[#333] transition-colors duration-150"
                >
                    + Nova imagem
                </button>
            @endif
        </div>
    @endif

    {{-- Create / Edit Modal --}}
    <div
        x-show="showModal"
        x-cloak
        class="fixed inset-0 bg-black/45 z-[100] flex items-center justify-center p-4"
    >
        <div class="bg-white border border-fs-border rounded-[2px] p-8 w-full max-w-[480px] max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-fs-dark text-[1rem] font-semibold m-0" x-text="editingId ? 'Editar imagem' : 'Nova imagem'"></h2>
                <button
                    @click="showModal = false"
                    class="bg-transparent border-0 text-[#aaa] text-[1.4rem] cursor-pointer leading-none p-0 hover:text-fs-dark transition-colors"
                >&times;</button>
            </div>

            {{-- Create form --}}
            <form
                x-show="!editingId"
                method="POST"
                :action="formAction"
                enctype="multipart/form-data"
                class="flex flex-col gap-[1.25rem]"
            >
                @csrf
                <div>
                    <label class="block text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Nome *</label>
                    <input
                        type="text"
                        name="name"
                        class="w-full bg-transparent border-0 border-b border-fs-mid py-[0.4rem] px-0 text-fs-dark text-[0.9rem] outline-none font-[inherit] box-border"
                    >
                    @error('name')
                        <p class="text-red-500 text-[0.72rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Descrição</label>
                    <textarea
                        name="description"
                        rows="3"
                        class="w-full bg-transparent border border-fs-border py-2 px-3 text-fs-dark text-[0.88rem] outline-none resize-y font-[inherit] box-border rounded-[1px]"
                    ></textarea>
                </div>
                <div>
                    <label class="block text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Categoria</label>
                    <select
                        name="category_id"
                        class="w-full bg-white border border-fs-border text-fs-dark py-2 px-3 text-[0.88rem] outline-none rounded-[1px] font-[inherit] box-border"
                    >
                        <option value="">— Sem categoria —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Imagem *</label>
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="w-full text-[0.85rem] text-fs-gray font-[inherit]"
                    >
                    @error('image')
                        <p class="text-red-500 text-[0.72rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-3 pt-2">
                    <button
                        type="button"
                        @click="showModal = false"
                        class="flex-1 bg-white text-fs-gray border border-fs-border py-[0.6rem] text-[0.72rem] font-bold tracking-[0.08em] uppercase cursor-pointer rounded-[1px] font-[inherit]"
                    >Cancelar</button>
                    <button
                        type="submit"
                        class="flex-1 bg-fs-dark text-fs-light border-0 py-[0.6rem] text-[0.72rem] font-bold tracking-[0.08em] uppercase cursor-pointer rounded-[1px] font-[inherit] hover:bg-[#333] transition-colors duration-150"
                    >Adicionar</button>
                </div>
            </form>

            {{-- Edit form --}}
            <form
                x-show="editingId"
                method="POST"
                :action="formAction"
                enctype="multipart/form-data"
                class="flex flex-col gap-[1.25rem]"
            >
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Nome *</label>
                    <input
                        type="text"
                        name="name"
                        x-model="editName"
                        class="w-full bg-transparent border-0 border-b border-fs-mid py-[0.4rem] px-0 text-fs-dark text-[0.9rem] outline-none font-[inherit] box-border"
                    >
                </div>
                <div>
                    <label class="block text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Descrição</label>
                    <textarea
                        name="description"
                        rows="3"
                        x-model="editDesc"
                        class="w-full bg-transparent border border-fs-border py-2 px-3 text-fs-dark text-[0.88rem] outline-none resize-y font-[inherit] box-border rounded-[1px]"
                    ></textarea>
                </div>
                <div>
                    <label class="block text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Categoria</label>
                    <select
                        name="category_id"
                        x-model="editCatId"
                        class="w-full bg-white border border-fs-border text-fs-dark py-2 px-3 text-[0.88rem] outline-none rounded-[1px] font-[inherit] box-border"
                    >
                        <option value="">— Sem categoria —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[0.62rem] font-bold tracking-[0.14em] uppercase text-fs-muted mb-2">Imagem (manter atual se vazio)</label>
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="w-full text-[0.85rem] text-fs-gray font-[inherit]"
                    >
                </div>
                <div class="flex gap-3 pt-2">
                    <button
                        type="button"
                        @click="showModal = false"
                        class="flex-1 bg-white text-fs-gray border border-fs-border py-[0.6rem] text-[0.72rem] font-bold tracking-[0.08em] uppercase cursor-pointer rounded-[1px] font-[inherit]"
                    >Cancelar</button>
                    <button
                        type="submit"
                        class="flex-1 bg-fs-dark text-fs-light border-0 py-[0.6rem] text-[0.72rem] font-bold tracking-[0.08em] uppercase cursor-pointer rounded-[1px] font-[inherit] hover:bg-[#333] transition-colors duration-150"
                    >Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Confirm Modal --}}
    <div
        x-show="deleteId !== null"
        x-cloak
        class="fixed inset-0 bg-black/45 z-[100] flex items-center justify-center p-4"
    >
        <div class="bg-white border border-fs-border rounded-[2px] p-8 w-full max-w-[380px]">
            <h2 class="text-fs-dark text-[0.95rem] font-semibold m-0 mb-3">Eliminar imagem?</h2>
            <p class="text-fs-gray text-[0.85rem] m-0 mb-6">Esta ação não pode ser desfeita.</p>
            <div class="flex gap-3">
                <button
                    @click="deleteId = null"
                    class="flex-1 bg-white text-fs-gray border border-fs-border py-[0.6rem] text-[0.72rem] font-bold tracking-[0.08em] uppercase cursor-pointer rounded-[1px] font-[inherit]"
                >Cancelar</button>

                @foreach ($images as $image)
                <form
                    x-show="deleteId === {{ $image->id }}"
                    method="POST"
                    action="{{ route('my-images.destroy', $image->id) }}"
                    class="flex-1"
                >
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="w-full bg-red-600 text-white border-0 py-[0.6rem] text-[0.72rem] font-bold tracking-[0.08em] uppercase cursor-pointer rounded-[1px] font-[inherit] hover:bg-red-700 transition-colors duration-150"
                    >Eliminar</button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
