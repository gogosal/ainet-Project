<!DOCTYPE html><html><body style="font-family:sans-serif;background:#f9f9f9;padding:2rem;">
<div style="max-width:600px;margin:0 auto;background:white;border-radius:8px;padding:2rem;">
    <h2 style="color:#7c3aed;">FunShirt — Encomenda enviada!</h2>
    <p>Olá {{ $order->customer->user->name }},</p>
    <p>A tua encomenda <strong>#{{ $order->id }}</strong> foi processada e enviada.</p>
    <p>Encontras o teu recibo em anexo.</p>
    <p>Obrigado por comprares na FunShirt!</p>
</div>
</body></html>
