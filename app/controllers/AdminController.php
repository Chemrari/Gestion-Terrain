<?php

class AdminController extends Controller {

    public function dashboard() {
        $terrainModel = new Terrain($this->db);
        $userModel = new User($this->db);
        $reservationModel = new Reservation($this->db);
        $paiementModel = new Paiement($this->db);

        // Fetch counts
        $counts = [
            'terrains' => $terrainModel->countTotal(),
            'clients' => $userModel->countClients(),
            'reservations' => $reservationModel->countTotal(),
            'pending' => $reservationModel->countByStatus('en_attente'),
            'accepted' => $reservationModel->countByStatus('acceptee'),
            'revenue' => $paiementModel->sumTotalRevenue()
        ];

        // Fetch recent reservations (limit 5)
        $recentReservations = array_slice($reservationModel->getAllWithDetails(), 0, 5);

        // Fetch chart statistics
        $monthlyReservations = $reservationModel->getMonthlyStats();
        $monthlyRevenue = $paiementModel->getMonthlyRevenue();
        $popularTerrains = $reservationModel->getPopularTerrains(5);

        $this->render('admin/dashboard', [
            'counts' => $counts,
            'recentReservations' => $recentReservations,
            'monthlyReservations' => $monthlyReservations,
            'monthlyRevenue' => $monthlyRevenue,
            'popularTerrains' => $popularTerrains
        ]);
    }

    public function terrains() {
        $terrainModel = new Terrain($this->db);
        $terrains = $terrainModel->getAll();
        
        $this->render('admin/terrains', [
            'terrains' => $terrains
        ]);
    }

