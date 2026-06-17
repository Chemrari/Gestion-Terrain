<?php 
$title = "Gestion des Réservations";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <h1 class="h3 fw-bold mb-1 text-secondary">Gestion des Réservations</h1>
        <p class="text-muted small mb-4">Validez les demandes d'accès ou modifiez les réservations clients.</p>

        <div class="card glass-card p-4 shadow-sm">
            <?php if (!empty($reservations)): ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Client</th>
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
                                        <strong><?= e($res['user_prenom']) . ' ' . e($res['user_nom']) ?></strong>
                                        <div class="text-muted small" style="font-size: 11px;"><?= e($res['user_email']) ?></div>
                                        <div class="text-muted small" style="font-size: 11px;"><?= e($res['user_tel'] ?? '') ?></div>
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
                                            <!-- Approve reservation -->
                                            <form action="<?= base_url('admin/reservation/status') ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="reservation_id" value="<?= (int)$res['id'] ?>">
                                                <input type="hidden" name="statut" value="acceptee">
                                                <button type="submit" class="btn btn-outline-success btn-sm me-1" title="Accepter la réservation">
                                                    <i class="bi bi-check-circle"></i> Accepter
                                                </button>
                                            </form>
                                            
                                            <!-- Reject reservation -->
                                            <form action="<?= base_url('admin/reservation/status') ?>" method="POST" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="reservation_id" value="<?= (int)$res['id'] ?>">
                                                <input type="hidden" name="statut" value="refusee">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Refuser la réservation">
                                                    <i class="bi bi-x-circle"></i> Refuser
                                                </button>
                                            </form>
                                        <?php elseif ($res['statut'] === 'acceptee'): ?>
                                            <!-- If accepted but unpaid, prompt to record payment -->
                                            <?php if (empty($res['paiement_statut'])): ?>
                                                <a href="<?= base_url('admin/payments') ?>" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-cash-coin me-1"></i> Enregistrer Paiement
                                                </a>
                                            <?php else: ?>
                                                <!-- If paid, allow finishing -->
                                                <form action="<?= base_url('admin/reservation/status') ?>" method="POST" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="reservation_id" value="<?= (int)$res['id'] ?>">
                                                    <input type="hidden" name="statut" value="terminee">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm" title="Terminer">
                                                        <i class="bi bi-flag"></i> Clôturer
                                                    </button>
                                                </form>
                                            <?php endif; ?>
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
                    <i class="bi bi-calendar3 fs-2 text-muted mb-2"></i>
                    <p class="text-muted mb-0">Aucune demande de réservation.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
