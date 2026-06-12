<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1">Biblioteca pessoal</div>
            <h1 class="text-3xl font-light tracking-tight text-gray-900 m-0">As minhas <em
                    class="font-bold not-italic text-indigo-900">imagens</em></h1>
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-4 py-2.5 text-xs font-bold tracking-widest uppercase rounded-lg transition-colors shadow-sm">
            + Nova imagem
        </button>
    </div>

    {{-- Mensagem de Sucesso --}}
    @if (session('success'))
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesquisa --}}
    <div class="mb-8">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Pesquisar por nome…"
            class="w-full sm:w-72 bg-transparent border-0 border-b-2 border-gray-200 focus:border-indigo-600 focus:ring-0 py-2 text-gray-900 text-sm transition-colors placeholder-gray-400">
    </div>

    {{-- Grelha de Imagens --}}
    @if ($images->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-8">
            @foreach ($images as $image)
                @php
                    $bare = basename($image->image_url);
                    $imgSrc = file_exists(public_path('storage/tshirt_images/' . $bare))
                        ? asset('storage/tshirt_images/' . $bare)
                        : route('private-image', $bare);
                @endphp

                {{-- Cartão da Imagem --}}
                <div
                    class="bg-white border border-gray-200 rounded-xl flex flex-col overflow-hidden group hover:shadow-md hover:border-indigo-300 transition-all">
                    {{-- Imagem --}}
                    <div class="aspect-square bg-gray-50 overflow-hidden relative">
                        <img src="{{ asset('storage/tshirt_images/' . basename($image->image_url)) }}"
                            alt="{{ $image->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>

                    {{-- Detalhes do Cartão --}}
                    <div class="p-4 flex-1 flex flex-col border-t border-gray-100">
                        <p class="text-gray-900 text-sm font-bold truncate mb-0.5">{{ $image->name }}</p>
                        <p class="text-gray-500 text-xs truncate">{{ $image->category?->name ?? 'Sem categoria' }}</p>

                        @if ($image->description)
                            <p class="text-gray-400 text-xs mt-2 line-clamp-2">{{ $image->description }}</p>
                        @endif

                        {{-- Botões de Ação --}}
                        <div class="flex gap-2 mt-auto pt-4">
                            <button wire:click="openEdit({{ $image->id }})"
                                class="flex-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 py-1.5 text-xs font-bold tracking-wider uppercase rounded-md transition-colors">
                                Editar
                            </button>
                            <button wire:click="confirmDelete({{ $image->id }})"
                                class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 px-3 py-1.5 rounded-md transition-colors flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginação --}}
        <div>
            {{ $images->links() }}
        </div>
    @else
        {{-- Estado Vazio --}}
        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center shadow-sm">
            <p class="text-gray-500 text-base mb-6">
                @if ($search)
                    Nenhuma imagem encontrada para "<span
                        class="font-semibold text-gray-700">{{ $search }}</span>".
                @else
                    Ainda não tens imagens na tua biblioteca. Adiciona a tua primeira!
                @endif
            </p>
            @if (!$search)
                <button wire:click="openCreate"
                    class="inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white px-5 py-2.5 text-xs font-bold tracking-widest uppercase rounded-lg transition-colors">
                    + Nova imagem
                </button>
            @endif
        </div>
    @endif

    {{-- Modal: Criar / Editar --}}
    @if ($showModal)
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">

                <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-900 m-0">{{ $editingId ? 'Editar imagem' : 'Nova imagem' }}
                    </h2>
                    <button wire:click="$set('showModal', false)"
                        class="text-gray-400 hover:text-gray-700 transition-colors p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form wire:submit="saveImage" class="p-6 space-y-5 overflow-y-auto">
                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Nome
                            *</label>
                        <input type="text" wire:model="modalName"
                            class="w-full bg-white border border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-lg px-4 py-2.5 text-sm text-gray-900 transition-colors">
                        @error('modalName')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Descrição</label>
                        <textarea wire:model="modalDescription" rows="3"
                            class="w-full bg-white border border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-lg px-4 py-2.5 text-sm text-gray-900 transition-colors resize-y"></textarea>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Categoria</label>
                        <select wire:model="modalCategoryId"
                            class="w-full bg-white border border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-lg px-4 py-2.5 text-sm text-gray-900 transition-colors">
                            <option value="">— Sem categoria —</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-widest uppercase text-gray-500 mb-2">Imagem <span
                                class="normal-case tracking-normal text-gray-400 font-normal">{{ $editingId ? '(manter atual se vazio)' : '*' }}</span></label>
                        <input type="file" wire:model="modalImage" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:uppercase file:tracking-wider file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                        @error('modalImage')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="flex-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 py-2.5 text-xs font-bold tracking-widest uppercase rounded-lg transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 bg-gray-900 hover:bg-gray-800 text-white border border-transparent py-2.5 text-xs font-bold tracking-widest uppercase rounded-lg transition-colors">
                            {{ $editingId ? 'Guardar' : 'Adicionar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal: Eliminar --}}
    @if ($deleteId)
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 mb-2">Eliminar imagem?</h2>
                <p class="text-gray-500 text-sm mb-6">Esta ação não pode ser desfeita e a imagem será permanentemente
                    apagada da tua biblioteca.</p>

                <div class="flex gap-3">
                    <button wire:click="cancelDelete"
                        class="flex-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 py-2.5 text-xs font-bold tracking-widest uppercase rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="deleteImage"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white border border-transparent py-2.5 text-xs font-bold tracking-widest uppercase rounded-lg transition-colors">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
