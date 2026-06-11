<x-layouts.auth :title="__('Entrar')">
    <div style="margin-bottom:2.25rem;">
        <div style="font-size:.58rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:#c8c4be;margin-bottom:.75rem;">Área pessoal</div>
        <h1 style="font-size:2rem;font-weight:300;letter-spacing:-.04em;line-height:1.08;color:#1a1a1a;margin:0;">Bem-vindo de <em style="font-style:italic;font-weight:700;">volta.</em></h1>
    </div>

    @if (session('status'))
        <div class="auth-status-ok">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" style="display:flex;flex-direction:column;gap:1.5rem;">
        @csrf

        <div>
            <label class="auth-label">E-mail</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   class="auth-input {{ $errors->has('email') ? 'is-error' : '' }}"
                   placeholder="nome@exemplo.pt">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:.55rem;">
                <label class="auth-label" style="margin-bottom:0;">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link" style="font-size:.72rem;">Esqueceste?</a>
                @endif
            </div>
            <input name="password" type="password" required autocomplete="current-password"
                   class="auth-input {{ $errors->has('password') ? 'is-error' : '' }}"
                   placeholder="••••••••">
            @error('password') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:.25rem;">
            <button type="submit" class="auth-btn">Entrar</button>
            <a href="{{ route('register') }}" class="auth-link" style="font-size:.78rem;">Criar conta →</a>
        </div>
    </form>
</x-layouts.auth>
