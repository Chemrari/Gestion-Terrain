<?php
$current_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if (!empty($scriptDir) && strpos($current_uri, $scriptDir) === 0) {
    $current_uri = trim(substr($current_uri, strlen($scriptDir)), '/');
}
if (strpos($current_uri, 'public') === 0) {
    $current_uri = trim(substr($current_uri, 6), '/');
}
?>
<nav class="navbar navbar-expand-lg public-navbar sticky-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('home') ?>">
            <i class="bi bi-geo-fill text-primary me-2"></i>
            <span>Geo</span>Terrain
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_uri === '' || $current_uri === 'home') ? 'active' : '' ?>" href="<?= base_url('home') ?>">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_uri === 'about' ? 'active' : '' ?>" href="<?= base_url('about') ?>">À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_uri === 'contact' ? 'active' : '' ?>" href="<?= base_url('contact') ?>">Contact</a>
                </li>
                
                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm">
                                <i class="bi bi-speedometer2 me-1"></i> Admin Dashboard
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('client/dashboard') ?>" class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm">
                                <i class="bi bi-grid-1x2 me-1"></i> Espace Client
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-primary btn-sm px-3 rounded-pill me-2">Connexion</a>
                        <a href="<?= base_url('register') ?>" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">Inscription</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
