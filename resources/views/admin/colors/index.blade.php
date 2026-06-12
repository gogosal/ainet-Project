@extends('layouts.admin', ['title' => 'Cores'])

@section('content')

<div x-data="{
    showModal: false,
    editingId: null,
    editName: '',
    editCode: '',
    deleteId: null
}">

    {{-- Toolbar --}}
    <div class="flex justify-end mb-5">
        <button type="button"
            @click="showModal = true; editingId = null; editName = ''; editCode = '';"
            class="bg-fs-purple text-white border-none rounded-[1px] px-[1.1rem] py-[0.55rem] cursor-pointer text-[0.85rem] font-semibold transition-colors duration-150 hover:bg-[#6b5f8e]">
            + Nova cor
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b border-fs-border">
                    <th class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-left">Cor</th>
                    <th class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-left">Nome</th>
                    <th class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-left">Código</th>
                    <th class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-left">Imagem base</th>
                    <th class="text-fs-gray text-[0.75rem] font-semibold uppercase tracking-[0.05em] px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colors as $color)
                    @php
                        $baseCode = ltrim($color->code, '#');
                        $baseFile = null;
                        foreach (['.jpg', '.jpeg', '.png'] as $ext) {
                            if (file_exists(public_path('storage/tshirt_base/' . $baseCode . $ext))) {
                                $baseFile = $baseCode . $ext; break;
                            }
                        }
                    @endphp
                    <tr class="border-b border-fs-border">
                        <td class="px-4 py-3">
                            <div class="w-10 h-10 rounded-[1px] border border-[rgba(0,0,0,0.12)] shadow-[inset_0_0_0_1px_rgba(255,255,255,0.2)]"
                                 style="background:#{{ $baseCode }};"></div>
                        </td>
                        <td class="px-4 py-3 text-fs-dark text-[0.9rem] font-medium">{{ $color->name }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-white border border-fs-border text-fs-purple rounded-[1px] px-[0.5rem] py-[0.2rem] text-[0.78rem] font-mono">{{ $color->code }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($baseFile)
                                <img src="/storage/tshirt_base/{{ $baseFile }}" alt="{{ $color->name }}"
                                    class="h-11 w-auto rounded-[1px] border border-fs-border" />
                            @else
                                <span class="text-[#aaa] text-[0.78rem]">Sem imagem</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex gap-2 justify-end">
                                <button type="button"
                                    @click="showModal = true; editingId = {{ $color->id }}; editName = '{{ addslashes($color->name) }}'; editCode = '{{ $color->code }}';"
                                    class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-3 py-[0.4rem] cursor-pointer text-[0.8rem] transition-colors duration-150 hover:border-fs-dark hover:text-fs-dark">
                                    Editar
                                </button>
                                <button type="button"
                                    @click="deleteId = {{ $color->id }}"
                                    class="bg-[rgba(239,68,68,0.1)] text-[#f87171] border border-[rgba(239,68,68,0.3)] rounded-[1px] px-3 py-[0.4rem] cursor-pointer text-[0.8rem] transition-colors duration-150 hover:bg-[rgba(239,68,68,0.2)]">
                                    Apagar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-fs-gray px-4 py-10 text-[0.875rem]">
                            Nenhuma cor encontrada.
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
                <span x-show="editingId">Editar cor</span>
                <span x-show="!editingId">Nova cor</span>
            </h2>

            {{-- CREATE form --}}
            <div x-show="!editingId">
                <form method="POST" action="{{ route('admin.colors.store') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Código hex *</label>
                        <div class="flex gap-2 items-center">
                            <input type="color" name="code_picker" id="colorPickerCreate"
                                class="w-11 h-[38px] p-[2px] bg-white border border-fs-border rounded-[1px] cursor-pointer"
                                oninput="document.getElementById('colorCodeCreate').value = this.value" />
                            <input type="text" name="code" id="colorCodeCreate" placeholder="#rrggbb"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] flex-1 text-[0.9rem] outline-none font-mono"
                                oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value)) document.getElementById('colorPickerCreate').value = this.value"
                                required />
                        </div>
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Nome *</label>
                        <input type="text" name="name" placeholder="Ex: Branco"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" required />
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Imagem base da t-shirt (opcional)</label>
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
            </div>

            {{-- EDIT forms --}}
            <div x-show="editingId">
                @foreach($colors as $color)
                <form x-show="editingId === {{ $color->id }}"
                      method="POST" action="{{ route('admin.colors.update', $color->id) }}"
                      enctype="multipart/form-data" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Código</label>
                        <div class="flex gap-2 items-center">
                            <div class="w-9 h-9 rounded-[1px] border-2 border-[rgba(255,255,255,0.1)] flex-shrink-0"
                                 style="background:{{ $color->code }};"></div>
                            <span class="bg-white border border-fs-border text-fs-gray rounded-[1px] px-3 py-[0.6rem] font-mono text-[0.9rem]">{{ $color->code }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Nome *</label>
                        <input type="text" name="name" value="{{ $color->name }}"
                            class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" required />
                    </div>
                    <div>
                        <label class="text-fs-gray text-[0.8rem] block mb-[0.4rem]">Imagem base da t-shirt (opcional)</label>
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
            <p class="text-fs-gray text-[0.875rem] m-0 mb-5">Tem a certeza que deseja eliminar esta cor? Esta ação é irreversível.</p>

            @foreach($colors as $color)
            <form x-show="deleteId === {{ $color->id }}"
                  method="POST" action="{{ route('admin.colors.destroy', $color->id) }}"
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
