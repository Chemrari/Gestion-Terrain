<?php

class ClientController extends Controller {

    public function dashboard() {
        $reservationModel = new Reservation($this->db);
        $userId = $_SESSION['id'];
        
        // Fetch client's reservations
        $reservations = $reservationModel->getByUserId($userId);
        
        // Count statuses
        $stats = [
            'total' => count($reservations),
            'acceptee' => 0,
            'en_attente' => 0,
            'refusee' => 0,
            'terminee' => 0
        ];
        
        foreach ($reservations as $res) {
            $stats[$res['statut']]++;
        }

        // Limit list to 5 recent for dashboard display
        $recentReservations = array_slice($reservations, 0, 5);

        $this->render('client/dashboard', [
            'stats' => $stats,
            'recentReservations' => $recentReservations
        ]);
    }

    public function profile() {
        $userModel = new User($this->db);
        $userData = $userModel->getById($_SESSION['id']);
        
        $this->render('client/profile', [
            'user' => $userData
        ]);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/profile');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de validation CSRF.');
            redirect('client/profile');
        }

        $userId = $_SESSION['id'];
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';

        if (empty($nom) || empty($prenom) || empty($email)) {
            set_flash('error', 'Veuillez remplir les champs obligatoires.');
            redirect('client/profile');
        }

        $userModel = new User($this->db);

        // Check if email taken by someone else
        if ($userModel->emailExists($email, $userId)) {
            set_flash('error', 'Cette adresse email est déjà prise.');
            redirect('client/profile');
        }

        // Update basic info
        if ($userModel->update($userId, $nom, $prenom, $email, $telephone)) {
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['email'] = $email;

            // Password update logic if requested
            if (!empty($new_password)) {
                $user = $userModel->getById($userId);
                if (empty($current_password) || !password_verify($current_password, $user['password'])) {
                    set_flash('error', 'Informations personnelles mises à jour, mais le mot de passe actuel est incorrect.');
                    redirect('client/profile');
                }
                
                if (strlen($new_password) < 6) {
                    set_flash('error', 'Le nouveau mot de passe doit faire au moins 6 caractères.');
                    redirect('client/profile');
                }

                $userModel->updatePassword($userId, password_hash($new_password, PASSWORD_DEFAULT));
                set_flash('success', 'Votre profil et votre mot de passe ont été mis à jour avec succès.');
            } else {
                set_flash('success', 'Votre profil a été mis à jour avec succès.');
            }
        } else {
            set_flash('error', 'Erreur lors de la mise à jour du profil.');
        }

        redirect('client/profile');
    }

    public function terrains() {
        $terrainModel = new Terrain($this->db);
        
        $search = $_GET['search'] ?? '';
        $ville = $_GET['ville'] ?? '';
        $max_prix = $_GET['max_prix'] ?? '';
        $statut = $_GET['statut'] ?? '';
        
        $terrains = $terrainModel->search($search, $ville, $statut, $max_prix);
        $villes = $terrainModel->getDistinctVilles();
        
        $this->render('client/terrains', [
            'terrains' => $terrains,
            'villes' => $villes,
            'search' => $search,
            'selected_ville' => $ville,
            'selected_statut' => $statut,
            'max_prix' => $max_prix
        ]);
    }

    public function terrainDetails() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            set_flash('error', 'Terrain non trouvé.');
            redirect('client/terrains');
        }

        $terrainModel = new Terrain($this->db);
        $terrain = $terrainModel->getById($id);

        if (!$terrain) {
            set_flash('error', 'Terrain inexistant.');
            redirect('client/terrains');
        }

        $this->render('client/terrain_details', [
            'terrain' => $terrain
        ]);
    }

    public function createReservation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/terrains');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de validation CSRF.');
            redirect('client/terrains');
        }

        $userId = $_SESSION['id'];
        $terrainId = (int)($_POST['terrain_id'] ?? 0);
        $date_debut = $_POST['date_debut'] ?? '';
        $date_fin = $_POST['date_fin'] ?? '';

        if ($terrainId <= 0 || empty($date_debut) || empty($date_fin)) {
            set_flash('error', 'Veuillez remplir tous les champs de réservation.');
            redirect('client/terrain/details?id=' . $terrainId);
        }

        // Date Validation
        $today = date('Y-m-d');
        if ($date_debut < $today) {
            set_flash('error', 'La date de début ne peut pas être dans le passé.');
            redirect('client/terrain/details?id=' . $terrainId);
        }

        if ($date_fin < $date_debut) {
            set_flash('error', 'La date de fin doit être supérieure ou égale à la date de début.');
            redirect('client/terrain/details?id=' . $terrainId);
        }

        $terrainModel = new Terrain($this->db);
        $terrain = $terrainModel->getById($terrainId);
        if (!$terrain) {
            set_flash('error', 'Terrain non trouvé.');
            redirect('client/terrains');
        }

        $reservationModel = new Reservation($this->db);

        // Check availability
        if (!$reservationModel->isAvailable($terrainId, $date_debut, $date_fin)) {
            set_flash('error', 'Ce terrain est déjà réservé pour les dates sélectionnées.');
            redirect('client/terrain/details?id=' . $terrainId);
        }

        // Calculate total price: days * daily rate
        $start = new DateTime($date_debut);
        $end = new DateTime($date_fin);
        $days = $start->diff($end)->days + 1;
        $montant_total = $days * $terrain['prix'];

        if ($reservationModel->add($userId, $terrainId, $date_debut, $date_fin, $montant_total, 'en_attente')) {
            set_flash('success', 'Votre réservation a été soumise avec succès et est en attente d\'approbation.');
            redirect('client/reservations');
        } else {
            set_flash('error', 'Erreur lors de la création de la réservation.');
            redirect('client/terrain/details?id=' . $terrainId);
        }
    }

    public function reservations() {
        $reservationModel = new Reservation($this->db);
        $reservations = $reservationModel->getByUserId($_SESSION['id']);
        
        $this->render('client/reservations', [
            'reservations' => $reservations
        ]);
    }

    public function cancelReservation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('client/reservations');
        }

        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de validation CSRF.');
            redirect('client/reservations');
        }

        $resId = (int)($_POST['reservation_id'] ?? 0);
        $reservationModel = new Reservation($this->db);
        $res = $reservationModel->getById($resId);

        if (!$res || $res['user_id'] != $_SESSION['id']) {
            set_flash('error', 'Réservation introuvable.');
            redirect('client/reservations');
        }

        if ($res['statut'] !== 'en_attente') {
            set_flash('error', 'Vous ne pouvez annuler qu\'une réservation en attente.');
            redirect('client/reservations');
        }

        // Update status to 'refusee' (representing canceled)
        if ($reservationModel->updateStatus($resId, 'refusee')) {
            set_flash('success', 'Réservation annulée avec succès.');
        } else {
            set_flash('error', 'Erreur lors de l\'annulation de la réservation.');
        }

        redirect('client/reservations');
    }
}
