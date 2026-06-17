<?php

class HomeController extends Controller {

    public function index() {
        $terrainModel = new Terrain($this->db);
        
        // Fetch parameters for search and filtering
        $search = $_GET['search'] ?? '';
        $ville = $_GET['ville'] ?? '';
        $max_prix = $_GET['max_prix'] ?? '';
        $statut = 'disponible'; // Only available terrains on landing page search
        
        // Perform search
        $terrains = $terrainModel->search($search, $ville, $statut, $max_prix);
        $villes = $terrainModel->getDistinctVilles();
        
        $this->render('home', [
            'terrains' => $terrains,
            'villes' => $villes,
            'search' => $search,
            'selected_ville' => $ville,
            'max_prix' => $max_prix
        ]);
    }

    public function about() {
        $this->render('about');
    }

    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
                set_flash('error', 'Token CSRF invalide.');
                redirect('contact');
            }
            
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $sujet = trim($_POST['sujet'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            if (empty($nom) || empty($email) || empty($message)) {
                set_flash('error', 'Veuillez remplir tous les champs obligatoires.');
                redirect('contact');
            }
            
            // In a real application, you'd send an email or store this in a 'messages' table.
            set_flash('success', 'Votre message a bien été envoyé ! Notre équipe vous contactera dans les plus brefs délais.');
            redirect('contact');
        }
        
        $this->render('contact');
    }
}