    public function addTerrain() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/terrains');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de la validation CSRF.');
            redirect('admin/terrains');
        }

        $nom = trim($_POST['nom'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        $prix = (float)($_POST['prix'] ?? 0.0);
        $surface = (float)($_POST['surface'] ?? 0.0);
        $description = trim($_POST['description'] ?? '');
        $statut = $_POST['statut'] ?? 'disponible';
        
        if (empty($nom) || empty($ville) || $prix <= 0 || $surface <= 0) {
            set_flash('error', 'Veuillez remplir tous les champs obligatoires correctement.');
            redirect('admin/terrains');
        }

        // Image upload handling
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowedTypes)) {
                set_flash('error', 'Format d\'image non supporté. Formats autorisés : JPG, JPEG, PNG, WEBP.');
                redirect('admin/terrains');
            }

            if ($file['size'] > $maxSize) {
                set_flash('error', 'La taille de l\'image ne doit pas dépasser 5 Mo.');
                redirect('admin/terrains');
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('terrain_') . '.' . $ext;
            $uploadPath = ROOT_PATH . '/public/uploads/' . $imageName;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                set_flash('error', 'Erreur lors du téléchargement de l\'image.');
                redirect('admin/terrains');
            }
        }

        $terrainModel = new Terrain($this->db);
        if ($terrainModel->add($nom, $ville, $prix, $surface, $description, $imageName, $statut)) {
            set_flash('success', 'Le terrain a été ajouté avec succès.');
        } else {
            set_flash('error', 'Une erreur est survenue lors de l\'ajout du terrain.');
        }

        redirect('admin/terrains');
    }

    public function editTerrain() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/terrains');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de la validation CSRF.');
            redirect('admin/terrains');
        }

        $id = (int)($_POST['id'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $ville = trim($_POST['ville'] ?? '');
        $prix = (float)($_POST['prix'] ?? 0.0);
        $surface = (float)($_POST['surface'] ?? 0.0);
        $description = trim($_POST['description'] ?? '');
        $statut = $_POST['statut'] ?? 'disponible';

        $terrainModel = new Terrain($this->db);
        $terrain = $terrainModel->getById($id);

        if (!$terrain) {
            set_flash('error', 'Terrain introuvable.');
            redirect('admin/terrains');
        }

        if (empty($nom) || empty($ville) || $prix <= 0 || $surface <= 0) {
            set_flash('error', 'Veuillez remplir les champs requis.');
            redirect('admin/terrains');
        }

        $imageName = $terrain['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowedTypes)) {
                set_flash('error', 'Format d\'image invalide.');
                redirect('admin/terrains');
            }

            if ($file['size'] > $maxSize) {
                set_flash('error', 'Image trop volumineuse (max 5 Mo).');
                redirect('admin/terrains');
            }

            // Delete old image if exists
            if (!empty($terrain['image'])) {
                $oldImagePath = ROOT_PATH . '/public/uploads/' . $terrain['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('terrain_') . '.' . $ext;
            $uploadPath = ROOT_PATH . '/public/uploads/' . $imageName;

            move_uploaded_file($file['tmp_name'], $uploadPath);
        }

        if ($terrainModel->update($id, $nom, $ville, $prix, $surface, $description, $imageName, $statut)) {
            set_flash('success', 'Le terrain a été mis à jour avec succès.');
        } else {
            set_flash('error', 'Erreur lors de la mise à jour.');
        }

        redirect('admin/terrains');
    }

    public function deleteTerrain() {
        $id = (int)($_GET['id'] ?? 0);
        
        $terrainModel = new Terrain($this->db);
        $terrain = $terrainModel->getById($id);

        if (!$terrain) {
            set_flash('error', 'Terrain introuvable.');
            redirect('admin/terrains');
        }

        // Delete image file if exists
        if (!empty($terrain['image'])) {
            $imagePath = ROOT_PATH . '/public/uploads/' . $terrain['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($terrainModel->delete($id)) {
            set_flash('success', 'Le terrain a été supprimé avec succès.');
        } else {
            set_flash('error', 'Erreur lors de la suppression.');
        }

        redirect('admin/terrains');
    }

    public function users() {
        $userModel = new User($this->db);
        $users = $userModel->getAll();
        
        $this->render('admin/users', [
            'users' => $users
        ]);
    }

    public function updateUserRole() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/users');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de la validation CSRF.');
            redirect('admin/users');
        }

        $id = (int)($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? 'client';

        if ($id === (int)$_SESSION['id']) {
            set_flash('error', 'Vous ne pouvez pas modifier votre propre rôle.');
            redirect('admin/users');
        }

        $userModel = new User($this->db);
        if ($userModel->updateRole($id, $role)) {
            set_flash('success', 'Le rôle de l\'utilisateur a été mis à jour.');
        } else {
            set_flash('error', 'Erreur lors de la mise à jour du rôle.');
        }

        redirect('admin/users');
    }

    public function deleteUser() {
        $id = (int)($_GET['id'] ?? 0);

        if ($id === (int)$_SESSION['id']) {
            set_flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            redirect('admin/users');
        }

        $userModel = new User($this->db);
        if ($userModel->delete($id)) {
            set_flash('success', 'L\'utilisateur a été supprimé avec succès.');
        } else {
            set_flash('error', 'Erreur lors de la suppression.');
        }

        redirect('admin/users');
    }

    public function reservations() {
        $reservationModel = new Reservation($this->db);
        $reservations = $reservationModel->getAllWithDetails();
        
        $this->render('admin/reservations', [
            'reservations' => $reservations
        ]);
    }

    public function updateReservationStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/reservations');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de la validation CSRF.');
            redirect('admin/reservations');
        }

        $id = (int)($_POST['reservation_id'] ?? 0);
        $status = $_POST['statut'] ?? 'en_attente';

        $reservationModel = new Reservation($this->db);
        $res = $reservationModel->getById($id);

        if (!$res) {
            set_flash('error', 'Réservation introuvable.');
            redirect('admin/reservations');
        }

        if ($reservationModel->updateStatus($id, $status)) {
            // If reservation status is accepted or completed, let's update the terrain status as well if required
            $terrainModel = new Terrain($this->db);
            if ($status === 'acceptee') {
                $terrainModel->updateStatus($res['terrain_id'], 'reserve');
            } elseif ($status === 'terminee' || $status === 'refusee') {
                $terrainModel->updateStatus($res['terrain_id'], 'disponible');
            }
            
            set_flash('success', 'Le statut de la réservation a été mis à jour.');
        } else {
            set_flash('error', 'Erreur lors de la mise à jour.');
        }

        redirect('admin/reservations');
    }

    public function payments() {
        $paiementModel = new Paiement($this->db);
        $payments = $paiementModel->getAllWithDetails();

        // Fetch accepted reservations that do not have payment yet
        $reservationModel = new Reservation($this->db);
        $reservations = $reservationModel->getAllWithDetails();
        $unpaidReservations = [];
        
        foreach ($reservations as $r) {
            if ($r['statut'] === 'acceptee' && empty($r['paiement_statut'])) {
                $unpaidReservations[] = $r;
            }
        }

        $this->render('admin/payments', [
            'payments' => $payments,
            'unpaidReservations' => $unpaidReservations
        ]);
    }

    public function addPayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/payments');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de la validation CSRF.');
            redirect('admin/payments');
        }

        $reservationId = (int)($_POST['reservation_id'] ?? 0);
        $montant = (float)($_POST['montant'] ?? 0.0);
        $mode_paiement = $_POST['mode_paiement'] ?? 'espece';
        $date_paiement = $_POST['date_paiement'] ?? date('Y-m-d');

        if ($reservationId <= 0 || $montant <= 0) {
            set_flash('error', 'Informations de paiement invalides.');
            redirect('admin/payments');
        }

        $paiementModel = new Paiement($this->db);
        if ($paiementModel->add($reservationId, $montant, $mode_paiement, $date_paiement, 'paye')) {
            // Optionally update reservation status to terminee when paid
            $reservationModel = new Reservation($this->db);
            $reservationModel->updateStatus($reservationId, 'terminee');
            
            // Re-open terrain status as disponible after completion
            $res = $reservationModel->getById($reservationId);
            if ($res) {
                $terrainModel = new Terrain($this->db);
                $terrainModel->updateStatus($res['terrain_id'], 'disponible');
            }

            set_flash('success', 'Le paiement a été enregistré avec succès et la réservation est maintenant clôturée.');
        } else {
            set_flash('error', 'Erreur lors de l\'enregistrement du paiement.');
        }

        redirect('admin/payments');
    }

    public function statistics() {
        $reservationModel = new Reservation($this->db);
        $paiementModel = new Paiement($this->db);

        $monthlyReservations = $reservationModel->getMonthlyStats();
        $monthlyRevenue = $paiementModel->getMonthlyRevenue();
        $popularTerrains = $reservationModel->getPopularTerrains(5);

        $this->render('admin/statistics', [
            'monthlyReservations' => $monthlyReservations,
            'monthlyRevenue' => $monthlyRevenue,
            'popularTerrains' => $popularTerrains
        ]);
    }
}
