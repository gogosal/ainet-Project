<!DOCTYPE html><html><body style="font-family:sans-serif;background:#f9f9f9;padding:2rem;">
<div style="max-width:600px;margin:0 auto;background:white;border-radius:8px;padding:2rem;">
    <h2 style="color:#ef4444;">FunShirt — Encomenda anulada</h2>
    <p>Olá {{ $order->customer->user->name }},</p>
    <p>A tua encomenda <strong>#{{ $order->id }}</strong> foi anulada.</p>
    @if($order->reason_for_cancellation)
    <p>Motivo: {{ $order->reason_for_cancellation }}</p>
    @endif
    <p>Se tiveres dúvidas, contacta-nos.</p>
</div>
</body></html>
