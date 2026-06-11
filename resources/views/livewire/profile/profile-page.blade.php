<div style="max-width:1100px; margin:2.5rem auto; padding:0 1.5rem;">

    {{-- Page Title --}}
    <h1 style="color:#1a1a1a; font-size:1.75rem; font-weight:700; margin-bottom:2rem;">O meu perfil</h1>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div style="background:rgba(34,197,94,.08); border:1px solid rgba(22,163,74,.25); color:#16a34a; padding:0.85rem 1.25rem; border-radius:1px; margin-bottom:1.5rem; font-size:0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('passwordSuccess'))
        <div style="background:rgba(34,197,94,.08); border:1px solid rgba(22,163,74,.25); color:#16a34a; padding:0.85rem 1.25rem; border-radius:1px; margin-bottom:1.5rem; font-size:0.9rem;">
            {{ session('passwordSuccess') }}
        </div>
    @endif

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; align-items:start;">

        {{-- LEFT CARD: Profile Info --}}
        <div style="background:#ffffff; border:1px solid #e0ddd8; border-radius:2px; padding:2rem;">
            <h2 style="color:#1a1a1a; font-size:1.1rem; font-weight:600; margin-bottom:1.5rem; padding-bottom:0.75rem; border-bottom:1px solid #e0ddd8;">
                Informações pessoais
            </h2>

            {{-- Avatar --}}
            <div style="display:flex; align-items:center; gap:1.25rem; margin-bottom:1.75rem;">
                @if (Auth::user()->photo_url)
                    <img src="{{ str_contains(Auth::user()->photo_url, '/') ? asset('storage/' . Auth::user()->photo_url) : asset('storage/photos/' . Auth::user()->photo_url) }}"
                         alt="Foto de perfil"
                         style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:2px solid #7c6fa0; flex-shrink:0;">
                @else
                    <div style="width:72px; height:72px; border-radius:50%; background:#7c6fa0; display:flex; align-items:center; justify-content:center; font-size:1.75rem; font-weight:700; color:#fff; flex-shrink:0;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <label style="display:inline-block; cursor:pointer; background:#e0ddd8; border:1px solid #d8d5d0; color:#7c6fa0; padding:0.45rem 1rem; border-radius:1px; font-size:0.85rem; font-weight:500;"
                           onmouseover="this.style.background='#d8d5d0'" onmouseout="this.style.background='#e0ddd8'">
                        Alterar foto
                        <input type="file" wire:model="photo" accept="image/*" style="display:none;">
                    </label>
                    @if ($photo)
                        <p style="color:#888; font-size:0.78rem; margin-top:0.4rem; margin-bottom:0;">Nova foto selecionada</p>
                    @endif
                    @error('photo')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.4rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <form wire:submit="saveProfile">
                {{-- Name --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Nome *</label>
                    <input type="text" wire:model="name"
                           style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'">
                    @error('name')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Email *</label>
                    <input type="email" wire:model="email"
                           style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'">
                    @error('email')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gender --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.6rem;">Género</label>
                    <div style="display:flex; gap:1.5rem;">
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:#1a1a1a; font-size:0.9rem;">
                            <input type="radio" wire:model="gender" value="M"
                                   style="accent-color:#7c6fa0; width:1rem; height:1rem;">
                            Masculino
                        </label>
                        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:#1a1a1a; font-size:0.9rem;">
                            <input type="radio" wire:model="gender" value="F"
                                   style="accent-color:#7c6fa0; width:1rem; height:1rem;">
                            Feminino
                        </label>
                    </div>
                    @error('gender')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIF --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">NIF</label>
                    <input type="text" wire:model="nif" maxlength="9" placeholder="123456789"
                           style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'">
                    @error('nif')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Address --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Morada</label>
                    <textarea wire:model="address" rows="3" placeholder="Rua, código postal, cidade"
                              style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; resize:vertical; box-sizing:border-box;"
                              onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'"></textarea>
                    @error('address')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default Payment Type --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Método de pagamento preferido</label>
                    <select wire:model="defaultPaymentType"
                            style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; box-sizing:border-box;"
                            onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'">
                        <option value="">— Nenhum —</option>
                        <option value="Visa">Visa</option>
                        <option value="PayPal">PayPal</option>
                        <option value="MB WAY">MB WAY</option>
                    </select>
                    @error('defaultPaymentType')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default Payment Ref --}}
                <div style="margin-bottom:1.75rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Referência de pagamento</label>
                    <input type="text" wire:model="defaultPaymentRef" placeholder="Número de cartão, email PayPal, telemóvel..."
                           style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'">
                    @error('defaultPaymentRef')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        style="width:100%; background:#7c6fa0; color:#fff; padding:0.7rem 1.5rem; border-radius:1px; font-size:0.9rem; font-weight:600; border:none; cursor:pointer;"
                        onmouseover="this.style.background='#6b5f90'" onmouseout="this.style.background='#7c6fa0'">
                    Guardar perfil
                </button>
            </form>
        </div>

        {{-- RIGHT CARD: Password Change --}}
        <div style="background:#ffffff; border:1px solid #e0ddd8; border-radius:2px; padding:2rem;">
            <h2 style="color:#1a1a1a; font-size:1.1rem; font-weight:600; margin-bottom:1.5rem; padding-bottom:0.75rem; border-bottom:1px solid #e0ddd8;">
                Alterar password
            </h2>

            <form wire:submit="changePassword">
                {{-- Current Password --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Password atual *</label>
                    <input type="password" wire:model="currentPassword"
                           style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'">
                    @error('currentPassword')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Nova password *</label>
                    <input type="password" wire:model="newPassword"
                           style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'">
                    @error('newPassword')
                        <p style="color:#f87171; font-size:0.78rem; margin-top:0.3rem; margin-bottom:0;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm New Password --}}
                <div style="margin-bottom:1.75rem;">
                    <label style="display:block; color:#888; font-size:0.82rem; font-weight:500; margin-bottom:0.4rem;">Confirmar nova password *</label>
                    <input type="password" wire:model="newPasswordConfirmation"
                           style="width:100%; background:#ffffff; border:1px solid #e0ddd8; color:#1a1a1a; padding:0.6rem 0.85rem; border-radius:1px; font-size:0.9rem; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='#7c6fa0'" onblur="this.style.borderColor='#e0ddd8'">
                </div>

                <button type="submit"
                        style="width:100%; background:#7c6fa0; color:#fff; padding:0.7rem 1.5rem; border-radius:1px; font-size:0.9rem; font-weight:600; border:none; cursor:pointer;"
                        onmouseover="this.style.background='#6b5f90'" onmouseout="this.style.background='#7c6fa0'">
                    Alterar password
                </button>
            </form>
        </div>

    </div>
</div>
