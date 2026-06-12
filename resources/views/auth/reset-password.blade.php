<x-layouts.auth :title="__('Nova password')">
    <div style="margin-bottom:2rem;">
        <div style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.9rem;">Recuperação</div>
        <h1 style="font-size:1.9rem;font-weight:300;letter-spacing:-.03em;line-height:1.1;color:#1a1a1a;">Nova<br><em style="font-style:italic;font-weight:700;">password.</em></h1>
    </div>

    <div style="height:1px;background:#e0ddd8;margin-bottom:1.75rem;"></div>

    <form method="POST" action="{{ route('password.update') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label class="auth-label">E-mail</label>
            <input name="email" type="email" value="{{ old('email', $request->email) }}" required
                   class="auth-input {{ $errors->has('email') ? 'is-error' : '' }}">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="auth-label">Nova password</label>
            <input name="password" type="password" required autocomplete="new-password"
                   class="auth-input {{ $errors->has('password') ? 'is-error' : '' }}"
                   placeholder="Min. 8 caracteres">
            @error('password') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="auth-label">Confirmar password</label>
            <input name="password_confirmation" type="password" required autocomplete="new-password"
                   class="auth-input"
                   placeholder="••••••••">
        </div>

        <div style="margin-top:.5rem;">
            <button type="submit" class="auth-btn auth-btn-full">Guardar password</button>
        </div>
    </form>
</x-layouts.auth>
