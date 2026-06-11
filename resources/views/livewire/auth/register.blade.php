<x-layouts.auth :title="__('Criar conta')">
    <div style="margin-bottom:2rem;">
        <div style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.9rem;">Nova conta</div>
        <h1 style="font-size:1.9rem;font-weight:300;letter-spacing:-.03em;line-height:1.1;color:#1a1a1a;">Junta-te à<br><em style="font-style:italic;font-weight:700;">Funshirt.</em></h1>
    </div>

    <div style="height:1px;background:#e0ddd8;margin-bottom:1.75rem;"></div>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
        @csrf

        <div>
            <label class="auth-label">Nome completo</label>
            <input name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="auth-input {{ $errors->has('name') ? 'is-error' : '' }}"
                   placeholder="Maria Silva">
            @error('name') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="auth-label">E-mail</label>
            <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                   class="auth-input {{ $errors->has('email') ? 'is-error' : '' }}"
                   placeholder="email@exemplo.pt">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label class="auth-label">Password</label>
                <input name="password" type="password" required autocomplete="new-password"
                       class="auth-input {{ $errors->has('password') ? 'is-error' : '' }}"
                       placeholder="Min. 8 caracteres">
                @error('password') <p class="auth-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="auth-label">Confirmar</label>
                <input name="password_confirmation" type="password" required autocomplete="new-password"
                       class="auth-input"
                       placeholder="••••••••">
            </div>
        </div>

        <div>
            <label class="auth-label">Género</label>
            <select name="gender" required class="auth-input {{ $errors->has('gender') ? 'is-error' : '' }}">
                <option value="">Selecionar</option>
                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Feminino</option>
            </select>
            @error('gender') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:.5rem;">
            <button type="submit" class="auth-btn">Criar conta</button>
            <a href="{{ route('login') }}" class="auth-link">Já tens conta →</a>
        </div>
    </form>
</x-layouts.auth>
