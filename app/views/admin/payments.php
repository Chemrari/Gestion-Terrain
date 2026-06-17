<?php 
$title = "Gestion des Paiements";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <h1 class="h3 fw-bold mb-1 text-secondary">Gestion des Paiements</h1>
        <p class="text-muted small mb-4">Enregistrez les transactions de vos clients et suivez les flux financiers.</p>

        <div class="row">
            <!-- Left col: Payment history list -->
            <div class="col-lg-8">
                <div class="card glass-card p-4 shadow-sm">
                    <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-list-check me-2 text-primary"></i>Historique des Paiements</h5>
                    
                    <?php if (!empty($payments)): ?>
                        <div class="table-responsive">
                            <table class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>Terrain</th>
                                        <th>Montant</th>
                                        <th>Mode</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($payments as $p): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($p['date_paiement'])) ?></td>
                                            <td><strong><?= e($p['user_prenom']) . ' ' . e($p['user_nom']) ?></strong></td>
                                            <td><?= e($p['terrain_nom']) ?></td>
                                            <td class="fw-bold text-success"><?= number_format((float)$p['montant'], 2) ?> DH</td>
                                            <td>
                                                <span class="badge bg-light text-dark border small">
                                                    <?php
                                                    if ($p['mode_paiement'] === 'carte') echo 'Carte bancaire';
                                                    elseif ($p['mode_paiement'] === 'espece') echo 'Espèces';
                                                    elseif ($p['mode_paiement'] === 'virement') echo 'Virement';
                                                    else echo e($p['mode_paiement']);
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success text-white small px-2 py-1">
                                                    <?= $p['statut'] === 'paye' ? 'Payé' : e($p['statut']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-cash fs-2 text-muted mb-2"></i>
                            <p class="text-muted mb-0">Aucune transaction enregistrée.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Right col: Record payment form -->
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card glass-card p-4 p-md-5 shadow-sm sticky-top" style="top: 100px; z-index: 10;">
                    <h5 class="fw-bold mb-4 text-secondary"><i class="bi bi-plus-circle me-2 text-primary"></i>Enregistrer un Paiement</h5>
                    
                    <?php if (!empty($unpaidReservations)): ?>
                        <form action="<?= base_url('admin/payment/add') ?>" method="POST" id="paymentForm">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Réservation Acceptée *</label>
                                <select name="reservation_id" id="reservation_id" class="form-select" required>
                                    <option value="" data-amount="0">Sélectionner une réservation...</option>
                                    <?php foreach ($unpaidReservations as $r): ?>
                                        <option value="<?= (int)$r['id'] ?>" data-amount="<?= (float)$r['montant_total'] ?>">
                                            <?= e($r['user_prenom']) . ' ' . e($r['user_nom']) ?> - <?= e($r['terrain_nom']) ?> (<?= number_format((float)$r['montant_total'], 2) ?> DH)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Montant (DH) *</label>
                                <input type="number" name="montant" id="montant" class="form-control" step="0.01" min="0.01" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Mode de Paiement *</label>
                                <select name="mode_paiement" class="form-select" required>
                                    <option value="carte">Carte bancaire</option>
                                    <option value="espece" selected>Espèces</option>
                                    <option value="virement">Virement bancaire</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary">Date de Paiement *</label>
                                <input type="date" name="date_paiement" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 shadow">
                                    Enregistrer la transaction <i class="bi bi-check-circle ms-1"></i>
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info text-center m-0">
                            <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                            <strong>Aucune réservation en attente de paiement.</strong>
                            <p class="small text-muted m-0 mt-1">Toutes les réservations acceptées ont déjà été payées.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectRes = document.getElementById('reservation_id');
        const inputMontant = document.getElementById('montant');
        
        if (selectRes && inputMontant) {
            selectRes.addEventListener('change', function() {
                const selectedOption = selectRes.options[selectRes.selectedIndex];
                const amount = parseFloat(selectedOption.getAttribute('data-amount') || 0);
                if (amount > 0) {
                    inputMontant.value = amount;
                } else {
                    inputMontant.value = '';
                }
            });
        }
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
