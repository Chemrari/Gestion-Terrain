<?php
$current_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if (!empty($scriptDir) && strpos($current_uri, $scriptDir) === 0) {
    $current_uri = trim(substr($current_uri, strlen($scriptDir)), '/');
}
if (strpos($current_uri, 'public') === 0) {
    $current_uri = trim(substr($current_uri, 6), '/');
}
$role = $_SESSION['role'] ?? 'client';
?>

<div class="sidebar">
    <div class="brand">
        <i class="bi bi-geo-fill text-primary me-2"></i><span>Geo</span>Terrain
    </div>
    
    <ul class="sidebar-menu">
        <?php if ($role === 'admin'): ?>
            <li class="<?= $current_uri === 'admin/dashboard' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="<?= $current_uri === 'admin/terrains' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/terrains') ?>">
                    <i class="bi bi-layers-half"></i>
                    Terrains
                </a>
            </li>
            <li class="<?= $current_uri === 'admin/reservations' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/reservations') ?>">
                    <i class="bi bi-calendar3"></i>
                    Réservations
                </a>
            </li>
            <li class="<?= $current_uri === 'admin/users' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/users') ?>">
                    <i class="bi bi-people"></i>
                    Utilisateurs
                </a>
            </li>
            <li class="<?= $current_uri === 'admin/payments' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/payments') ?>">
                    <i class="bi bi-cash-stack"></i>
                    Paiements
                </a>
            </li>
            <li class="<?= $current_uri === 'admin/statistics' ? 'active' : '' ?>">
                <a href="<?= base_url('admin/statistics') ?>">
                    <i class="bi bi-bar-chart-line"></i>
                    Statistiques
                </a>
            </li>
        <?php else: ?>
            <li class="<?= $current_uri === 'client/dashboard' ? 'active' : '' ?>">
                <a href="<?= base_url('client/dashboard') ?>">
                    <i class="bi bi-grid-1x2"></i>
                    Dashboard
                </a>
            </li>
            <li class="<?= $current_uri === 'client/terrains' || strpos($current_uri, 'client/terrain/details') === 0 ? 'active' : '' ?>">
                <a href="<?= base_url('client/terrains') ?>">
                    <i class="bi bi-search"></i>
                    Terrains
                </a>
            </li>
            <li class="<?= $current_uri === 'client/reservations' ? 'active' : '' ?>">
                <a href="<?= base_url('client/reservations') ?>">
                    <i class="bi bi-calendar-check"></i>
                    Mes Réservations
                </a>
            </li>
            <li class="<?= $current_uri === 'client/profile' ? 'active' : '' ?>">
                <a href="<?= base_url('client/profile') ?>">
                    <i class="bi bi-person-circle"></i>
                    Mon Profil
                </a>
            </li>
        <?php endif; ?>
    </ul>
    
    <div class="sidebar-footer">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-primary text-white rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-person"></i>
            </div>
            <div>
                <div class="fw-semibold text-white small"><?= e($_SESSION['nom'] ?? 'Utilisateur') ?></div>
                <div class="text-muted small" style="font-size: 11px;"><?= e($_SESSION['role'] ?? 'client') ?></div>
            </div>
        </div>
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center">
            <i class="bi bi-box-arrow-left me-1"></i> Déconnexion
        </a>
    </div>
</div>
