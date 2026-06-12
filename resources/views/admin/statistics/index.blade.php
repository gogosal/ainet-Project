@extends('layouts.admin', ['title' => 'Estatísticas'])
@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-fs-dark text-[1.5rem] font-bold m-0">Estatísticas</h1>
        <p class="text-fs-gray text-[0.85rem] mt-1 m-0">Análise de desempenho da loja</p>
    </div>

    {{-- Charts grid 2x2 --}}
    <div class="grid grid-cols-2 gap-6">

        {{-- 1. Revenue by month --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-6">
            <h2 class="text-fs-dark text-[0.95rem] font-semibold m-0 mb-4">Receita por mês (últimos 12 meses)</h2>
            <div class="relative h-[260px]"><canvas id="revenueChart"></canvas></div>
        </div>

        {{-- 2. Orders by status --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-6">
            <h2 class="text-fs-dark text-[0.95rem] font-semibold m-0 mb-4">Encomendas por estado</h2>
            <div class="relative h-[260px]"><canvas id="statusChart"></canvas></div>
        </div>

        {{-- 3. New clients by month --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-6">
            <h2 class="text-fs-dark text-[0.95rem] font-semibold m-0 mb-4">Novos clientes por mês (últimos 6 meses)</h2>
            <div class="relative h-[260px]"><canvas id="clientsChart"></canvas></div>
        </div>

        {{-- 4. Top 5 catalog images --}}
        <div class="bg-white border border-fs-border rounded-[2px] p-6">
            <h2 class="text-fs-dark text-[0.95rem] font-semibold m-0 mb-4">Top 5 imagens mais vendidas</h2>
            <div class="relative h-[260px]"><canvas id="topImagesChart"></canvas></div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueData  = @json($revenueByMonth);
    const statusData   = @json($ordersByStatus);
    const clientsData  = @json($clientsByMonth);
    const topImagesData = @json($topImages);

    const gridColor  = '#e0ddd8';
    const tickColor  = '#aaa';
    const baseOpts = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: '#888', font: { size: 12 }, boxWidth: 12, padding: 16 } },
            tooltip: { backgroundColor: '#ffffff', borderColor: '#7c6fa0', borderWidth: 1, titleColor: '#1a1a1a', bodyColor: '#888', padding: 10 }
        }
    };
    const scaleOpts = {
        x: { ticks: { color: tickColor, font: { size: 11 } }, grid: { color: gridColor } },
        y: { ticks: { color: tickColor, font: { size: 11 } }, grid: { color: gridColor } }
    };

    document.addEventListener('DOMContentLoaded', function() {
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: revenueData.map(d => d.month),
                datasets: [{ label: 'Receita (€)', data: revenueData.map(d => d.revenue), borderColor: '#7c6fa0', backgroundColor: 'rgba(124,111,160,0.10)', pointBackgroundColor: '#7c6fa0', pointRadius: 4, pointHoverRadius: 6, fill: true, tension: 0.4, borderWidth: 2 }]
            },
            options: { ...baseOpts, scales: scaleOpts }
        });
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pendente', 'Fechada', 'Cancelada'],
                datasets: [{ data: [statusData.pending ?? 0, statusData.closed ?? 0, statusData.canceled ?? 0], backgroundColor: ['#f59e0b', '#10b981', '#ef4444'], borderColor: '#ffffff', borderWidth: 3, hoverOffset: 8 }]
            },
            options: { ...baseOpts, cutout: '62%', plugins: { ...baseOpts.plugins, legend: { ...baseOpts.plugins.legend, position: 'bottom' } } }
        });
        new Chart(document.getElementById('clientsChart'), {
            type: 'bar',
            data: {
                labels: clientsData.map(d => d.month),
                datasets: [{ label: 'Novos clientes', data: clientsData.map(d => d.total), backgroundColor: 'rgba(124,111,160,0.7)', borderColor: '#7c6fa0', borderWidth: 1, borderRadius: 6 }]
            },
            options: { ...baseOpts, scales: scaleOpts }
        });
        new Chart(document.getElementById('topImagesChart'), {
            type: 'bar',
            data: {
                labels: topImagesData.map(d => d.name),
                datasets: [{ label: 'Unidades vendidas', data: topImagesData.map(d => d.total_qty), backgroundColor: 'rgba(13,148,136,0.75)', borderColor: '#0d9488', borderWidth: 1, borderRadius: 6 }]
            },
            options: { ...baseOpts, indexAxis: 'y', scales: { x: scaleOpts.x, y: { ...scaleOpts.y, ticks: { ...scaleOpts.y.ticks, font: { size: 11 } } } } }
        });
    });
</script>

@endsection
