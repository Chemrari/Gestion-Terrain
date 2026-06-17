<?php 
$title = "Contact";
require_once ROOT_PATH . '/app/views/layouts/header.php';
require_once ROOT_PATH . '/app/views/layouts/public_navbar.php';
?>

<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-5">
                <h6 class="text-primary fw-bold text-uppercase">Nous contacter</h6>
                <h1 class="display-6 fw-bold mb-4" style="color: var(--secondary);">Besoin d'aide ou d'informations ?</h1>
                <p class="text-muted mb-5">N'hésitez pas à nous envoyer un message. Notre équipe d'assistance vous répondra sous 24 heures.</p>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary-light text-primary rounded p-3 me-3">
                        <i class="bi bi-geo-alt fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Notre Siège</h6>
                        <span class="text-muted">Boulevard d'Anfa, Casablanca, Maroc</span>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary-light text-primary rounded p-3 me-3">
                        <i class="bi bi-telephone fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Téléphone</h6>
                        <span class="text-muted">+212 5 22 00 11 22</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="bg-primary-light text-primary rounded p-3 me-3">
                        <i class="bi bi-envelope fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email</h6>
                        <span class="text-muted">contact@geoterrain.ma</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="p-4 p-md-5 glass-card shadow-sm">
                    <h4 class="fw-bold mb-4">Formulaire de Contact</h4>
                    <form action="<?= base_url('contact') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nom complet *</label>
                                <input type="text" name="nom" class="form-control" placeholder="Amine Rachidi" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Adresse Email *</label>
                                <input type="email" name="email" class="form-control" placeholder="amine@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Sujet *</label>
                                <input type="text" name="sujet" class="form-control" placeholder="Demande de partenariat, question sur les réservations..." required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Message *</label>
                                <textarea name="message" rows="5" class="form-control" placeholder="Votre message..." required></textarea>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-primary shadow-sm py-2">
                                    Envoyer le message <i class="bi bi-send ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
