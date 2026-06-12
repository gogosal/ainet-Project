<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encomenda recebida — FunShirt</title>
</head>
<body style="margin:0;padding:0;background:#f5f4f1;font-family:'Segoe UI',Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f4f1;padding:2rem 1rem;">
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border:1px solid #e0ddd8;border-radius:10px;overflow:hidden;">

                    <!-- Header -->
                    <tr>
                        <td style="background:#1a1a1a;padding:1.5rem 2rem;text-align:center;">
                            <p style="margin:0;font-size:1.5rem;font-weight:700;color:#ffffff;letter-spacing:-0.02em;">FunShirt</p>
                            <p style="margin:0.3rem 0 0;font-size:0.75rem;color:#b8b4ae;letter-spacing:0.06em;text-transform:uppercase;">A tua loja de t-shirts personalizadas</p>
                        </td>
                    </tr>

                    <!-- Status badge -->
                    <tr>
                        <td style="background:#eeecea;padding:0.75rem 2rem;text-align:center;border-bottom:1px solid #e0ddd8;">
                            <span style="display:inline-block;background:#7c6fa0;color:#ffffff;font-size:0.72rem;font-weight:600;padding:0.3rem 1rem;border-radius:20px;letter-spacing:0.06em;text-transform:uppercase;">Encomenda recebida</span>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:2rem;">
                            <p style="margin:0 0 0.75rem;color:#1a1a1a;font-size:0.95rem;line-height:1.6;">
                                Olá <strong>{{ $order->customer->user->name }}</strong>,
                            </p>
                            <p style="margin:0 0 1.5rem;color:#555555;font-size:0.9rem;line-height:1.6;">
                                Recebemos a tua encomenda <strong style="color:#1a1a1a;">#{{ $order->id }}</strong> no valor total de <strong style="color:#1a1a1a;">€{{ number_format($order->total_price, 2) }}</strong>. Estamos a processar o teu pedido e receberás um email assim que for enviado.
                            </p>

                            <!-- Items -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0ddd8;border-radius:8px;overflow:hidden;margin-bottom:1.5rem;">
                                <tr>
                                    <td colspan="3" style="background:#eeecea;padding:0.75rem 1rem;border-bottom:1px solid #e0ddd8;">
                                        <p style="margin:0;font-size:0.72rem;font-weight:600;color:#888888;text-transform:uppercase;letter-spacing:0.06em;">Artigos</p>
                                    </td>
                                </tr>
                                @foreach($order->items as $item)
                                @php
                                    $imgPath = null;
                                    if ($item->tshirtImage) {
                                        $url = $item->tshirtImage->image_url;
                                        if (str_starts_with($url, 'tshirt_images_private')) {
                                            $imgPath = storage_path('app/private/' . $url);
                                        } elseif (str_contains($url, '/')) {
                                            $imgPath = storage_path('app/public/' . $url);
                                        } else {
                                            $imgPath = storage_path('app/public/tshirt_images/' . basename($url));
                                        }
                                    }
                                @endphp
                                <tr style="border-bottom:1px solid #e0ddd8;">
                                    @if($imgPath && file_exists($imgPath))
                                    <td width="72" style="padding:0.75rem 0 0.75rem 1rem;vertical-align:middle;">
                                        <img src="data:image/{{ strtolower(pathinfo($imgPath, PATHINFO_EXTENSION)) }};base64,{{ base64_encode(file_get_contents($imgPath)) }}"
                                             width="60" height="60"
                                             style="width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #e0ddd8;display:block;">
                                    </td>
                                    @endif
                                    <td style="padding:0.75rem 0.75rem;vertical-align:middle;">
                                        <p style="margin:0;font-size:0.9rem;font-weight:600;color:#1a1a1a;">{{ $item->tshirtImage->name ?? 'T-shirt' }}</p>
                                        <p style="margin:0.2rem 0 0;font-size:0.78rem;color:#888888;">Cor: {{ $item->color->name ?? $item->color_code }} &nbsp;·&nbsp; Tamanho: {{ strtoupper($item->size) }} &nbsp;·&nbsp; Qtd: {{ $item->qty }}</p>
                                    </td>
                                    <td style="padding:0.75rem 1rem 0.75rem 0;vertical-align:middle;text-align:right;">
                                        <p style="margin:0;font-size:0.9rem;font-weight:600;color:#1a1a1a;">€{{ number_format($item->sub_total, 2) }}</p>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" style="padding:0.75rem 1rem;text-align:right;">
                                        <p style="margin:0;font-size:0.85rem;color:#555555;">Total: <strong style="color:#1a1a1a;font-size:0.95rem;">€{{ number_format($order->total_price, 2) }}</strong></p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0;color:#888888;font-size:0.82rem;line-height:1.6;">
                                Obrigado por comprares na FunShirt! Se tiveres alguma questão, responde a este email.
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
