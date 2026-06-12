@extends('layouts.app', ['title' => 'O meu perfil'])

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-gray-50/50 min-h-screen">

        {{-- Page Header --}}
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">O meu perfil</h1>
            <p class="mt-2 text-sm text-gray-500">Gere as tuas informações pessoais, moradas e segurança da conta.</p>
        </div>

        <div class="space-y-10 sm:space-y-16">

            {{-- SECTION 1: Informações Pessoais --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                <div class="md:col-span-1">
                    <h2 class="text-lg font-semibold text-gray-900 leading-6">Dados Pessoais</h2>
                    <p class="mt-2 text-sm text-gray-500 leading-relaxed">Atualiza a tua foto e as informações de faturação
                        e entregas.</p>
                </div>

                <div class="md:col-span-2">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                        class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden">
                        @csrf
                        <div class="p-6 sm:p-8 space-y-8">
                            {{-- Avatar --}}
                            <div class="flex items-center gap-x-6">
                                @if ($user->photo_url)
                                    @php $photoSrc = str_contains($user->photo_url, '/') ? asset('storage/' . $user->photo_url) : asset('storage/photos/' . $user->photo_url); @endphp
                                    <img src="{{ $photoSrc }}" alt="Avatar"
                                        class="h-20 w-20 rounded-full object-cover border border-gray-200">
                                @else
                                    <div
                                        class="h-20 w-20 rounded-full bg-[#7c6fa0] flex items-center justify-center text-2xl font-bold text-white shadow-inner">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <label
                                        class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                        <span>Alterar foto</span>
                                        <input type="file" name="photo" accept="image/*" class="sr-only">
                                    </label>
                                </div>
                            </div>

                            {{-- Inputs --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Nome *</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border">
                                </div>
                                <div class="sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Email *</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border">
                                </div>
                                <div class="sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Género</label>
                                    <div class="flex items-center gap-5 mt-2">
                                        <label class="flex items-center gap-2 text-sm"><input type="radio" name="gender"
                                                value="M" {{ old('gender', $user->gender) === 'M' ? 'checked' : '' }}
                                                class="text-[#7c6fa0] focus:ring-[#7c6fa0]"> Masculino</label>
                                        <label class="flex items-center gap-2 text-sm"><input type="radio" name="gender"
                                                value="F" {{ old('gender', $user->gender) === 'F' ? 'checked' : '' }}
                                                class="text-[#7c6fa0] focus:ring-[#7c6fa0]"> Feminino</label>
                                    </div>
                                </div>
                                <div class="sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">NIF</label>
                                    <input type="text" name="nif"
                                        value="{{ old('nif', $user->customer->nif ?? '') }}" maxlength="9"
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] sm:text-sm border">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Morada</label>
                                    <textarea name="address" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm sm:text-sm border">{{ old('address', $user->customer->address ?? '') }}</textarea>
                                </div>
                                <div class="sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Método de pagamento</label>
                                    <select name="default_payment_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm sm:text-sm border">
                                        <option value="">— Selecionar —</option>
                                        <option value="Visa"
                                            {{ old('default_payment_type', $user->customer->default_payment_type ?? '') === 'Visa' ? 'selected' : '' }}>
                                            Visa</option>
                                        <option value="PayPal"
                                            {{ old('default_payment_type', $user->customer->default_payment_type ?? '') === 'PayPal' ? 'selected' : '' }}>
                                            PayPal</option>
                                        <option value="MB WAY"
                                            {{ old('default_payment_type', $user->customer->default_payment_type ?? '') === 'MB WAY' ? 'selected' : '' }}>
                                            MB WAY</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700">Referência</label>
                                    <input type="text" name="default_payment_ref"
                                        value="{{ old('default_payment_ref', $user->customer->default_payment_ref ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm sm:text-sm border">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                            <button type="submit"
                                class="bg-[#1a1a1a] hover:bg-[#333] text-white font-semibold py-2 px-6 rounded-md shadow-sm text-sm">Guardar
                                alterações</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SECTION 2: Segurança --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                <div class="md:col-span-1">
                    <h2 class="text-lg font-semibold text-gray-900 leading-6">Segurança</h2>
                    <p class="mt-2 text-sm text-gray-500">Altera a tua password.</p>
                </div>
                <div class="md:col-span-2">
                    <form method="POST" action="{{ route('profile.password') }}"
                        class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden">
                        @csrf
                        <div class="p-6 sm:p-8 space-y-6">

                            {{-- Password Atual --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password atual</label>
                                <div x-data="{ show: false }" class="relative mt-1">
                                    <input :type="show ? 'text' : 'password'" name="current_password"
                                        class="block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm border">
                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-3 text-xs font-bold uppercase text-gray-500"
                                        x-text="show ? 'Ocultar' : 'Ver'"></button>
                                </div>
                                @error('current_password')
                                    <p class="text-red-600 text-xs mt-2 font-bold">ERRO: {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nova Password --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nova password</label>
                                <div x-data="{ show: false }" class="relative mt-1">
                                    <input :type="show ? 'text' : 'password'" name="new_password"
                                        class="block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm border">
                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-3 text-xs font-bold uppercase text-gray-500"
                                        x-text="show ? 'Ocultar' : 'Ver'"></button>
                                </div>
                                @error('new_password')
                                    <p class="text-red-600 text-xs mt-2 font-bold">ERRO: {{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirmar Nova Password --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirmar nova password</label>
                                <div x-data="{ show: false }" class="relative mt-1">
                                    <input :type="show ? 'text' : 'password'" name="new_password_confirmation"
                                        class="block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm border">
                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-3 text-xs font-bold uppercase text-gray-500"
                                        x-text="show ? 'Ocultar' : 'Ver'"></button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                            <button type="submit"
                                class="bg-black text-white font-semibold py-2 px-6 rounded-md text-sm">Atualizar
                                password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
