<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Email — FunShirt</title>
</head>
<body style="margin:0;padding:0;background:#f5f4f1;font-family:'Segoe UI',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f4f1;padding:2rem 1rem;">
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;background:#ffffff;border:1px solid #e0ddd8;border-radius:10px;overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background:#1a1a1a;padding:1.5rem 2rem;text-align:center;">
                            <p style="margin:0;font-size:1.5rem;font-weight:700;color:#ffffff;letter-spacing:-0.02em;">FunShirt</p>
                            <p style="margin:0.3rem 0 0;font-size:0.75rem;color:#b8b4ae;letter-spacing:0.06em;text-transform:uppercase;">A tua loja de t-shirts personalizadas</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:2rem;">
                            <h2 style="margin:0 0 0.75rem;font-size:1.15rem;font-weight:600;color:#1a1a1a;">Confirma o teu email</h2>
                            <p style="margin:0 0 1.5rem;color:#555555;font-size:0.9rem;line-height:1.6;">
                                Olá <strong style="color:#1a1a1a;">{{ $name }}</strong>,<br><br>
                                Obrigado por te registares na FunShirt! Clica no botão abaixo para confirmar o teu endereço de email e activar a tua conta.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin:1.5rem 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}"
                                           style="display:inline-block;background:#1a1a1a;color:#ffffff;text-decoration:none;border-radius:8px;padding:0.8rem 2rem;font-size:0.9rem;font-weight:600;letter-spacing:0.01em;">
                                            Verificar email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:1.5rem 0 0;color:#888888;font-size:0.8rem;line-height:1.6;">
                                Se não criaste uma conta, podes ignorar este email.<br>
                                Este link expira em <strong style="color:#555555;">60 minutos</strong>.<br><br>
                                Em alternativa, copia e cola este link no browser:<br>
                                <a href="{{ $url }}" style="color:#7c6fa0;word-break:break-all;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#eeecea;border-top:1px solid #e0ddd8;padding:1rem 2rem;text-align:center;">
                            <p style="margin:0;font-size:0.73rem;color:#888888;">© {{ date('Y') }} FunShirt. Todos os direitos reservados.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
