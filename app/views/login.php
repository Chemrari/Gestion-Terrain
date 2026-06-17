<?php 
$title = "Connexion";
require_once ROOT_PATH . '/app/views/layouts/header.php';
require_once ROOT_PATH . '/app/views/layouts/public_navbar.php';
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="p-4 p-md-5 glass-card shadow-sm">
                <div class="text-center mb-4">
                    <i class="bi bi-geo-fill text-primary" style="font-size: 40px;"></i>
                    <h3 class="fw-bold mt-2">Bienvenue</h3>
                    <p class="text-muted small">Connectez-vous pour louer et gérer vos terrains</p>
                </div>
                
                <form action="<?= base_url('login') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Adresse Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-start-0" placeholder="Ex: client@terrains.com" required 
                                   value="<?= isset($_COOKIE['remember_user']) ? e($_COOKIE['remember_user']) : '' ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control border-start-0" placeholder="Saisir votre mot de passe" required>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberMe" 
                                   <?= isset($_COOKIE['remember_user']) ? 'checked' : '' ?>>
                            <label class="form-check-label small text-muted" for="rememberMe">Se souvenir de moi</label>
                        </div>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary py-2 shadow-sm">
                            Se connecter <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </div>
                    
                    <div class="text-center">
                        <p class="small text-muted">Pas encore de compte ? <a href="<?= base_url('register') ?>" class="text-primary fw-semibold">S'inscrire</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>