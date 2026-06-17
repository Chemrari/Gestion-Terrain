<?php 
$title = "Accueil";
require_once ROOT_PATH . '/app/views/layouts/header.php';
require_once ROOT_PATH . '/app/views/layouts/public_navbar.php';
?>

<!-- Hero & Search Section -->
<section class="hero-section text-center py-5">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3" style="color: var(--secondary);">Réservez votre <span class="text-primary">Terrain</span> en quelques clics</h1>
        <p class="lead text-muted mb-5">Découvrez et réservez des terrains de sport premium près de chez vous de manière simple, rapide et sécurisée.</p>

        <!-- Search Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="<?= base_url('/') ?>" method="GET" class="search-box glass-card text-start">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary">Recherche</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Nom du terrain..." value="<?= e($search) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Ville</label>
                            <select name="ville" class="form-select">
                                <option value="">Toutes les villes</option>
                                <?php foreach ($villes as $v): ?>
                                    <option value="<?= e($v) ?>" <?= $selected_ville === $v ? 'selected' : '' ?>><?= e($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-secondary">Prix Max (DH/h)</label>
                            <input type="number" name="max_prix" class="form-control" placeholder="Ex: 400" value="<?= e($max_prix) ?>">
                        </div>
                        <div class="col-md-2 d-grid align-items-end">
                            <button type="submit" class="btn btn-primary py-2 px-3 shadow">
                                <i class="bi bi-funnel me-1"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Terrains Display Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="h3 fw-bold m-0" style="color: var(--secondary);">Nos Terrains Disponibles</h2>
            <?php if (!empty($search) || !empty($selected_ville) || !empty($max_prix)): ?>
                <a href="<?= base_url('/') ?>" class="btn btn-sm btn-outline-secondary">Réinitialiser les filtres</a>
            <?php endif; ?>
        </div>

        <?php if (!empty($terrains)): ?>
            <div class="row g-4">
                <?php foreach ($terrains as $t): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="terrain-card">
                            <div class="img-wrapper">
                                <span class="status-tag badge badge-status disponible">Disponible</span>
                                <?php if (!empty($t['image'])): ?>
                                    <img src="<?= base_url('public/uploads/' . $t['image']) ?>" alt="<?= e($t['nom']) ?>">
                                <?php else: ?>
                                    <!-- Fallback image design using SVG -->
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
                                    <?php if (is_logged_in()): ?>
                                        <a href="<?= base_url('client/terrain/details?id=' . $t['id']) ?>" class="btn btn-primary btn-sm shadow-sm">
                                            Réserver maintenant <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('login') ?>" class="btn btn-outline-primary btn-sm">
                                            Se connecter pour réserver
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-3"><i class="bi bi-exclamation-circle fs-1 text-muted"></i></div>
                <h4>Aucun terrain disponible trouvé</h4>
                <p class="text-muted">Essayez de modifier vos critères de recherche ou vos filtres.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Features Info Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-4 glass-card h-100">
                    <div class="bg-primary text-white rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-lightning-charge fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Réservation Instantanée</h5>
                    <p class="text-muted">Consultez en temps réel les disponibilités des terrains de sport et réservez immédiatement.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 glass-card h-100">
                    <div class="bg-primary text-white rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-check fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Paiement Sécurisé</h5>
                    <p class="text-muted">Payez en ligne de façon fiable ou enregistrez votre transaction auprès de l'administration.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 glass-card h-100">
                    <div class="bg-primary text-white rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-star fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Terrains Certifiés</h5>
                    <p class="text-muted">Tous nos terrains partenaires disposent d'infrastructures de qualité avec éclairage et douches.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
