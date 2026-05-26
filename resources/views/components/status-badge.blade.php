@props(['status'])

@php
$styles = match($status) {
    'pending'  => 'background:rgba(234,179,8,.15);color:#ca8a04;border:1px solid rgba(234,179,8,.25);',
    'closed'   => 'background:rgba(34,197,94,.15);color:#16a34a;border:1px solid rgba(34,197,94,.25);',
    'canceled' => 'background:rgba(239,68,68,.15);color:#dc2626;border:1px solid rgba(239,68,68,.25);',
    default    => 'background:rgba(100,116,139,.15);color:#64748b;border:1px solid rgba(100,116,139,.25);',
};
$labels = [
    'pending'  => 'Pendente',
    'closed'   => 'Fechada',
    'canceled' => 'Anulada',
];
@endphp

<span style="display:inline-block;border-radius:20px;padding:2px 10px;font-size:0.75rem;font-weight:500;{{ $styles }}">
    {{ $labels[$status] ?? $status }}
</span>
