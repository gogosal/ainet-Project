@extends('layouts.admin', ['title' => 'Categorias'])

@section('content')
    <div x-data="{
        showModal: false,
        editingId: null,
        editName: '',
        deleteId: null
    }">

        {{-- Toolbar --}}
        <div class="flex justify-end mb-5">
            <button type="button" @click="showModal = true; editingId = null; editName = '';"
                class="bg-fs-purple text-white border-none rounded-[1px] px-[1.1rem] py-[0.55rem] cursor-pointer text-[0.85rem] font-semibold transition-colors duration-150 hover:bg-[#6b5f8e]">
                + Nova categoria
            </button>
        </div>

        {{-- Table --}}
        <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-fs-border">
                        <th
                            class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-left">
                            Imagem</th>
                        <th
                            class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-left">
                            Nome</th>
                        <th
                            class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-center">
                            Imagens</th>
                        <th
                            class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-right">
                            Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        @php $catImgUrl = str_contains($cat->image_url ?? '', '/') ? \Illuminate\Support\Facades\Storage::url($cat->image_url) : asset('storage/categories/' . $cat->image_url); @endphp
                        <tr class="border-b border-fs-border">
                            <td class="px-4 py-3">
                                @if ($cat->image_url)
                                    <img src="{{ $catImgUrl }}" alt="{{ $cat->name }}"
                                        class="w-12 h-12 object-cover rounded-[1px] border border-fs-border" />
                                @else
                                    <div
                                        class="w-12 h-12 bg-white rounded-[1px] border border-fs-border flex items-center justify-center text-[#aaa]">
                                        🏷️</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-fs-dark text-[0.9rem] font-medium">{{ $cat->name }}</td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="bg-[rgba(124,111,160,0.12)] text-fs-purple rounded-[1px] px-[0.6rem] py-[0.2rem] text-[0.78rem] font-semibold">
                                    {{ $cat->tshirt_images_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex gap-2 justify-end">
                                    <button type="button"
                                        @click="showModal = true; editingId = {{ $cat->id }}; editName = '{{ addslashes($cat->name) }}';"
                                        class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-3 py-[0.4rem] cursor-pointer text-[0.8rem] transition-colors duration-150 hover:border-fs-dark hover:text-fs-dark">
                                        Editar
                                    </button>
                                    <button type="button" @click="deleteId = {{ $cat->id }}"
                                        class="bg-[rgba(239,68,68,0.1)] text-[#f87171] border border-[rgba(239,68,68,0.3)] rounded-[1px] px-3 py-[0.4rem] cursor-pointer text-[0.8rem] transition-colors duration-150 hover:bg-[rgba(239,68,68,0.2)]">
                                        Apagar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-fs-gray px-4 py-10 text-[0.875rem]">
                                Nenhuma categoria encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Create / Edit Modal --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 bg-[rgba(0,0,0,0.7)] z-50 flex items-center justify-center p-4">
            <div class="bg-white border border-fs-border rounded-[2px] w-full max-w-[420px] p-6">
                <h2 class="text-fs-dark text-base font-bold m-0 mb-5">
                    <span x-show="editingId">Editar categoria</span>
                    <span x-show="!editingId">Nova categoria</span>
                </h2>

                {{-- CREATE form --}}
                <div x-show="!editingId">
                    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data"
                        class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Nome *</label>
                            <input type="text" name="name" placeholder="Nome da categoria"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none"
                                required />
                        </div>
                        <div>
                            <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Imagem *</label>
                            <input type="file" name="image" accept="image/*"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.5rem] w-full text-[0.85rem] outline-none"
                                required />
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

                {{-- EDIT forms --}}
                {{-- EDIT forms --}}
                <div x-show="editingId">
                    @foreach ($categories as $cat)
                        <form x-show="editingId === {{ $cat->id }}" method="POST"
                            action="{{ route('admin.categories.update', $cat->id) }}" enctype="multipart/form-data"
                            class="flex flex-col gap-4">
                            @csrf
                            <div>
                                <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Nome *</label>
                                <input type="text" name="name" value="{{ $cat->name }}"
                                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none"
                                    required />
                            </div>
                            <div>
                                <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Imagem (deixe vazio para
                                    manter)</label>

                                {{-- Preview da imagem atual --}}
                                @if ($cat->image_url)
                                    @php
                                        $catImgUrl = str_contains($cat->image_url, '/')
                                            ? \Illuminate\Support\Facades\Storage::url($cat->image_url)
                                            : asset('storage/categories/' . $cat->image_url);
                                    @endphp
                                    <div
                                        class="mb-3 flex items-center gap-3 bg-[#f8f9fa] p-2 border border-fs-border rounded-[1px]">
                                        <img src="{{ $catImgUrl }}" alt="{{ $cat->name }}"
                                            class="w-12 h-12 object-cover rounded-[1px] border border-fs-border bg-white" />
                                        <span class="text-[0.75rem] text-fs-gray">Imagem atual</span>
                                    </div>
                                @endif

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
                <p class="text-fs-gray text-[0.875rem] m-0 mb-5">Tem a certeza que deseja eliminar esta categoria? Esta ação
                    é irreversível.</p>

                @foreach ($categories as $cat)
                    <form x-show="deleteId === {{ $cat->id }}" method="POST"
                        action="{{ route('admin.categories.destroy', $cat->id) }}" class="flex gap-3">
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
