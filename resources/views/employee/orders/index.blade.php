@extends('layouts.app', ['title' => 'Encomendas Pendentes'])

@section('content')
<div>

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-[0.72rem] font-semibold uppercase tracking-[0.1em] text-fs-muted m-0 mb-1">Funcionário</p>
        <h1 class="text-fs-dark text-[1.5rem] font-bold m-0 mb-1">Encomendas Pendentes</h1>
        <p class="text-fs-gray text-[0.85rem] m-0">Processa e fecha as encomendas após estampagem e envio.</p>
    </div>

    {{-- Empty state --}}
    @if($orders->isEmpty())
        <div class="text-center py-16 px-8 bg-white border border-fs-border rounded-[2px]">
            <p class="text-fs-gray m-0">Não há encomendas pendentes de momento.</p>
        </div>
    @else
        <div class="bg-white border border-fs-border rounded-[2px]" style="overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f0ede8;border-bottom:1px solid #e0ddd8;">
                        <th style="padding:.75rem 1rem;text-align:left;font-size:.75rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:#888;">#</th>
                        <th style="padding:.75rem 1rem;text-align:left;font-size:.75rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:#888;">Cliente</th>
                        <th style="padding:.75rem 1rem;text-align:left;font-size:.75rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:#888;">Data</th>
                        <th style="padding:.75rem 1rem;text-align:left;font-size:.75rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:#888;">Artigos</th>
                        <th style="padding:.75rem 1rem;text-align:right;font-size:.75rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:#888;">Total</th>
                        <th style="padding:.75rem 1rem;text-align:right;font-size:.75rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:#888;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr style="border-bottom:1px solid #e0ddd8;" onmouseover="this.style.background='#faf9f7'" onmouseout="this.style.background='transparent'">
                            <td style="padding:.8rem 1rem;font-size:.85rem;font-weight:600;color:#7c6fa0;">#{{ $order->id }}</td>
                            <td style="padding:.8rem 1rem;">
                                <p style="margin:0;font-size:.85rem;font-weight:500;color:#1a1a1a;">{{ $order->customer->user->name }}</p>
                                <p style="margin:.1rem 0 0;font-size:.75rem;color:#aaa;">{{ $order->customer->user->email }}</p>
                            </td>
                            <td style="padding:.8rem 1rem;font-size:.85rem;color:#888;">{{ $order->date->format('d/m/Y') }}</td>
                            <td style="padding:.8rem 1rem;font-size:.85rem;color:#888;">{{ $order->items->count() }}</td>
                            <td style="padding:.8rem 1rem;font-size:.9rem;font-weight:600;color:#1a1a1a;text-align:right;">€{{ number_format($order->total_price, 2) }}</td>
                            <td style="padding:.8rem 1rem;text-align:right;">
                                <form method="POST" action="{{ route('employee.orders.close', $order->id) }}" style="display:inline;">
                                    @csrf
                                    <button
                                        type="submit"
                                        style="background:#7c6fa0;color:#fff;border:none;border-radius:2px;padding:.35rem .9rem;font-size:.78rem;font-weight:600;cursor:pointer;font-family:inherit;transition:opacity .15s;"
                                        onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'"
                                    >✓ Fechar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif

</div>
@endsection
