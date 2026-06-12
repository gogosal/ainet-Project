@extends('layouts.admin', ['title' => 'Clientes'])
@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-fs-dark text-[1.5rem] font-bold m-0">Clientes</h1>
                <p class="text-fs-gray text-[0.85rem] mt-1 m-0">Gestão de clientes registados</p>
            </div>
        </div>

        {{-- Search + Filter --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-6 mb-6">
            <div class="flex gap-4 flex-wrap items-center">
                <form method="GET" action="{{ route('admin.customers') }}" class="flex gap-4 flex-wrap items-center w-full">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Pesquisar por nome ou email..."
                        class="bg-white border border-fs-border text-fs-dark rounded-[1px] px-3 py-[0.6rem] w-[280px] text-[0.9rem] outline-none focus:border-fs-purple" />
                    <div class="flex gap-2">
                        @foreach (['all' => 'Todos', 'active' => 'Ativos', 'blocked' => 'Bloqueados'] as $value => $label)
                            <a href="{{ route('admin.customers', array_merge(request()->except('filter', 'page'), ['filter' => $value, 'search' => $search ?? ''])) }}"
                                class="rounded-[1px] px-4 py-2 text-[0.85rem] font-semibold border border-fs-border no-underline {{ ($filter ?? 'all') === $value ? 'bg-fs-purple text-white border-fs-purple' : 'bg-white text-fs-gray hover:text-fs-dark' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white border border-fs-border rounded-[2px] overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-[#f9f8f6]">
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Nome</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Email</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Género</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            NIF</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Estado</th>
                        <th
                            class="px-4 py-3 text-left text-fs-gray text-[0.8rem] font-semibold uppercase tracking-[0.05em] border-b border-fs-border">
                            Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="{{ $user->blocked ? 'bg-red-50/40' : '' }}">
                            <td class="px-4 py-3 border-b border-fs-border text-fs-dark text-[0.85rem]">
                                <div class="flex items-center gap-2">
                                    {{-- A MUDANÇA ESTÁ AQUI: muito mais limpo! --}}
                                    @if ($user->photoSrc)
                                        <img src="{{ $user->photoSrc }}" alt="{{ $user->name }}"
                                            class="w-7 h-7 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-7 h-7 rounded-full bg-white flex items-center justify-center text-fs-purple text-[0.75rem] font-bold border border-fs-border">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-fs-gray text-[0.85rem]">{{ $user->email }}
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-fs-gray text-[0.85rem]">
                                @if ($user->gender === 'M')
                                    Masculino
                                @elseif ($user->gender === 'F')
                                    Feminino
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-fs-gray text-[0.85rem]">
                                {{ $user->customer?->nif ?? '—' }}</td>
                            <td class="px-4 py-3 border-b border-fs-border text-[0.85rem]">
                                @if ($user->blocked)
                                    <span
                                        class="bg-red-100 text-red-400 border border-red-200 rounded-[1px] px-[0.65rem] py-[0.2rem] text-[0.75rem] font-semibold">Bloqueado</span>
                                @else
                                    <span
                                        class="bg-green-100 text-green-600 border border-green-200 rounded-[1px] px-[0.65rem] py-[0.2rem] text-[0.75rem] font-semibold">Ativo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border-b border-fs-border text-[0.85rem]">
                                <div class="flex gap-2 flex-wrap">
                                    <form method="POST" action="{{ route('admin.customers.toggle-block', $user->id) }}"
                                        class="inline"
                                        onsubmit="return confirm('{{ $user->blocked ? 'Desbloquear este cliente?' : 'Bloquear este cliente?' }}')">
                                        @csrf
                                        <button type="submit"
                                            class="{{ $user->blocked ? 'bg-green-100 text-green-600 border border-green-200' : 'bg-red-100 text-red-400 border border-red-200' }} rounded-[1px] px-3 py-[0.35rem] cursor-pointer text-[0.78rem] font-semibold">
                                            {{ $user->blocked ? 'Desbloquear' : 'Bloquear' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.customers.destroy', $user->id) }}"
                                        class="inline"
                                        onsubmit="return confirm('Tem a certeza que quer eliminar este cliente? Esta ação não pode ser revertida.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-100 text-red-400 border border-red-200 rounded-[1px] px-3 py-[0.35rem] cursor-pointer text-[0.78rem] font-semibold">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-fs-gray text-[0.9rem]">Nenhum cliente
                                encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginator --}}
        @if ($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif

    </div>
@endsection
