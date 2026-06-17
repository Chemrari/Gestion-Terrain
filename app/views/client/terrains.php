<?php 
$title = "Réserver un Terrain";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <h1 class="h3 fw-bold mb-1 text-secondary">Réserver un Terrain</h1>
        <p class="text-muted small mb-4">Recherchez un terrain par ville, prix ou disponibilité et effectuez votre réservation.</p>

        <!-- Filters card -->
        <div class="card glass-card p-4 mb-4 shadow-sm">
            <form action="<?= base_url('client/terrains') ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-secondary">Recherche</label>
                        <input type="text" name="search" class="form-control" placeholder="Nom..." value="<?= e($search) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-secondary">Ville</label>
                        <select name="ville" class="form-select">
                            <option value="">Toutes les villes</option>
                            <?php foreach ($villes as $v): ?>
                                <option value="<?= e($v) ?>" <?= $selected_ville === $v ? 'selected' : '' ?>><?= e($v) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous</option>
                            <option value="disponible" <?= ($selected_statut ?? '') === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                            <option value="reserve" <?= ($selected_statut ?? '') === 'reserve' ? 'selected' : '' ?>>Réservé</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-secondary">Prix Max (DH/h)</label>
                        <input type="number" name="max_prix" class="form-control" placeholder="Ex: 500" value="<?= e($max_prix) ?>">
                    </div>
                    <div class="col-md-2 d-grid align-items-end">
                        <button type="submit" class="btn btn-primary py-2 shadow-sm">
                            <i class="bi bi-search me-1"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Terrains list -->
        <?php if (!empty($terrains)): ?>
            <div class="row g-4">
                <?php foreach ($terrains as $t): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="terrain-card">
                            <div class="img-wrapper">
                                <span class="status-tag badge badge-status <?= e($t['statut']) ?>">
                                    <?= $t['statut'] === 'disponible' ? 'Disponible' : 'Réservé' ?>
                                </span>
                                <?php if (!empty($t['image'])): ?>
                                    <img src="<?= base_url('public/uploads/' . $t['image']) ?>" alt="<?= e($t['nom']) ?>">
                                <?php else: ?>
                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-image fs-1"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="price-tag"><?= number_format((float)$t['prix'], 2) ?> DH/h</span>
                            </div>
                            <div class="p-4">
                                <h5 class="fw-bold mb-2 text-dark"><?= e($t['nom']) ?></h5>
                                <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-primary me-1"></i> <?= e($t['ville']) ?> (<?= e($t['surface']) ?> m²)</p>
                                <p class="text-muted text-truncate mb-4"><?= e($t['description'] ?? 'Aucune description disponible.') ?></p>
                                <div class="d-grid">
                                    <a href="<?= base_url('client/terrain/details?id=' . $t['id']) ?>" class="btn btn-primary btn-sm shadow-sm">
                                        Voir les détails / Réserver <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-emoji-frown fs-1 text-muted mb-2"></i>
                <h5>Aucun terrain ne correspond à votre recherche.</h5>
                <p class="text-muted">Veuillez modifier vos critères de filtrage ou réinitialiser.</p>
                <a href="<?= base_url('client/terrains') ?>" class="btn btn-outline-secondary btn-sm mt-3">Tout afficher</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
