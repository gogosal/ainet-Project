<x-layouts.auth :title="__('Criar conta')">
    <div style="margin-bottom:1.75rem;">
        <h2 style="color:#e2e8f0;font-size:1.5rem;font-weight:700;letter-spacing:-0.025em;margin:0 0 0.35rem;">Criar conta</h2>
        <p style="color:#64748b;font-size:0.875rem;margin:0;">Junta-te à FunShirt e começa a personalizar</p>
    </div>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:1rem;">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div style="grid-column:1/-1;">
                <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;margin-bottom:0.4rem;">Nome completo</label>
                <input name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="input-field"
                       style="width:100%;background:#0f0f1d;border:1px solid {{ $errors->has('name') ? '#ef4444' : '#252540' }};border-radius:8px;padding:0.65rem 0.85rem;color:#e2e8f0;font-size:0.9rem;box-sizing:border-box;transition:border-color .15s,box-shadow .15s;"
                       placeholder="Maria Silva">
                @error('name') <p style="color:#f87171;font-size:0.75rem;margin:0.3rem 0 0;">⚠ {{ $message }}</p> @enderror
            </div>

            <div style="grid-column:1/-1;">
                <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;margin-bottom:0.4rem;">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                       class="input-field"
                       style="width:100%;background:#0f0f1d;border:1px solid {{ $errors->has('email') ? '#ef4444' : '#252540' }};border-radius:8px;padding:0.65rem 0.85rem;color:#e2e8f0;font-size:0.9rem;box-sizing:border-box;transition:border-color .15s,box-shadow .15s;"
                       placeholder="email@exemplo.pt">
                @error('email') <p style="color:#f87171;font-size:0.75rem;margin:0.3rem 0 0;">⚠ {{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;margin-bottom:0.4rem;">Password</label>
                <input name="password" type="password" required autocomplete="new-password"
                       class="input-field"
                       style="width:100%;background:#0f0f1d;border:1px solid {{ $errors->has('password') ? '#ef4444' : '#252540' }};border-radius:8px;padding:0.65rem 0.85rem;color:#e2e8f0;font-size:0.9rem;box-sizing:border-box;transition:border-color .15s,box-shadow .15s;"
                       placeholder="Min. 8 caracteres">
                @error('password') <p style="color:#f87171;font-size:0.75rem;margin:0.3rem 0 0;">⚠ {{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;margin-bottom:0.4rem;">Confirmar</label>
                <input name="password_confirmation" type="password" required autocomplete="new-password"
                       class="input-field"
                       style="width:100%;background:#0f0f1d;border:1px solid #252540;border-radius:8px;padding:0.65rem 0.85rem;color:#e2e8f0;font-size:0.9rem;box-sizing:border-box;transition:border-color .15s,box-shadow .15s;"
                       placeholder="••••••••">
            </div>

            <div style="grid-column:1/-1;">
                <label style="display:block;color:#94a3b8;font-size:0.78rem;font-weight:600;letter-spacing:.04em;text-transform:uppercase;margin-bottom:0.4rem;">Género</label>
                <select name="gender" required
                        class="input-field"
                        style="width:100%;background:#0f0f1d;border:1px solid {{ $errors->has('gender') ? '#ef4444' : '#252540' }};border-radius:8px;padding:0.65rem 0.85rem;color:#e2e8f0;font-size:0.9rem;box-sizing:border-box;transition:border-color .15s,box-shadow .15s;cursor:pointer;">
                    <option value="" style="background:#0f0f1d;">Selecionar género</option>
                    <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }} style="background:#0f0f1d;">Masculino</option>
                    <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }} style="background:#0f0f1d;">Feminino</option>
                </select>
                @error('gender') <p style="color:#f87171;font-size:0.75rem;margin:0.3rem 0 0;">⚠ {{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit"
                style="width:100%;background:linear-gradient(135deg,#7c3aed,#5b21b6);color:white;border:none;border-radius:8px;padding:0.75rem;font-size:0.9rem;font-weight:600;cursor:pointer;transition:all .2s;box-shadow:0 4px 16px rgba(124,58,237,.3);letter-spacing:.01em;margin-top:0.25rem;"
                onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 24px rgba(124,58,237,.45)'"
                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 16px rgba(124,58,237,.3)'">
            Criar conta →
        </button>
    </form>

    <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid #1a1a2e;text-align:center;">
        <p style="color:#64748b;font-size:0.85rem;margin:0;">
            Já tens conta?
            <a href="{{ route('login') }}" style="color:#a78bfa;text-decoration:none;font-weight:500;" onmouseover="this.style.color='#e2e8f0'" onmouseout="this.style.color='#a78bfa'">Entrar</a>
        </p>
    </div>
</x-layouts.auth>
