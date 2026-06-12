@extends('layouts.admin', ['title' => 'Colaboradores'])
@section('content')

<div class="p-6"
     x-data="{
         showModal: false,
         showDeleteModal: false,
         isEditing: false,
         editingId: null,
         deleteId: null,
         form: { name: '', email: '', password: '', user_type: 'F', gender: '', photo: null },
         openCreate() {
             this.isEditing = false;
             this.editingId = null;
             this.form = { name: '', email: '', password: '', user_type: 'F', gender: '', photo: null };
             this.showModal = true;
         },
         openEdit(id, name, email, userType, gender) {
             this.isEditing = true;
             this.editingId = id;
             this.form = { name: name, email: email, password: '', user_type: userType, gender: gender, photo: null };
             this.showModal = true;
         },
         confirmDelete(id) {
             this.deleteId = id;
             this.showDeleteModal = true;
         }
     }">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-fs-dark text-[1.5rem] font-bold m-0">Colaboradores</h1>
            <p class="text-fs-gray text-[0.85rem] mt-1 m-0">Gestão de funcionários e administradores</p>
        </div>
        <button
            type="button"
            @click="openCreate()"
            class="bg-fs-purple text-white border-none rounded-[1px] px-4 py-2 cursor-pointer text-[0.85rem] font-semibold hover:opacity-90 transition-opacity">
            + Novo colaborador
        </button>
    </div>


    {{-- Table --}}
    <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-[#f9f8f6]">
                    <th class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Nome</th>
                    <th class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Email</th>
                    <th class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Tipo</th>
                    <th class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Género</th>
                    <th class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($staff as $member)
                    <tr>
                        <td class="px-4 py-3 border-b border-fs-border text-fs-dark text-[0.85rem]">
                            <div class="flex items-center gap-2">
                                @if ($member->photo_url ?? null)
                                    @php $memberPhotoSrc = str_contains($member->photo_url ?? '', '/') ? asset('storage/' . $member->photo_url) : asset('storage/photos/' . $member->photo_url); @endphp
                                    <img src="{{ $memberPhotoSrc }}" alt="" class="w-7 h-7 rounded-full object-cover">
                                @else
                                    <div class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-fs-purple text-[0.75rem] font-bold border border-fs-border">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                                {{ $member->name }}
                            </div>
                        </td>
                        <td class="px-4 py-3 border-b border-fs-border text-fs-gray text-[0.85rem]">{{ $member->email }}</td>
                        <td class="px-4 py-3 border-b border-fs-border text-[0.85rem]">
                            @if ($member->user_type === 'A')
                                <span class="bg-purple-100 text-fs-purple border border-purple-200 rounded-[1px] px-[0.65rem] py-[0.2rem] text-[0.75rem] font-semibold">Admin</span>
                            @else
                                <span class="bg-sky-100 text-sky-400 border border-sky-200 rounded-[1px] px-[0.65rem] py-[0.2rem] text-[0.75rem] font-semibold">Funcionário</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border-b border-fs-border text-fs-gray text-[0.85rem]">
                            @if ($member->gender === 'M') Masculino
                            @elseif ($member->gender === 'F') Feminino
                            @else —
                            @endif
                        </td>
                        <td class="px-4 py-3 border-b border-fs-border text-[0.85rem]">
                            <div class="flex gap-2">
                                <button
                                    type="button"
                                    @click="openEdit({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ addslashes($member->email) }}', '{{ $member->user_type }}', '{{ $member->gender ?? '' }}')"
                                    class="bg-purple-50 text-fs-purple border border-purple-200 rounded-[1px] px-3 py-[0.35rem] cursor-pointer text-[0.78rem] font-semibold hover:bg-purple-100 transition-colors">
                                    Editar
                                </button>
                                <button
                                    type="button"
                                    @click="confirmDelete({{ $member->id }})"
                                    class="bg-red-100 text-red-400 border border-red-200 rounded-[1px] px-3 py-[0.35rem] cursor-pointer text-[0.78rem] font-semibold hover:bg-red-200 transition-colors">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-fs-gray text-[0.9rem]">Nenhum colaborador encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Create / Edit Modal --}}
    <div
        x-show="showModal"
        x-cloak
        class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-white border border-fs-border rounded-[2px] p-8 w-full max-w-[480px]">
            <h2 class="text-fs-dark text-[1.1rem] font-bold m-0 mb-6" x-text="isEditing ? 'Editar colaborador' : 'Novo colaborador'"></h2>

            <template x-if="!isEditing">
                <form method="POST" action="{{ route('admin.staff.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Nome *</label>
                            <input type="text" name="name" x-model="form.name" placeholder="Nome completo"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple" />
                        </div>
                        <div>
                            <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Email *</label>
                            <input type="email" name="email" x-model="form.email" placeholder="email@exemplo.com"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Tipo *</label>
                                <select name="user_type" x-model="form.user_type"
                                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple">
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Género</label>
                                <select name="gender" x-model="form.gender"
                                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple">
                                    <option value="">— Não definido —</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Feminino</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Foto</label>
                            <input type="file" name="photo" accept="image/*"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" />
                        </div>
                        <div>
                            <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Palavra-passe *</label>
                            <input type="password" name="password" x-model="form.password" placeholder="Mínimo 8 caracteres"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple" />
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showModal = false"
                            class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-4 py-2 cursor-pointer text-[0.85rem] font-semibold hover:border-fs-dark hover:text-fs-dark transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-fs-purple text-white border-none rounded-[1px] px-4 py-2 cursor-pointer text-[0.85rem] font-semibold hover:opacity-90 transition-opacity">
                            Criar colaborador
                        </button>
                    </div>
                </form>
            </template>

            <template x-if="isEditing">
                <form method="POST" :action="`{{ url('admin/staff') }}/${editingId}`" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="_method_override" value="UPDATE">
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Nome *</label>
                            <input type="text" name="name" x-model="form.name" placeholder="Nome completo"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple" />
                        </div>
                        <div>
                            <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Email *</label>
                            <input type="email" name="email" x-model="form.email" placeholder="email@exemplo.com"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Tipo *</label>
                                <select name="user_type" x-model="form.user_type"
                                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple">
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Género</label>
                                <select name="gender" x-model="form.gender"
                                    class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple">
                                    <option value="">— Não definido —</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Feminino</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Foto (opcional)</label>
                            <input type="file" name="photo" accept="image/*"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none" />
                        </div>
                        <div>
                            <label class="block text-fs-gray text-[0.8rem] mb-[0.4rem]">Palavra-passe (deixar em branco para não alterar)</label>
                            <input type="password" name="password" x-model="form.password" placeholder="Mínimo 8 caracteres"
                                class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-full text-[0.9rem] outline-none focus:border-fs-purple" />
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showModal = false"
                            class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-4 py-2 cursor-pointer text-[0.85rem] font-semibold hover:border-fs-dark hover:text-fs-dark transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="bg-fs-purple text-white border-none rounded-[1px] px-4 py-2 cursor-pointer text-[0.85rem] font-semibold hover:opacity-90 transition-opacity">
                            Guardar alterações
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div
        x-show="showDeleteModal"
        x-cloak
        class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-white border border-fs-border rounded-[2px] p-8 w-full max-w-[400px]">
            <h2 class="text-fs-dark text-[1.1rem] font-bold m-0 mb-3">Confirmar eliminação</h2>
            <p class="text-fs-gray text-[0.9rem] m-0 mb-6">Tem a certeza que quer eliminar este colaborador? Esta ação não pode ser revertida.</p>
            <div class="flex justify-end gap-3">
                <button type="button" @click="showDeleteModal = false; deleteId = null"
                    class="bg-white text-fs-gray border border-fs-border rounded-[1px] px-4 py-2 cursor-pointer text-[0.85rem] font-semibold hover:border-fs-dark hover:text-fs-dark transition-colors">
                    Cancelar
                </button>
                <form method="POST" :action="`{{ url('admin/staff') }}/${deleteId}`" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 text-white border-none rounded-[1px] px-4 py-2 cursor-pointer text-[0.85rem] font-semibold hover:bg-red-700 transition-colors">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
