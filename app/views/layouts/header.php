<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GeoTerrain - Application de gestion et réservation de terrains de sport">
    <title><?= isset($title) ? e($title) . ' – GeoTerrain' : 'GeoTerrain | Gestion des Terrains' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>

<?php
// Display toast flash messages
$success_msg = get_flash('success');
$error_msg   = get_flash('error');
?>

<?php if ($success_msg): ?>
<div class="alert alert-success alert-toast d-flex align-items-center gap-2" role="alert" id="toast-notification">
    <i class="bi bi-check-circle-fill flex-shrink-0 fs-5"></i>
    <div class="flex-grow-1"><?= e($success_msg) ?></div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Fermer"></button>
</div>
<?php endif; ?>

<?php if ($error_msg): ?>
<div class="alert alert-danger alert-toast d-flex align-items-center gap-2" role="alert" id="toast-notification">
    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 fs-5"></i>
    <div class="flex-grow-1"><?= e($error_msg) ?></div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Fermer"></button>
</div>
<?php endif; ?>
