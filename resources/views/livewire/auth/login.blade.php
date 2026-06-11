<x-layouts.auth :title="__('Entrar')">
    <div style="margin-bottom:2rem;">
        <h2 style="color:#e2e8f0;font-size:1.5rem;font-weight:700;letter-spacing:-0.025em;margin:0 0 0.35rem;">Bem-vindo de volta</h2>
        <p style="color:#64748b;font-size:0.875rem;margin:0;">Entra na tua conta para continuar</p>
    </div>

    @if (session('status'))
        <div style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);color:#4ade80;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.85rem;display:flex;align-items:center;gap:0.5rem;">
            <span>✓</span> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" style="display:flex;flex-direction:column;gap:1.1rem;">
        @csrf

        <div>
            <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;margin-bottom:0.45rem;">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   class="input-field"
                   style="width:100%;background:#0f0f1d;border:1px solid {{ $errors->has('email') ? '#ef4444' : '#252540' }};border-radius:8px;padding:0.65rem 0.85rem;color:#e2e8f0;font-size:0.9rem;box-sizing:border-box;transition:border-color .15s,box-shadow .15s;"
                   placeholder="email@exemplo.pt">
            @error('email') <p style="color:#f87171;font-size:0.75rem;margin:0.35rem 0 0;display:flex;align-items:center;gap:0.3rem;"><span>⚠</span> {{ $message }}</p> @enderror
        </div>

        <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.45rem;">
                <label style="color:#94a3b8;font-size:0.78rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color:#7c3aed;font-size:0.78rem;text-decoration:none;font-weight:500;transition:color .15s;" onmouseover="this.style.color='#a78bfa'" onmouseout="this.style.color='#7c3aed'">Esqueceste?</a>
                @endif
            </div>
            <input name="password" type="password" required autocomplete="current-password"
                   class="input-field"
                   style="width:100%;background:#0f0f1d;border:1px solid {{ $errors->has('password') ? '#ef4444' : '#252540' }};border-radius:8px;padding:0.65rem 0.85rem;color:#e2e8f0;font-size:0.9rem;box-sizing:border-box;transition:border-color .15s,box-shadow .15s;"
                   placeholder="••••••••">
            @error('password') <p style="color:#f87171;font-size:0.75rem;margin:0.35rem 0 0;display:flex;align-items:center;gap:0.3rem;"><span>⚠</span> {{ $message }}</p> @enderror
        </div>

        <button type="submit"
                style="width:100%;background:linear-gradient(135deg,#7c3aed,#5b21b6);color:white;border:none;border-radius:8px;padding:0.75rem;font-size:0.9rem;font-weight:600;cursor:pointer;transition:all .2s;box-shadow:0 4px 16px rgba(124,58,237,.3);letter-spacing:.01em;margin-top:0.25rem;"
                onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 24px rgba(124,58,237,.45)'"
                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 16px rgba(124,58,237,.3)'">
            Entrar →
        </button>
    </form>

    <div style="margin-top:1.75rem;padding-top:1.5rem;border-top:1px solid #1a1a2e;text-align:center;">
        <p style="color:#64748b;font-size:0.85rem;margin:0;">
            Não tens conta?
            <a href="{{ route('register') }}" style="color:#a78bfa;text-decoration:none;font-weight:500;" onmouseover="this.style.color='#e2e8f0'" onmouseout="this.style.color='#a78bfa'">Registar agora</a>
        </p>
    </div>
</x-layouts.auth>
