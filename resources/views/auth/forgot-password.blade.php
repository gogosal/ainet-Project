<x-layouts.auth :title="__('Recuperar password')">
    <div style="margin-bottom:2rem;">
        <div style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.9rem;">Recuperação</div>
        <h1 style="font-size:1.9rem;font-weight:300;letter-spacing:-.03em;line-height:1.1;color:#1a1a1a;">Esqueceste a<br><em style="font-style:italic;font-weight:700;">password?</em></h1>
    </div>

    <div style="height:1px;background:#e0ddd8;margin-bottom:1.75rem;"></div>

    <p style="color:#888;font-size:.84rem;line-height:1.55;margin-bottom:1.5rem;">Insere o teu e-mail e enviamos um link para definires uma nova password.</p>

    <form method="POST" action="{{ route('password.email') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
        @csrf
        <div>
            <label class="auth-label">E-mail</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="auth-input {{ $errors->has('email') ? 'is-error' : '' }}"
                   placeholder="email@exemplo.pt">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:.5rem;">
            <button type="submit" class="auth-btn">Enviar link</button>
            <a href="{{ route('login') }}" class="auth-link">← Voltar</a>
        </div>
    </form>
</x-layouts.auth>
