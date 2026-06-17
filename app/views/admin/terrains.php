<?php 
$title = "Gestion des Terrains";
require_once ROOT_PATH . '/app/views/layouts/header.php';
?>

<div class="dashboard-wrapper">
    <?php require_once ROOT_PATH . '/app/views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 fw-bold m-0 text-secondary">Gestion des Terrains</h1>
                <p class="text-muted small m-0">Créez, modifiez ou supprimez les terrains de sport.</p>
            </div>
            <button class="btn btn-primary shadow-sm btn-sm" data-bs-toggle="modal" data-bs-target="#addTerrainModal">
                <i class="bi bi-plus-circle me-1"></i> Ajouter un terrain
            </button>
        </div>

        <!-- Terrains list table -->
        <div class="card glass-card p-4 shadow-sm">
            <?php if (!empty($terrains)): ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Ville</th>
                                <th>Surface</th>
                                <th>Prix / Heure</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($terrains as $t): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($t['image'])): ?>
                                            <img src="<?= base_url('public/uploads/' . $t['image']) ?>" width="70" height="50" class="rounded object-fit-cover" alt="Terrain">
                                        <?php else: ?>
                                            <div class="bg-light rounded text-muted d-flex align-items-center justify-content-center" style="width: 70px; height: 50px;">
                                                <i class="bi bi-image" style="font-size: 16px;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?= e($t['nom']) ?></strong></td>
                                    <td><?= e($t['ville']) ?></td>
                                    <td><?= e($t['surface']) ?> m²</td>
                                    <td class="fw-bold text-primary"><?= number_format((float)$t['prix'], 2) ?> DH</td>
                                    <td>
                                        <span class="badge-status <?= e($t['statut']) ?>">
                                            <?= $t['statut'] === 'disponible' ? 'Disponible' : 'Réservé' ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editTerrainModal<?= $t['id'] ?>">
                                            <i class="bi bi-pencil"></i> Modifier
                                        </button>
                                        <a href="<?= base_url('admin/terrain/delete?id=' . $t['id']) ?>" 
                                           class="btn btn-outline-danger btn-sm"
                                           onclick="return confirm('Voulez-vous vraiment supprimer ce terrain et toutes ses réservations associées ?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- EDIT TERRAIN MODAL (Loop-based) -->
                                <div class="modal fade" id="editTerrainModal<?= $t['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-primary text-white border-0 py-3">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i>Modifier le terrain</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <form action="<?= base_url('admin/terrain/edit') ?>" method="POST" enctype="multipart/form-data">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Nom du terrain *</label>
                                                        <input type="text" name="nom" class="form-control" value="<?= e($t['nom']) ?>" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Ville *</label>
                                                        <input type="text" name="ville" class="form-control" value="<?= e($t['ville']) ?>" required>
                                                    </div>
                                                    
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold text-secondary">Surface (m²) *</label>
                                                            <input type="number" name="surface" step="0.01" class="form-control" value="<?= e($t['surface']) ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-semibold text-secondary">Prix / Heure (DH) *</label>
                                                            <input type="number" name="prix" step="0.01" class="form-control" value="<?= e($t['prix']) ?>" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Statut *</label>
                                                        <select name="statut" class="form-select" required>
                                                            <option value="disponible" <?= $t['statut'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                                                            <option value="reserve" <?= $t['statut'] === 'reserve' ? 'selected' : '' ?>>Réservé</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold text-secondary">Description</label>
                                                        <textarea name="description" class="form-control" rows="3"><?= e($t['description']) ?></textarea>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                        <label class="form-label small fw-semibold text-secondary">Image du terrain (JPG, PNG, WEBP)</label>
                                                        <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                                        <p class="text-muted small m-0 mt-1" style="font-size: 11px;">Laissez vide pour conserver l'image actuelle.</p>
                                                    </div>
                                                    
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-primary py-2 shadow-sm">Enregistrer les modifications</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-layers-half fs-2 text-muted mb-2"></i>
                    <p class="text-muted mb-0">Aucun terrain enregistré dans le système.</p>
                    <button class="btn btn-primary btn-sm mt-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addTerrainModal">Ajouter un terrain</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ADD TERRAIN MODAL -->
<div class="modal fade" id="addTerrainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Ajouter un terrain</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="<?= base_url('admin/terrain/add') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nom du terrain *</label>
                        <input type="text" name="nom" class="form-control" placeholder="Complexe Sportif Oasis" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Ville *</label>
                        <input type="text" name="ville" class="form-control" placeholder="Casablanca" required>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">Surface (m²) *</label>
                            <input type="number" name="surface" step="0.01" class="form-control" placeholder="1200" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-secondary">Prix / Heure (DH) *</label>
                            <input type="number" name="prix" step="0.01" class="form-control" placeholder="350" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Statut *</label>
                        <select name="statut" class="form-select" required>
                            <option value="disponible" selected>Disponible</option>
                            <option value="reserve">Réservé</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Équipements, vestiaires, douches..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary">Image du terrain * (JPG, PNG, WEBP)</label>
                        <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2 shadow-sm">Créer le terrain</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layouts/footer.php'; ?>