<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            background: #ffffff;
            padding: 2.5rem;
        }

        .header {
            text-align: center;
            padding-bottom: 1.25rem;
            margin-bottom: 1.75rem;
            border-bottom: 1.5px solid #e0ddd8;
        }

        .logo {
            color: #1a1a1a;
            font-size: 1.4rem;
            font-weight: bold;
        }

        .subtitle {
            color: #888888;
            font-size: 10px;
            margin-top: 0.25rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .meta-table td {
            vertical-align: top;
            padding: 0;
            color: #555555;
            line-height: 1.7;
        }

        .meta-table .right {
            text-align: right;
        }

        .meta-table strong {
            color: #1a1a1a;
        }

        .address {
            color: #555555;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e0ddd8;
        }

        .address strong {
            color: #1a1a1a;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        table.items thead tr {
            background: #eeecea;
        }

        table.items th {
            padding: 0.55rem 0.5rem;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #888888;
            font-weight: 700;
            border-bottom: 1px solid #e0ddd8;
        }

        table.items td {
            padding: 0.55rem 0.5rem;
            border-bottom: 1px solid #e0ddd8;
            color: #555555;
            vertical-align: middle;
        }

        table.items .total-row td {
            font-weight: bold;
            color: #1a1a1a;
            font-size: 13px;
            border-top: 1.5px solid #e0ddd8;
            border-bottom: none;
            padding-top: 0.65rem;
        }

        .total-amount {
            color: #7c6fa0;
        }

        .footer {
            color: #888888;
            font-size: 10px;
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e0ddd8;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo">FunShirt</div>
        <div class="subtitle">Recibo de compra</div>
    </div>

    <table class="meta-table">
        <tr>
            <td>
                <strong>Cliente:</strong> {{ $order->customer->user->name }}<br>
                <strong>NIF:</strong> {{ $order->nif ?: '—' }}<br>
                <strong>Email:</strong> {{ $order->customer->user->email }}
            </td>
            <td class="right">
                <strong>Encomenda:</strong> #{{ $order->id }}<br>
                <strong>Data:</strong> {{ $order->date->format('d/m/Y') }}<br>
                <strong>Estado:</strong> Fechada
            </td>
        </tr>
    </table>

    <div class="address">
        <strong>Morada de entrega:</strong> {{ $order->address }}
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:52px;">Imagem</th>
                <th>Design</th>
                <th>Cor</th>
                <th>Tam.</th>
                <th>Qtd</th>
                <th>Preço unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($preparedItems as $data)
                <tr>
                    <td>
                        @if ($data['hasImage'])
                            <img src="{{ $data['imgSrc'] }}" width="44" height="44"
                                style="width:44px;height:44px;object-fit:cover;border-radius:3px;border:1px solid #e0ddd8;display:block;">
                        @else
                            <div
                                style="width:44px;height:44px;background:#eeecea;border-radius:3px;border:1px solid #e0ddd8;">
                            </div>
                        @endif
                    </td>
                    <td>{{ $data['item']->tshirtImage?->name ?? '—' }}</td>
                    <td>{{ $data['item']->color?->name ?? $data['item']->color_code }}</td>
                    <td>{{ strtoupper($data['item']->size) }}</td>
                    <td>{{ $data['item']->qty }}</td>
                    <td>€{{ number_format($data['item']->unit_price, 2) }}</td>
                    <td>€{{ number_format($data['item']->sub_total, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" style="text-align:right;">Total:</td>
                <td class="total-amount">€{{ number_format($order->total_price, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">FunShirt — Obrigado pela tua compra!</div>

</body>

</html>
