<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
.header { text-align: center; border-bottom: 2px solid #7c3aed; padding-bottom: 1rem; margin-bottom: 1.5rem; }
.logo { color: #7c3aed; font-size: 1.5rem; font-weight: bold; }
table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
th { background: #f3f0ff; color: #7c3aed; padding: 0.5rem; text-align: left; font-size: 11px; text-transform: uppercase; }
td { padding: 0.5rem; border-bottom: 1px solid #e5e7eb; }
.total { font-weight: bold; font-size: 14px; }
</style></head>
<body>
<div class="header">
    <div class="logo">FunShirt</div>
    <div style="color:#666;font-size:11px;margin-top:0.25rem;">Recibo de compra</div>
</div>
<div style="display:flex;justify-content:space-between;margin-bottom:1.5rem;">
    <div>
        <strong>Cliente:</strong> {{ $order->customer->user->name }}<br>
        <strong>NIF:</strong> {{ $order->nif ?: '—' }}<br>
        <strong>Email:</strong> {{ $order->customer->user->email }}
    </div>
    <div style="text-align:right">
        <strong>Encomenda:</strong> #{{ $order->id }}<br>
        <strong>Data:</strong> {{ $order->date->format('d/m/Y') }}<br>
        <strong>Estado:</strong> Fechada
    </div>
</div>
<div style="margin-bottom:1rem;"><strong>Morada de entrega:</strong> {{ $order->address }}</div>
<table>
    <tr><th>Design</th><th>Cor</th><th>Tam.</th><th>Qtd</th><th>Preço unit.</th><th>Subtotal</th></tr>
    @foreach($order->items as $item)
    <tr>
        <td>{{ $item->tshirtImage?->name ?? '—' }}</td>
        <td>{{ $item->color?->name ?? $item->color_code }}</td>
        <td>{{ $item->size }}</td>
        <td>{{ $item->qty }}</td>
        <td>€{{ number_format($item->unit_price, 2) }}</td>
        <td>€{{ number_format($item->sub_total, 2) }}</td>
    </tr>
    @endforeach
    <tr>
        <td colspan="5" style="text-align:right" class="total">Total:</td>
        <td class="total" style="color:#7c3aed;">€{{ number_format($order->total_price, 2) }}</td>
    </tr>
</table>
<p style="color:#666;font-size:10px;text-align:center;margin-top:2rem;">FunShirt — Obrigado pela tua compra!</p>
</body>
</html>
