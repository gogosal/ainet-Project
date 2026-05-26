<x-layouts.auth :title="__('Verificar email')">
    <h2 style="color:#e2e8f0;font-size:1.3rem;font-weight:600;margin:0 0 0.5rem;">Verificar email</h2>
    <p style="color:#64748b;font-size:0.85rem;margin:0 0 1.5rem;">
        Enviámos um link de verificação para o teu email. Verifica a caixa de entrada.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#4ade80;padding:0.75rem;border-radius:6px;margin-bottom:1rem;font-size:0.85rem;">
            Um novo link foi enviado para o teu email.
        </div>
    @endif

    <div style="display:flex;gap:0.75rem;flex-direction:column;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    style="width:100%;background:#7c3aed;color:white;border:none;border-radius:6px;padding:0.7rem;font-size:0.9rem;font-weight:600;cursor:pointer;">
                Reenviar email de verificação
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width:100%;background:transparent;color:#64748b;border:1px solid #1e1e30;border-radius:6px;padding:0.7rem;font-size:0.9rem;cursor:pointer;">
                Logout
            </button>
        </form>
    </div>
</x-layouts.auth>
