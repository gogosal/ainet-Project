<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-gray-50/50 min-h-screen">

    {{-- Page Header --}}
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">O meu perfil</h1>
        <p class="mt-2 text-sm text-gray-500">Gere as tuas informações pessoais, moradas e segurança da conta.</p>
    </div>

    {{-- Flash Messages --}}
    @if (session('success') || session('passwordSuccess'))
        <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-md shadow-sm flex items-start">
            <div class="shrink-0">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700 font-medium m-0">
                    {{ session('success') ?? session('passwordSuccess') }}
                </p>
            </div>
        </div>
    @endif

    <div class="space-y-10 sm:space-y-16">

        {{-- SECTION 1: Informações Pessoais --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">

            {{-- Coluna da Esquerda (Explicação) --}}
            <div class="md:col-span-1">
                <h2 class="text-lg font-semibold text-gray-900 leading-6">Dados Pessoais</h2>
                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Atualiza a tua foto e as informações que usamos para faturação e entregas. Mantém o teu NIF e morada
                    atualizados.
                </p>
            </div>

            {{-- Coluna da Direita (Formulário) --}}
            <div class="md:col-span-2">
                <form wire:submit="saveProfile"
                    class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden">
                    <div class="p-6 sm:p-8 space-y-8">

                        {{-- Avatar Section --}}
                        <div class="flex items-center gap-x-6">
                            @if (Auth::user()->photo_url)
                                <img src="{{ str_contains(Auth::user()->photo_url, '/') ? asset('storage/' . Auth::user()->photo_url) : asset('storage/photos/' . Auth::user()->photo_url) }}"
                                    alt="Avatar" class="h-20 w-20 rounded-full object-cover border border-gray-200">
                            @else
                                <div
                                    class="h-20 w-20 rounded-full bg-[#7c6fa0] flex items-center justify-center text-2xl font-bold text-white shadow-inner">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <label
                                    class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#7c6fa0] transition-colors">
                                    <span>Alterar foto</span>
                                    <input type="file" wire:model="photo" accept="image/*" class="sr-only">
                                </label>
                                <p class="mt-2 text-xs text-gray-500">JPG, PNG ou GIF até 2MB.</p>
                                @if ($photo)
                                    <p class="text-[#7c6fa0] text-xs font-semibold mt-1">✓ Nova foto pronta a guardar
                                    </p>
                                @endif
                                @error('photo')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Grelha de Inputs --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            {{-- Nome --}}
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700">Nome <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="name"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" wire:model="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Género --}}
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Género</label>
                                <div class="flex items-center gap-5 mt-1">
                                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                        <input type="radio" wire:model="gender" value="M"
                                            class="text-[#7c6fa0] focus:ring-[#7c6fa0] border-gray-300 cursor-pointer w-4 h-4">
                                        Masculino
                                    </label>
                                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                        <input type="radio" wire:model="gender" value="F"
                                            class="text-[#7c6fa0] focus:ring-[#7c6fa0] border-gray-300 cursor-pointer w-4 h-4">
                                        Feminino
                                    </label>
                                </div>
                                @error('gender')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- NIF --}}
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700">NIF</label>
                                <input type="text" wire:model="nif" maxlength="9" placeholder="Ex: 123456789"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors">
                                @error('nif')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Morada --}}
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Morada de Faturação e
                                    Entrega</label>
                                <textarea wire:model="address" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors resize-y"></textarea>
                                @error('address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Pagamento --}}
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700">Método de pagamento</label>
                                <select wire:model="defaultPaymentType"
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors">
                                    <option value="">— Selecionar —</option>
                                    <option value="Visa">Visa</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="MB WAY">MB WAY</option>
                                </select>
                                @error('defaultPaymentType')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ref Pagamento --}}
                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700">Referência do pagamento</label>
                                <input type="text" wire:model="defaultPaymentRef"
                                    placeholder="Email, nº cartão, telemóvel..."
                                    class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors">
                                @error('defaultPaymentRef')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Rodapé do Formulário (Botão Guardar) --}}
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end border-t border-gray-100">
                        <button type="submit"
                            class="bg-[#1a1a1a] hover:bg-[#333] text-white font-semibold py-2 px-6 rounded-md shadow-sm transition-colors text-sm">
                            Guardar alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Separador Visual --}}
        <div class="hidden sm:block">
            <div class="py-2">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        {{-- SECTION 2: Segurança --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">

            {{-- Coluna da Esquerda (Explicação) --}}
            <div class="md:col-span-1">
                <h2 class="text-lg font-semibold text-gray-900 leading-6">Segurança da Conta</h2>
                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                    Garante que a tua conta está a usar uma password longa e aleatória para se manter segura e
                    protegida.
                </p>
            </div>

            {{-- Coluna da Direita (Formulário) --}}
            <div class="md:col-span-2">
                <form wire:submit="changePassword"
                    class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden">
                    <div class="p-6 sm:p-8 space-y-6">

                        {{-- Password Atual --}}
                        <div class="max-w-md">
                            <label class="block text-sm font-medium text-gray-700">Password atual <span
                                    class="text-red-500">*</span></label>
                            <input type="password" wire:model="currentPassword"
                                class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors">
                            @error('currentPassword')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nova Password --}}
                        <div class="max-w-md">
                            <label class="block text-sm font-medium text-gray-700">Nova password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" wire:model="newPassword"
                                class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors">
                            @error('newPassword')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirmar Nova Password --}}
                        <div class="max-w-md">
                            <label class="block text-sm font-medium text-gray-700">Confirmar nova password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" wire:model="newPasswordConfirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 py-2 px-3 shadow-sm focus:border-[#7c6fa0] focus:ring-[#7c6fa0] sm:text-sm border transition-colors">
                        </div>

                    </div>

                    {{-- Rodapé do Formulário (Botão Atualizar) --}}
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end border-t border-gray-100">
                        <button type="submit"
                            class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-2 px-6 rounded-md shadow-sm transition-colors text-sm">
                            Atualizar password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
