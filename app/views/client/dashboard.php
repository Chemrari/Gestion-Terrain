<?php 
$title = "Dashboard Client";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 fw-bold m-0 text-secondary">Tableau de bord</h1>
                <p class="text-muted small m-0">Consultez vos statistiques et vos récentes réservations.</p>
            </div>
            <a href="<?= base_url('client/terrains') ?>" class="btn btn-primary shadow-sm btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Réserver un terrain
            </a>
        </div>

        <!-- Stats row -->
        <div class="row g-4 mb-5">
            <div class="col-sm-6 col-lg-3">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Réservations</span>
                            <h3 class="fw-bold mt-1 mb-0 text-dark"><?= $stats['total'] ?></h3>
                        </div>
                        <div class="icon-box bg-primary-light text-primary">
                            <i class="bi bi-calendar-event fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">En attente</span>
                            <h3 class="fw-bold mt-1 mb-0 text-warning"><?= $stats['en_attente'] ?></h3>
                        </div>
                        <div class="icon-box bg-warning-light text-warning" style="background-color: rgba(245, 158, 11, 0.1);">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Acceptées</span>
                            <h3 class="fw-bold mt-1 mb-0 text-primary"><?= $stats['acceptee'] ?></h3>
                        </div>
                        <div class="icon-box bg-primary-light text-primary">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card-stat p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold">Terminées</span>
                            <h3 class="fw-bold mt-1 mb-0 text-success"><?= $stats['terminee'] ?></h3>
                        </div>
                        <div class="icon-box bg-success-light text-success">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent bookings table -->
        <div class="card glass-card p-4 shadow-sm">
            <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-clock-history me-2 text-primary"></i>Réservations Récentes</h5>
            
            <?php if (!empty($recentReservations)): ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Terrain</th>
                                <th>Ville</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Montant Total</th>
                                <th>Statut Réservation</th>
                                <th>Statut Paiement</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentReservations as $res): ?>
                                <tr>
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
                                        <?php if ($res['paiement_statut'] === 'paye'): ?>
                                            <span class="badge bg-success text-white small px-2 py-1"><i class="bi bi-cash-coin me-1"></i> Payé</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary text-white small px-2 py-1"><i class="bi bi-hourglass me-1"></i> Non payé</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fs-2 text-muted mb-2"></i>
                    <p class="text-muted mb-0">Vous n'avez pas encore fait de réservation.</p>
                    <a href="<?= base_url('client/terrains') ?>" class="btn btn-primary btn-sm mt-3 shadow-sm">Découvrir les terrains</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
