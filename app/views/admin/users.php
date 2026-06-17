<?php 
$title = "Gestion des Utilisateurs";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <h1 class="h3 fw-bold mb-1 text-secondary">Gestion des Utilisateurs</h1>
        <p class="text-muted small mb-4">Gérez les comptes clients et mettez à jour les privilèges d'accès.</p>

        <div class="card glass-card p-4 shadow-sm">
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Utilisateur</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Date d'Inscription</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= (int)$u['id'] ?></td>
                                    <td><strong><?= e($u['prenom']) . ' ' . e($u['nom']) ?></strong></td>
                                    <td><?= e($u['email']) ?></td>
                                    <td><?= e($u['telephone'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge <?= $u['role'] === 'admin' ? 'bg-primary' : 'bg-secondary' ?> text-white small">
                                            <?= e($u['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                                    <td class="text-end">
                                        <!-- Do not allow actions on current logged-in user -->
                                        <?php if ($u['id'] !== $_SESSION['id']): ?>
                                            <!-- Role switch form -->
                                            <form action="<?= base_url('admin/users/role') ?>" method="POST" class="d-inline me-1">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                                <input type="hidden" name="role" value="<?= $u['role'] === 'admin' ? 'client' : 'admin' ?>">
                                                <button type="submit" class="btn btn-outline-primary btn-sm" title="Changer le rôle">
                                                    <i class="bi bi-person-gear"></i> <?= $u['role'] === 'admin' ? 'Retirer Admin' : 'Rendre Admin' ?>
                                                </button>
                                            </form>
                                            
                                            <!-- Delete user link -->
                                            <a href="<?= base_url('admin/users/delete?id=' . $u['id']) ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ? Toutes ses données associées seront supprimées.');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Moi-même</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-people fs-2 text-muted mb-2"></i>
                    <p class="text-muted mb-0">Aucun utilisateur enregistré.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>
