<div style="padding:1.5rem;">

    {{-- Header --}}
    <div style="margin-bottom:1.5rem;">
        <h1 style="color:#1a1a1a;font-size:1.5rem;font-weight:700;margin:0;">Estatísticas</h1>
        <p style="color:#888;font-size:0.85rem;margin:0.25rem 0 0;">Análise de desempenho da loja</p>
    </div>

    {{-- Charts grid (2x2) --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

        {{-- 1. Revenue by month --}}
        <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.5rem;">
            <h2 style="color:#1a1a1a;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Receita por mês (últimos 12 meses)</h2>
            <div style="position:relative;height:260px;"><canvas id="revenueChart"></canvas></div>
        </div>

        {{-- 2. Orders by status --}}
        <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.5rem;">
            <h2 style="color:#1a1a1a;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Encomendas por estado</h2>
            <div style="position:relative;height:260px;"><canvas id="statusChart"></canvas></div>
        </div>

        {{-- 3. New clients by month --}}
        <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.5rem;">
            <h2 style="color:#1a1a1a;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Novos clientes por mês (últimos 6 meses)</h2>
            <div style="position:relative;height:260px;"><canvas id="clientsChart"></canvas></div>
        </div>

        {{-- 4. Top 5 catalog images --}}
        <div style="background:#ffffff;border:1px solid #e0ddd8;border-radius:2px;padding:1.5rem;">
            <h2 style="color:#1a1a1a;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Top 5 imagens mais vendidas</h2>
            <div style="position:relative;height:260px;"><canvas id="topImagesChart"></canvas></div>
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
                tooltip: {
                    backgroundColor: '#ffffff',
                    borderColor: '#7c6fa0',
                    borderWidth: 1,
                    titleColor: '#1a1a1a',
                    bodyColor: '#888',
                    padding: 10,
                }
            }
        };
        const scaleOpts = {
            x: { ticks: { color: tickColor, font: { size: 11 } }, grid: { color: gridColor } },
            y: { ticks: { color: tickColor, font: { size: 11 } }, grid: { color: gridColor } }
        };

        document.addEventListener('DOMContentLoaded', function() {

            // 1. Revenue — area line
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: revenueData.map(d => d.month),
                    datasets: [{
                        label: 'Receita (€)',
                        data: revenueData.map(d => d.revenue),
                        borderColor: '#7c6fa0',
                        backgroundColor: 'rgba(124,111,160,0.10)',
                        pointBackgroundColor: '#7c6fa0',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                    }]
                },
                options: { ...baseOpts, scales: scaleOpts }
            });

            // 2. Orders by status — doughnut
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Pendente', 'Fechada', 'Cancelada'],
                    datasets: [{
                        data: [statusData.pending ?? 0, statusData.closed ?? 0, statusData.canceled ?? 0],
                        backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    ...baseOpts,
                    cutout: '62%',
                    plugins: {
                        ...baseOpts.plugins,
                        legend: { ...baseOpts.plugins.legend, position: 'bottom' }
                    }
                }
            });

            // 3. New clients — bar
            new Chart(document.getElementById('clientsChart'), {
                type: 'bar',
                data: {
                    labels: clientsData.map(d => d.month),
                    datasets: [{
                        label: 'Novos clientes',
                        data: clientsData.map(d => d.total),
                        backgroundColor: 'rgba(124,111,160,0.7)',
                        borderColor: '#7c6fa0',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: { ...baseOpts, scales: scaleOpts }
            });

            // 4. Top images — horizontal bar
            new Chart(document.getElementById('topImagesChart'), {
                type: 'bar',
                data: {
                    labels: topImagesData.map(d => d.name),
                    datasets: [{
                        label: 'Unidades vendidas',
                        data: topImagesData.map(d => d.total_qty),
                        backgroundColor: 'rgba(13,148,136,0.75)',
                        borderColor: '#0d9488',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    ...baseOpts,
                    indexAxis: 'y',
                    scales: {
                        x: scaleOpts.x,
                        y: { ...scaleOpts.y, ticks: { ...scaleOpts.y.ticks, font: { size: 11 } } }
                    }
                }
            });
        });
    </script>

</div>
