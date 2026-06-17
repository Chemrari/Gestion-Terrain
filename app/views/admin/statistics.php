<?php 
$title = "Rapports Statistiques";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <h1 class="h3 fw-bold mb-1 text-secondary">Rapports & Statistiques</h1>
        <p class="text-muted small mb-4">Analysez l'évolution des ventes, de la fréquentation et de l'attractivité des terrains.</p>

        <div class="row g-4 mb-4">
            <!-- Monthly Revenue Line Chart -->
            <div class="col-lg-6">
                <div class="card glass-card p-4 shadow-sm h-100">
                    <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-cash-stack text-primary me-2"></i>Évolution du Chiffre d'Affaires Mensuel</h5>
                    <div style="height: 300px; position: relative;">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Bookings Bar Chart -->
            <div class="col-lg-6">
                <div class="card glass-card p-4 shadow-sm h-100">
                    <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-calendar-event text-primary me-2"></i>Volume Mensuel des Réservations</h5>
                    <div style="height: 300px; position: relative;">
                        <canvas id="monthlyReservationsChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Terrains Popularity Horizontal Bar Chart -->
            <div class="col-lg-8 mx-auto">
                <div class="card glass-card p-4 shadow-sm">
                    <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-graph-up text-primary me-2"></i>Popularité Relative des Terrains</h5>
                    <div style="height: 300px; position: relative;">
                        <canvas id="popularTerrainsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. REVENUE LINE CHART ---
        const revData = <?= json_encode($monthlyRevenue) ?>;
        const revLabels = revData.map(item => {
            const parts = item.mois.split('-');
            return parts[1] + '/' + parts[0];
        });
        const revTotals = revData.map(item => parseFloat(item.total));

        const ctxRev = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: revLabels.length ? revLabels : ['Aucun'],
                datasets: [{
                    label: 'Ventes en DH',
                    data: revTotals.length ? revTotals : [0],
                    borderColor: '#22C55E', // success color green
                    backgroundColor: 'rgba(34, 197, 94, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#E2E8F0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // --- 2. RESERVATIONS BAR CHART ---
        const resData = <?= json_encode($monthlyReservations) ?>;
        const resLabels = resData.map(item => {
            const parts = item.mois.split('-');
            return parts[1] + '/' + parts[0];
        });
        const resCounts = resData.map(item => parseInt(item.total));

        const ctxRes = document.getElementById('monthlyReservationsChart').getContext('2d');
        new Chart(ctxRes, {
            type: 'bar',
            data: {
                labels: resLabels.length ? resLabels : ['Aucun'],
                datasets: [{
                    label: 'Nombre de Réservations',
                    data: resCounts.length ? resCounts : [0],
                    backgroundColor: '#2563EB', // primary blue
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#E2E8F0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // --- 3. POPULARITY HORIZONTAL BAR CHART ---
        const popData = <?= json_encode($popularTerrains) ?>;
        const popLabels = popData.map(item => item.nom);
        const popCounts = popData.map(item => parseInt(item.reservations_count));

        const ctxPop = document.getElementById('popularTerrainsChart').getContext('2d');
        new Chart(ctxPop, {
            type: 'bar',
            data: {
                labels: popLabels.length ? popLabels : ['Aucun'],
                datasets: [{
                    label: 'Nombre de Locations',
                    data: popCounts.length ? popCounts : [0],
                    backgroundColor: '#0F172A', // secondary dark slate
                    borderRadius: 6,
                    maxBarThickness: 35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // Makes the bar chart horizontal
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        grid: { color: '#E2E8F0' }
                    },
                    y: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
