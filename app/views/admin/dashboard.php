<?php 
$title = "Dashboard Admin";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 fw-bold m-0 text-secondary">Tableau de bord Admin</h1>
                <p class="text-muted small m-0">Aperçu général de l'activité de votre plateforme.</p>
            </div>
            <a href="<?= base_url('admin/statistics') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-bar-chart-line me-1"></i> Voir les rapports complets
            </a>
        </div>

        <!-- Admin cards stats row -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-4">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Chiffre d'Affaires</span>
                            <h3 class="fw-bold mt-1 mb-0 text-dark"><?= number_format((float)$counts['revenue'], 2) ?> DH</h3>
                        </div>
                        <div class="icon-box bg-success-light text-success">
                            <i class="bi bi-cash-coin fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-2">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Terrains</span>
                            <h3 class="fw-bold mt-1 mb-0 text-dark"><?= $counts['terrains'] ?></h3>
                        </div>
                        <div class="icon-box bg-primary-light text-primary">
                            <i class="bi bi-layers-half"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-2">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Clients</span>
                            <h3 class="fw-bold mt-1 mb-0 text-dark"><?= $counts['clients'] ?></h3>
                        </div>
                        <div class="icon-box bg-info text-white" style="background-color: rgba(6, 182, 212, 0.1) !important; color: var(--info) !important;">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-2">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">En attente</span>
                            <h3 class="fw-bold mt-1 mb-0 text-warning"><?= $counts['pending'] ?></h3>
                        </div>
                        <div class="icon-box bg-warning-light text-warning" style="background-color: rgba(245, 158, 11, 0.1);">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-2">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Acceptées</span>
                            <h3 class="fw-bold mt-1 mb-0 text-primary"><?= $counts['accepted'] ?></h3>
                        </div>
                        <div class="icon-box bg-primary-light text-primary">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts row -->
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="card glass-card p-4 h-100 shadow-sm">
                    <h5 class="fw-bold mb-3 text-secondary">Évolution du Chiffre d'Affaires</h5>
                    <div style="height: 250px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card glass-card p-4 h-100 shadow-sm">
                    <h5 class="fw-bold mb-3 text-secondary">Popularité des Terrains</h5>
                    <div style="height: 250px;">
                        <canvas id="popularityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent bookings table -->
        <div class="card glass-card p-4 shadow-sm">
            <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-clock-history me-2 text-primary"></i>Demandes de Réservation Récentes</h5>
            
            <?php if (!empty($recentReservations)): ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Terrain</th>
                                <th>Ville</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentReservations as $res): ?>
                                <tr>
                                    <td>
                                        <strong><?= e($res['user_prenom']) . ' ' . e($res['user_nom']) ?></strong>
                                        <div class="text-muted small" style="font-size: 11px;"><?= e($res['user_email']) ?></div>
                                    </td>
                                    <td><strong><?= e($res['terrain_nom']) ?></strong></td>
                                    <td><?= e($res['terrain_ville']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($res['date_debut'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($res['date_fin'])) ?></td>
                                    <td class="fw-bold text-dark"><?= number_format((float)$res['montant_total'], 2) ?> DH</td>
                                    <td>
                                        <span class="badge-status <?= e($res['statut']) ?>">
                                            <?php
                                            if ($res['statut'] === 'en_attente') echo 'En attente';
                                            elseif ($res['statut'] === 'acceptee') echo 'Acceptée';
                                            elseif ($res['statut'] === 'refusee') echo 'Refusée';
                                            elseif ($res['statut'] === 'terminee') echo 'Terminée';
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/reservations') ?>" class="btn btn-outline-primary btn-sm">
                                            Gérer <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-2 text-muted mb-2"></i>
                    <p class="text-muted mb-0">Aucune réservation récente dans le système.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- REVENUE CHART ---
        const revData = <?= json_encode($monthlyRevenue) ?>;
        const revLabels = revData.map(item => {
            const parts = item.mois.split('-');
            return parts[1] + '/' + parts[0];
        });
        const revTotals = revData.map(item => parseFloat(item.total));

        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: revLabels.length ? revLabels : ['Aucune donnée'],
                datasets: [{
                    label: 'Revenus (DH)',
                    data: revTotals.length ? revTotals : [0],
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
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

        // --- POPULARITY CHART ---
        const popData = <?= json_encode($popularTerrains) ?>;
        const popLabels = popData.map(item => item.nom);
        const popCounts = popData.map(item => parseInt(item.reservations_count));

        const ctxPop = document.getElementById('popularityChart').getContext('2d');
        new Chart(ctxPop, {
            type: 'bar',
            data: {
                labels: popLabels.length ? popLabels : ['Aucune donnée'],
                datasets: [{
                    label: 'Réservations',
                    data: popCounts.length ? popCounts : [0],
                    backgroundColor: '#0F172A',
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
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
