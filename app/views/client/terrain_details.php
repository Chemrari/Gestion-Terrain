<?php 
$title = "Détails du Terrain";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="mb-4">
            <a href="<?= base_url('client/terrains') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-left me-1"></i> Retour aux terrains</a>
        </div>

        <div class="row">
            <!-- Left col: image & descriptions -->
            <div class="col-lg-7">
                <div class="card glass-card overflow-hidden shadow-sm mb-4">
                    <div style="height: 380px; overflow: hidden; position: relative;">
                        <?php if (!empty($terrain['image'])): ?>
                            <img src="<?= base_url('public/uploads/' . $terrain['image']) ?>" alt="<?= e($terrain['nom']) ?>" class="w-100 h-100" style="object-fit: cover;">
                        <?php else: ?>
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-image fs-1" style="font-size: 80px;"></i>
                            </div>
                        <?php endif; ?>
                        <span class="position-absolute top-3 start-3 badge badge-status <?= e($terrain['statut']) ?>" style="top: 20px; left: 20px; font-size: 14px; padding: 8px 16px;">
                            <?= $terrain['statut'] === 'disponible' ? 'Disponible' : 'Réservé' ?>
                        </span>
                    </div>
                    
                    <div class="p-4 p-md-5">
                        <h2 class="fw-bold mb-3 text-secondary"><?= e($terrain['nom']) ?></h2>
                        <div class="d-flex flex-wrap gap-4 mb-4">
                            <div>
                                <span class="text-muted small d-block">Ville</span>
                                <strong class="text-dark"><i class="bi bi-geo-alt-fill text-primary"></i> <?= e($terrain['ville']) ?></strong>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Surface</span>
                                <strong class="text-dark"><i class="bi bi-fullscreen text-primary"></i> <?= e($terrain['surface']) ?> m²</strong>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Tarif / Heure</span>
                                <strong class="text-primary fs-5"><?= number_format((float)$terrain['prix'], 2) ?> DH</strong>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-3">Description</h5>
                        <p class="text-muted" style="line-height: 1.7;"><?= nl2br(e($terrain['description'] ?? 'Aucune description disponible pour ce terrain.')) ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Right col: booking form -->
            <div class="col-lg-5">
                <div class="card glass-card p-4 p-md-5 shadow-sm sticky-top" style="top: 100px; z-index: 10;">
                    <h4 class="fw-bold mb-4 text-secondary"><i class="bi bi-calendar-event me-2 text-primary"></i>Faire une réservation</h4>
                    
                    <?php if ($terrain['statut'] === 'disponible'): ?>
                        <form action="<?= base_url('client/reserve') ?>" method="POST" id="bookingForm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="terrain_id" value="<?= (int)$terrain['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">Date de Début *</label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control" min="<?= date('Y-m-d') ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary">Date de Fin *</label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control" min="<?= date('Y-m-d') ?>" required>
                            </div>

                            <!-- Real-time cost preview box -->
                            <div class="bg-light rounded p-3 mb-4 border" id="costSummaryBox" style="display: none;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tarif journalier :</span>
                                    <span class="fw-semibold text-dark"><?= number_format((float)$terrain['prix'], 2) ?> DH</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Nombre de jours :</span>
                                    <span class="fw-semibold text-dark" id="daysCount">0</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-secondary">Montant total :</strong>
                                    <strong class="text-primary fs-5" id="totalAmount">0.00 DH</strong>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 shadow">
                                    Soumettre la réservation <i class="bi bi-check-circle ms-1"></i>
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger m-0 text-center">
                            <i class="bi bi-exclamation-octagon fs-3 d-block mb-2"></i>
                            <strong>Terrain actuellement indisponible !</strong>
                            <p class="small text-muted m-0 mt-1">Ce terrain a été réservé par un autre client ou est temporairement fermé.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');
        const costSummaryBox = document.getElementById('costSummaryBox');
        const daysCount = document.getElementById('daysCount');
        const totalAmount = document.getElementById('totalAmount');
        
        const pricePerDay = <?= (float)$terrain['prix'] ?>;

        function updateCost() {
            const startVal = dateDebut.value;
            const endVal = dateFin.value;
            
            if (startVal && endVal) {
                const startDate = new Date(startVal);
                const endDate = new Date(endVal);
                
                // Validate dates order
                if (endDate >= startDate) {
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const days = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                    
                    costSummaryBox.style.display = 'block';
                    daysCount.textContent = days;
                    totalAmount.textContent = (days * pricePerDay).toFixed(2) + ' DH';
                    
                    // Set min date of end date to start date dynamically
                    dateFin.min = startVal;
                } else {
                    costSummaryBox.style.display = 'none';
                }
            } else {
                costSummaryBox.style.display = 'none';
            }
        }

        if (dateDebut && dateFin) {
            dateDebut.addEventListener('change', function() {
                dateFin.min = dateDebut.value;
                updateCost();
            });
            dateFin.addEventListener('change', updateCost);
        }
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
