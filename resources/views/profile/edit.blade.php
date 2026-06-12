@extends('layouts.app', ['title' => 'O meu perfil'])

@section('content')
<div class="max-w-[1100px] mx-auto my-10 px-6">

    {{-- Page Title --}}
    <h1 class="text-fs-dark text-[1.75rem] font-bold mb-8">O meu perfil</h1>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-green-500/[0.08] border border-green-600/25 text-green-600 py-[0.85rem] px-5 rounded-[1px] mb-6 text-[0.9rem]">
            {{ session('success') }}
        </div>
    @endif

    @if (session('passwordSuccess'))
        <div class="bg-green-500/[0.08] border border-green-600/25 text-green-600 py-[0.85rem] px-5 rounded-[1px] mb-6 text-[0.9rem]">
            {{ session('passwordSuccess') }}
        </div>
    @endif

    <div class="grid grid-cols-2 gap-6 items-start">

        {{-- LEFT CARD: Profile Info --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-8">
            <h2 class="text-fs-dark text-[1.1rem] font-semibold mb-6 pb-3 border-b border-fs-border">
                Informações pessoais
            </h2>

            {{-- Avatar --}}
            <div class="flex items-center gap-5 mb-7">
                @if($user->photo_url)
                    @php $photoSrc = str_contains($user->photo_url, '/') ? asset('storage/' . $user->photo_url) : asset('storage/photos/' . $user->photo_url); @endphp
                    <img
                        src="{{ $photoSrc }}"
                        alt="Foto de perfil"
                        class="w-[72px] h-[72px] rounded-full object-cover border-2 border-fs-purple shrink-0"
                    >
                @else
                    <div class="w-[72px] h-[72px] rounded-full bg-fs-purple flex items-center justify-center text-[1.75rem] font-bold text-white shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <label class="inline-block cursor-pointer bg-fs-border border border-[#d8d5d0] text-fs-purple py-[0.45rem] px-4 rounded-[1px] text-[0.85rem] font-medium hover:bg-[#d8d5d0] transition-colors duration-150">
                        Alterar foto
                        <input type="file" name="photo" form="profile-form" accept="image/*" class="hidden">
                    </label>
                </div>
            </div>

            <form
                id="profile-form"
                method="POST"
                action="{{ route('profile.update') }}"
                enctype="multipart/form-data"
            >
                @csrf

                {{-- Name --}}
                <div class="mb-5">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">Nome *</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none box-border focus:border-fs-purple transition-colors duration-150"
                    >
                    @error('name')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-5">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">Email *</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none box-border focus:border-fs-purple transition-colors duration-150"
                    >
                    @error('email')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gender --}}
                <div class="mb-5">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.6rem]">Género</label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer text-fs-dark text-[0.9rem]">
                            <input
                                type="radio"
                                name="gender"
                                value="M"
                                {{ old('gender', $user->gender) === 'M' ? 'checked' : '' }}
                                class="accent-[#7c6fa0] w-4 h-4"
                            >
                            Masculino
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-fs-dark text-[0.9rem]">
                            <input
                                type="radio"
                                name="gender"
                                value="F"
                                {{ old('gender', $user->gender) === 'F' ? 'checked' : '' }}
                                class="accent-[#7c6fa0] w-4 h-4"
                            >
                            Feminino
                        </label>
                    </div>
                    @error('gender')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIF --}}
                <div class="mb-5">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">NIF</label>
                    <input
                        type="text"
                        name="nif"
                        value="{{ old('nif', $user->customer->nif ?? '') }}"
                        maxlength="9"
                        placeholder="123456789"
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none box-border focus:border-fs-purple transition-colors duration-150"
                    >
                    @error('nif')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div class="mb-5">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">Morada</label>
                    <textarea
                        name="address"
                        rows="3"
                        placeholder="Rua, código postal, cidade"
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none resize-y box-border focus:border-fs-purple transition-colors duration-150"
                    >{{ old('address', $user->customer->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default Payment Type --}}
                <div class="mb-5">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">Método de pagamento preferido</label>
                    <select
                        name="default_payment_type"
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none box-border focus:border-fs-purple transition-colors duration-150"
                    >
                        <option value="">— Nenhum —</option>
                        <option value="Visa" {{ old('default_payment_type', $user->customer->default_payment_type ?? '') === 'Visa' ? 'selected' : '' }}>Visa</option>
                        <option value="PayPal" {{ old('default_payment_type', $user->customer->default_payment_type ?? '') === 'PayPal' ? 'selected' : '' }}>PayPal</option>
                        <option value="MB WAY" {{ old('default_payment_type', $user->customer->default_payment_type ?? '') === 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                    </select>
                    @error('default_payment_type')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default Payment Ref --}}
                <div class="mb-7">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">Referência de pagamento</label>
                    <input
                        type="text"
                        name="default_payment_ref"
                        value="{{ old('default_payment_ref', $user->customer->default_payment_ref ?? '') }}"
                        placeholder="Número de cartão, email PayPal, telemóvel..."
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none box-border focus:border-fs-purple transition-colors duration-150"
                    >
                    @error('default_payment_ref')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-fs-purple text-white py-[0.7rem] px-6 rounded-[1px] text-[0.9rem] font-semibold border-0 cursor-pointer hover:bg-[#6b5f90] transition-colors duration-150"
                >
                    Guardar perfil
                </button>
            </form>
        </div>

        {{-- RIGHT CARD: Password Change --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-8">
            <h2 class="text-fs-dark text-[1.1rem] font-semibold mb-6 pb-3 border-b border-fs-border">
                Alterar password
            </h2>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf

                {{-- Current Password --}}
                <div class="mb-5">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">Password atual *</label>
                    <input
                        type="password"
                        name="current_password"
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none box-border focus:border-fs-purple transition-colors duration-150"
                    >
                    @error('current_password')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-5">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">Nova password *</label>
                    <input
                        type="password"
                        name="password"
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none box-border focus:border-fs-purple transition-colors duration-150"
                    >
                    @error('password')
                        <p class="text-red-400 text-[0.78rem] mt-[0.3rem] mb-0">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div class="mb-7">
                    <label class="block text-fs-gray text-[0.82rem] font-medium mb-[0.4rem]">Confirmar nova password *</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="w-full bg-white border border-fs-border text-fs-dark py-[0.6rem] px-[0.85rem] rounded-[1px] text-[0.9rem] outline-none box-border focus:border-fs-purple transition-colors duration-150"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-fs-purple text-white py-[0.7rem] px-6 rounded-[1px] text-[0.9rem] font-semibold border-0 cursor-pointer hover:bg-[#6b5f90] transition-colors duration-150"
                >
                    Alterar password
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
