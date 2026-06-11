<x-layouts.auth :title="__('Verificar e-mail')">
    <div style="margin-bottom:2rem;">
        <div style="font-size:.62rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#b8b4ae;margin-bottom:.9rem;">Verificação</div>
        <h1 style="font-size:1.9rem;font-weight:300;letter-spacing:-.03em;line-height:1.1;color:#1a1a1a;">Confirma o<br><em style="font-style:italic;font-weight:700;">teu e-mail.</em></h1>
    </div>

    <div style="height:1px;background:#e0ddd8;margin-bottom:1.75rem;"></div>

    <p style="color:#888;font-size:.84rem;line-height:1.55;margin-bottom:1.5rem;">
        Enviámos um link de verificação para o teu endereço de e-mail. Verifica a caixa de entrada.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-status-ok" style="margin-bottom:1.5rem;">Um novo link foi enviado para o teu e-mail.</div>
    @endif

    <div style="display:flex;flex-direction:column;gap:.75rem;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-btn auth-btn-full">Reenviar e-mail</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-btn-ghost auth-btn-full" style="width:100%;">Logout</button>
        </form>
    </div>
</x-layouts.auth>
