<?php 
$title = "Mon Profil";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <h1 class="h3 fw-bold mb-1 text-secondary">Mon Profil</h1>
        <p class="text-muted small mb-4">Gérez vos informations personnelles et sécurisez votre compte.</p>

        <div class="row">
            <div class="col-lg-8">
                <div class="card glass-card p-4 p-md-5 shadow-sm">
                    <form action="<?= base_url('client/profile/update') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-person me-2"></i>Informations Personnelles</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nom</label>
                                <input type="text" name="nom" class="form-control" value="<?= e($user['nom']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Prénom</label>
                                <input type="text" name="prenom" class="form-control" value="<?= e($user['prenom']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Adresse Email</label>
                                <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Téléphone</label>
                                <input type="text" name="telephone" class="form-control" value="<?= e($user['telephone']) ?>">
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: var(--border-color);">

                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-shield-lock me-2"></i>Modifier le mot de passe</h5>
                        <p class="text-muted small mb-3">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Saisir votre mot de passe actuel">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nouveau mot de passe</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Min. 6 caractères">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary shadow-sm px-4">
                                Enregistrer les modifications <i class="bi bi-save ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card glass-card p-4 text-center shadow-sm">
                    <div class="bg-primary text-white rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person fs-1"></i>
                    </div>
                    <h5 class="fw-bold m-0"><?= e($user['prenom']) . ' ' . e($user['nom']) ?></h5>
                    <p class="text-muted small"><?= e($user['email']) ?></p>
                    <hr class="my-3">
                    <div class="text-start">
                        <div class="mb-2">
                            <span class="text-muted small">Rôle :</span>
                            <span class="badge bg-primary text-white small ms-1"><?= e($user['role']) ?></span>
                        </div>
                        <div>
                            <span class="text-muted small">Membre depuis :</span>
                            <span class="text-dark small ms-1"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
