<?php 
$title = "À propos";
require_once ROOT_PATH . '/app/views/layouts/header.php';
require_once ROOT_PATH . '/app/views/layouts/public_navbar.php';
?>

<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h6 class="text-primary fw-bold text-uppercase">Qui sommes-nous ?</h6>
                <h1 class="display-5 fw-bold mb-4" style="color: var(--secondary);">Simplifier l'accès aux infrastructures sportives</h1>
                <p class="lead text-muted mb-4">GeoTerrain est né de la volonté de numériser la réservation de complexes sportifs et de terrains municipaux, souvent confrontés à une gestion manuelle inefficace.</p>
                <p class="text-muted mb-4">Notre plateforme offre une interface moderne qui connecte les passionnés de sport aux gestionnaires de terrains. Que vous soyez un club, une entreprise ou un groupe d'amis cherchant à faire une partie hebdomadaire, GeoTerrain résout le problème des doubles réservations et simplifie la facturation.</p>
                <div class="d-flex gap-3">
                    <a href="<?= base_url('register') ?>" class="btn btn-primary shadow">Créer un compte</a>
                    <a href="<?= base_url('contact') ?>" class="btn btn-outline-secondary">Nous contacter</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-5 glass-card shadow-sm text-center">
                    <i class="bi bi-geo-fill text-primary" style="font-size: 80px;"></i>
                    <h3 class="fw-bold mt-4 mb-2">Notre Vision</h3>
                    <p class="text-muted">Favoriser la pratique du sport en facilitant les processus de location et en rendant transparents les tarifs et les plannings d'occupation.</p>
                    <div class="row g-3 mt-4 text-start">
                        <div class="col-6">
                            <h5 class="fw-bold text-primary m-0">+500</h5>
                            <span class="text-muted small">Réservations effectuées</span>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold text-primary m-0">99%</h5>
                            <span class="text-muted small">Clients satisfaits</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
