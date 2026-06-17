<?php 
$title = "Mes Réservations";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <h1 class="h3 fw-bold mb-1 text-secondary">Mes Réservations</h1>
        <p class="text-muted small mb-4">Consultez l'état de vos réservations, vos paiements, ou annulez vos demandes en attente.</p>

        <div class="card glass-card p-4 shadow-sm">
            <?php if (!empty($reservations)): ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Terrain</th>
                                <th>Ville</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Montant Total</th>
                                <th>Statut Réservation</th>
                                <th>Statut Paiement</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $res): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($res['terrain_image'])): ?>
                                            <img src="<?= base_url('public/uploads/' . $res['terrain_image']) ?>" width="60" height="40" class="rounded object-fit-cover" alt="Terrain">
                                        <?php else: ?>
                                            <div class="bg-light rounded text-muted d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                                <i class="bi bi-image" style="font-size: 14px;"></i>
                                            </div>
                                        <?php endif; ?>
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
                                        <?php if ($res['paiement_statut'] === 'paye'): ?>
                                            <span class="badge bg-success text-white small px-2 py-1"><i class="bi bi-cash-coin me-1"></i> Payé</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary text-white small px-2 py-1"><i class="bi bi-hourglass me-1"></i> Non payé</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($res['statut'] === 'en_attente'): ?>
                                            <form action="<?= base_url('client/reservation/cancel') ?>" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler cette demande de réservation ?');" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="reservation_id" value="<?= (int)$res['id'] ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle me-1"></i> Annuler
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
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
                    <p class="text-muted mb-0">Vous n'avez effectué aucune réservation.</p>
                    <a href="<?= base_url('client/terrains') ?>" class="btn btn-primary btn-sm mt-3 shadow-sm">Découvrir les terrains</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
