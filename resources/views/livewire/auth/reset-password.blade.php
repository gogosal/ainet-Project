<x-layouts.auth :title="__('Nova password')">
    <h2 style="color:#e2e8f0;font-size:1.3rem;font-weight:600;margin:0 0 1.5rem;">Definir nova password</h2>

    <form method="POST" action="{{ route('password.store') }}" style="display:flex;flex-direction:column;gap:1rem;">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Email</label>
            <input name="email" type="email" value="{{ old('email', $request->email) }}" required
                   style="width:100%;background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;">
            @error('email') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Nova password</label>
            <input name="password" type="password" required autocomplete="new-password"
                   style="width:100%;background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
            @error('password') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
        </div>
        <div>
            <label style="display:block;color:#94a3b8;font-size:0.8rem;font-weight:500;margin-bottom:0.4rem;">Confirmar password</label>
            <input name="password_confirmation" type="password" required
                   style="width:100%;background:#1a1a2e;border:1px solid #1e1e30;border-radius:6px;padding:0.6rem 0.75rem;color:#e2e8f0;font-size:0.9rem;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#7c3aed'" onblur="this.style.borderColor='#1e1e30'">
        </div>
        <button type="submit"
                style="width:100%;background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.7rem;font-size:0.9rem;font-weight:600;cursor:pointer;">
            Guardar nova password
        </button>
    </form>
</x-layouts.auth>
