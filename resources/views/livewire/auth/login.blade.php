<x-layouts.auth :title="__('Entrar')">
    <h2 style="color:#e2e8f0;font-size:1.3rem;font-weight:600;margin:0 0 0.5rem;">Entrar na conta</h2>
    <p style="color:#64748b;font-size:0.85rem;margin:0 0 1.5rem;">Insere o teu email e password para entrar</p>

    @if (session('status'))
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#4ade80;padding:0.75rem;border-radius:6px;margin-bottom:1rem;font-size:0.85rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" style="display:flex;flex-direction:column;gap:1rem;">
        @csrf

        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   style="width:100%;background:#1a1a2e;border:1px solid {{ $errors->has('email') ? '#ef4444' : '#1e1e30' }};border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;"
                   placeholder="email@exemplo.pt"
                   onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='{{ $errors->has('email') ? '#ef4444' : '#1e1e30' }}'">
            @error('email') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
        </div>

        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
                <label style="color:#94a3b8;font-size:0.8rem;font-weight:500;">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color:#a78bfa;font-size:0.75rem;text-decoration:none;">Esqueceste?</a>
                @endif
            </div>
            <input name="password" type="password" required autocomplete="current-password"
                   style="width:100%;background:#1a1a2e;border:1px solid {{ $errors->has('password') ? '#ef4444' : '#1e1e30' }};border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;"
                   placeholder="••••••••"
                   onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='{{ $errors->has('password') ? '#ef4444' : '#1e1e30' }}'">
            @error('password') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                style="width:100%;background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.7rem;font-size:0.9rem;font-weight:600;cursor:pointer;transition:background .2s;"
                onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
            Entrar
        </button>
    </form>

    <p style="color:#64748b;font-size:0.85rem;text-align:center;margin-top:1.5rem;">
        Não tens conta?
        <a href="{{ route('register') }}" style="color:#a78bfa;text-decoration:none;">Registar</a>
    </p>
</x-layouts.auth>
