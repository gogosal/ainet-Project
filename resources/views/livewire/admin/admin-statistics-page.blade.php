<div style="padding:1.5rem;">

    {{-- Header --}}
    <div style="margin-bottom:1.5rem;">
        <h1 style="color:#e2e8f0;font-size:1.5rem;font-weight:700;margin:0;">Estatísticas</h1>
        <p style="color:#94a3b8;font-size:0.85rem;margin:0.25rem 0 0;">Análise de desempenho da loja</p>
    </div>

    {{-- Charts grid (2x2) --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

        {{-- 1. Revenue by month --}}
        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.5rem;">
            <h2 style="color:#e2e8f0;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Receita por mês (últimos 12 meses)</h2>
            <canvas id="revenueChart"></canvas>
        </div>

        {{-- 2. Orders by status --}}
        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.5rem;">
            <h2 style="color:#e2e8f0;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Encomendas por estado</h2>
            <canvas id="statusChart"></canvas>
        </div>

        {{-- 3. New clients by month --}}
        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.5rem;">
            <h2 style="color:#e2e8f0;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Novos clientes por mês (últimos 6 meses)</h2>
            <canvas id="clientsChart"></canvas>
        </div>

        {{-- 4. Top 5 catalog images --}}
        <div style="background:#111120;border:1px solid #1e1e30;border-radius:12px;padding:1.5rem;">
            <h2 style="color:#e2e8f0;font-size:0.95rem;font-weight:600;margin:0 0 1rem;">Top 5 imagens de catálogo (unidades vendidas)</h2>
            <canvas id="topImagesChart"></canvas>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueData = @json($revenueByMonth);
        const statusData = @json($ordersByStatus);
        const clientsData = @json($clientsByMonth);
        const topImagesData = @json($topImages);

        // Chart.js color scheme: purple #7c3aed, teal #0d9488, amber #d97706
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue chart
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: revenueData.map(d => d.month),
                    datasets: [{
                        label: 'Receita (€)',
                        data: revenueData.map(d => d.revenue),
                        borderColor: '#a78bfa',
                        backgroundColor: 'rgba(124,58,237,.1)',
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: { responsive: true, plugins: { legend: { labels: { color: '#94a3b8' } } }, scales: { x: { ticks: { color: '#94a3b8' }, grid: { color: '#1e1e30' } }, y: { ticks: { color: '#94a3b8' }, grid: { color: '#1e1e30' } } } }
            });

            // Status doughnut
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Pendente', 'Fechada', 'Cancelada'],
                    datasets: [{
                        data: [statusData.pending ?? 0, statusData.closed ?? 0, statusData.canceled ?? 0],
                        backgroundColor: ['#d97706', '#059669', '#dc2626'],
                    }]
                },
                options: { responsive: true, plugins: { legend: { labels: { color: '#94a3b8' } } } }
            });

            // Clients bar
            new Chart(document.getElementById('clientsChart'), {
                type: 'bar',
                data: {
                    labels: clientsData.map(d => d.month),
                    datasets: [{
                        label: 'Novos clientes',
                        data: clientsData.map(d => d.total),
                        backgroundColor: '#7c3aed',
                    }]
                },
                options: { responsive: true, plugins: { legend: { labels: { color: '#94a3b8' } } }, scales: { x: { ticks: { color: '#94a3b8' }, grid: { color: '#1e1e30' } }, y: { ticks: { color: '#94a3b8' }, grid: { color: '#1e1e30' } } } }
            });

            // Top images horizontal bar
            new Chart(document.getElementById('topImagesChart'), {
                type: 'bar',
                data: {
                    labels: topImagesData.map(d => d.name),
                    datasets: [{
                        label: 'Unidades vendidas',
                        data: topImagesData.map(d => d.total_qty),
                        backgroundColor: '#0d9488',
                    }]
                },
                options: { indexAxis: 'y', responsive: true, plugins: { legend: { labels: { color: '#94a3b8' } } }, scales: { x: { ticks: { color: '#94a3b8' }, grid: { color: '#1e1e30' } }, y: { ticks: { color: '#94a3b8' }, grid: { color: '#1e1e30' } } } }
            });
        });
    </script>

</div>
