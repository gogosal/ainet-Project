<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Password — FunShirt</title>
</head>
<body style="margin:0;padding:0;background:#0d0d1a;font-family:'Segoe UI',Arial,sans-serif;">
    <div style="max-width:560px;margin:2rem auto;background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;">
        <!-- Header -->
        <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);padding:2rem;text-align:center;">
            <h1 style="color:#ffffff;margin:0;font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">FunShirt</h1>
            <p style="color:rgba(255,255,255,.75);margin:0.35rem 0 0;font-size:0.9rem;">A tua loja de t-shirts personalizadas</p>
        </div>

        <!-- Body -->
        <div style="padding:2rem;">
            <h2 style="color:#e2e8f0;font-size:1.2rem;font-weight:600;margin:0 0 0.75rem;">Recupera a tua password</h2>
            <p style="color:#94a3b8;font-size:0.9rem;line-height:1.6;margin:0 0 1.5rem;">
                Olá <strong style="color:#e2e8f0;">{{ $name }}</strong>,<br><br>
                Recebemos um pedido para redefinir a password da tua conta FunShirt. Clica no botão abaixo para criar uma nova password.
            </p>

            <div style="text-align:center;margin:1.5rem 0;">
                <a href="{{ $url }}"
                   style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#ffffff;text-decoration:none;border-radius:8px;padding:0.8rem 2rem;font-size:0.95rem;font-weight:600;letter-spacing:0.01em;">
                    Redefinir password
                </a>
            </div>

            <p style="color:#64748b;font-size:0.8rem;line-height:1.5;margin:1.5rem 0 0;">
                Este link expira em <strong>60 minutos</strong>. Após expirar, terás de solicitar um novo link.<br><br>
                Se não pediste a recuperação de password, podes ignorar este email. A tua password não será alterada.<br><br>
                Em alternativa, copia e cola este link no browser:<br>
                <a href="{{ $url }}" style="color:#a78bfa;word-break:break-all;">{{ $url }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div style="border-top:1px solid #1e1e30;padding:1rem 2rem;text-align:center;">
            <p style="color:#475569;font-size:0.75rem;margin:0;">© {{ date('Y') }} FunShirt. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>
