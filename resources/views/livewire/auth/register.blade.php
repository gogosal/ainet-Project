<x-layouts.auth :title="__('Criar conta')">
    <h2 style="color:#e2e8f0;font-size:1.3rem;font-weight:600;margin:0 0 0.5rem;">Criar conta</h2>
    <p style="color:#64748b;font-size:0.85rem;margin:0 0 1.5rem;">Regista-te para fazer encomendas</p>

    <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:1rem;">
        @csrf

        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Nome completo</label>
            <input name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                   style="width:100%;background:#1a1a2e;border:1px solid {{ $errors->has('name') ? '#ef4444' : '#1e1e30' }};border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;"
                   placeholder="Maria Silva"
                   onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
            @error('name') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
        </div>

        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                   style="width:100%;background:#1a1a2e;border:1px solid {{ $errors->has('email') ? '#ef4444' : '#1e1e30' }};border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;"
                   placeholder="email@exemplo.pt"
                   onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
            @error('email') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
        </div>

        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Password</label>
            <input name="password" type="password" required autocomplete="new-password"
                   style="width:100%;background:#1a1a2e;border:1px solid {{ $errors->has('password') ? '#ef4444' : '#1e1e30' }};border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;"
                   placeholder="••••••••"
                   onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
            @error('password') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
        </div>

        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Confirmar password</label>
            <input name="password_confirmation" type="password" required autocomplete="new-password"
                   style="width:100%;background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;"
                   placeholder="••••••••"
                   onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
        </div>

        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Género</label>
            <select name="gender" required
                    style="width:100%;background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;">
                <option value="">Selecionar</option>
                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Feminino</option>
            </select>
            @error('gender') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                style="width:100%;background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.7rem;font-size:0.9rem;font-weight:600;cursor:pointer;transition:background .2s;"
                onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
            Criar conta
        </button>
    </form>

    <p style="color:#64748b;font-size:0.85rem;text-align:center;margin-top:1.5rem;">
        Já tens conta?
        <a href="{{ route('login') }}" style="color:#a78bfa;text-decoration:none;">Entrar</a>
    </p>
</x-layouts.auth>
