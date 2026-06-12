@extends('layouts.app', ['title' => 'As minhas imagens'])

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{
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
                <div class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Biblioteca pessoal</div>
                <h1 class="text-3xl font-light tracking-tight text-gray-900 m-0">As minhas <em
                        class="font-bold not-italic text-indigo-900">imagens</em></h1>
            </div>
            <button
                @click="showModal = true; editingId = null; editName = ''; editDesc = ''; editCatId = ''; formAction = '{{ route('my-images.store') }}';"
                class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-2.5 text-xs font-bold tracking-widest uppercase rounded-lg transition-colors shadow-sm">
                + Nova imagem
            </button>
        </div>

        @push('scripts')
            @if (session('success'))
                <script>
                    window.addEventListener('load', () => {
                        window.dispatchEvent(new CustomEvent('toast-success', {
                            detail: '{{ session('success') }}'
                        }));
                    });
                </script>
            @endif
        @endpush

        {{-- Pesquisa --}}
        <div class="mb-8">
            <form method="GET" action="{{ route('my-images') }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Pesquisar por nome…"
                    class="w-full sm:w-72 bg-transparent border-0 border-b-2 border-gray-200 focus:border-indigo-600 focus:ring-0 py-2 text-gray-900 text-sm transition-colors placeholder-gray-400">
            </form>
        </div>

        {{-- Grelha de Imagens --}}
        @if ($images->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-8">
                @foreach ($images as $image)
                    <div
                        class="bg-white border border-gray-200 rounded-xl flex flex-col overflow-hidden group hover:shadow-md hover:border-indigo-300 transition-all">
                        <div class="aspect-square bg-gray-50 overflow-hidden relative">
                            <img src="{{ $image->resolved_url }}" alt="{{ $image->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-4 flex-1 flex flex-col border-t border-gray-100">
                            <p class="text-gray-900 text-sm font-bold truncate mb-0.5">{{ $image->name }}</p>
                            <p class="text-gray-500 text-xs truncate">{{ $image->category?->name ?? 'Sem categoria' }}</p>
                            @if ($image->description)
                                <p class="text-gray-400 text-xs mt-2 line-clamp-2">{{ $image->description }}</p>
                            @endif
                            <div class="flex gap-2 mt-auto pt-4">
                                <button
                                    @click="editingId = {{ $image->id }}; editName = '{{ addslashes($image->name) }}'; editDesc = '{{ addslashes($image->description ?? '') }}'; editCatId = '{{ $image->category_id }}'; formAction = '{{ route('my-images.update', $image->id) }}'; showModal = true;"
                                    class="flex-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 py-1.5 text-xs font-bold tracking-wider uppercase rounded-md transition-colors">
                                    Editar
                                </button>
                                <button @click="deleteId = {{ $image->id }}"
                                    class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 px-3 py-1.5 rounded-md transition-colors flex items-center justify-center">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div>{{ $images->links() }}</div>
        @else
            <div class="bg-white border border-gray-200 rounded-xl p-12 text-center shadow-sm">
                <p class="text-gray-500 text-base">Ainda não tens imagens.</p>
            </div>
        @endif

        {{-- Modal Criar / Editar --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-900 m-0" x-text="editingId ? 'Editar imagem' : 'Nova imagem'">
                    </h2>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-700">×</button>
                </div>

                <form method="POST" :action="formAction" enctype="multipart/form-data"
                    class="p-6 space-y-5 overflow-y-auto">
                    @csrf
                    <template x-if="editingId"><input type="hidden" name="_method" value="PUT"></template>

                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Nome *</label>
                        <input type="text" name="name" x-model="editName"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Descrição</label>
                        <textarea name="description" x-model="editDesc" rows="3"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm"></textarea>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Categoria</label>
                        <select name="category_id" x-model="editCatId"
                            class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                            <option value="">— Sem categoria —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Imagem</label>

                        <input type="file" name="image" accept="image/*"
                            onchange="if(this.files[0] && !this.files[0].type.startsWith('image/')) {
           window.dispatchEvent(new CustomEvent('toast-error', { detail: 'Erro: Só suportamos imagens!' })); this.value = '';}"
                            class="w-full text-sm">
                        {{-- Mensagem de erro do Laravel (Backend) --}}
                        @error('image')
                            <p class="text-red-500 text-xs mt-1 font-medium">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="flex gap-3 pt-4 border-t">
                        <button type="button" @click="showModal = false"
                            class="flex-1 bg-white hover:bg-gray-50 border py-2.5 text-xs font-bold uppercase rounded-lg">Cancelar</button>
                        <button type="submit"
                            class="flex-1 bg-gray-900 hover:bg-gray-800 text-white py-2.5 text-xs font-bold uppercase rounded-lg">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Eliminar --}}
        @foreach ($images as $image)
            <div x-show="deleteId === {{ $image->id }}" x-cloak
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                    <h2 class="text-lg font-bold mb-4">Eliminar imagem?</h2>
                    <div class="flex gap-3">
                        <button @click="deleteId = null"
                            class="flex-1 py-2.5 bg-gray-100 rounded-lg font-bold text-xs uppercase">Cancelar</button>
                        <form action="{{ route('my-images.destroy', $image->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full py-2.5 bg-red-600 text-white rounded-lg font-bold text-xs uppercase">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
