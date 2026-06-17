<?php 
$title = "Inscription";
require_once ROOT_PATH . '/app/views/layouts/header.php';
require_once ROOT_PATH . '/app/views/layouts/public_navbar.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="p-4 p-md-5 glass-card shadow-sm">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus text-primary" style="font-size: 40px;"></i>
                    <h3 class="fw-bold mt-2">Créer un compte</h3>
                    <p class="text-muted small">Inscrivez-vous pour réserver vos terrains préférés</p>
                </div>
                
                <form action="<?= base_url('register') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nom *</label>
                            <input type="text" name="nom" class="form-control" placeholder="Rachidi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Prénom *</label>
                            <input type="text" name="prenom" class="form-control" placeholder="Amine" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Adresse Email *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-start-0" placeholder="amine@example.com" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-phone"></i></span>
                            <input type="text" name="telephone" class="form-control border-start-0" placeholder="0612345678">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Mot de passe *</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 6 caractères" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Confirmer le mot de passe *</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Ressaisir le mot de passe" required>
                        </div>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary py-2 shadow-sm">
                            S'inscrire <i class="bi bi-check-circle ms-1"></i>
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p class="small text-muted">Déjà un compte ? <a href="<?= base_url('login') ?>" class="text-primary fw-semibold">Se connecter</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>