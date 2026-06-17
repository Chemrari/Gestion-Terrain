<?php

class RegisterController extends Controller {

    public function showRegisterForm() {
        if (is_logged_in()) {
            if (is_admin()) {
                redirect('admin/dashboard');
            } else {
                redirect('client/dashboard');
            }
        }
        $this->render('register');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('register');
        }

        // CSRF check
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de la validation de sécurité (CSRF). Veuillez réessayer.');
            redirect('register');
        }

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Form Validation
        if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
            set_flash('error', 'Veuillez remplir tous les champs obligatoires.');
            redirect('register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_flash('error', 'Veuillez entrer une adresse email valide.');
            redirect('register');
        }

        if (strlen($password) < 6) {
            set_flash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
            redirect('register');
        }

        if ($password !== $confirm_password) {
            set_flash('error', 'Les mots de passe ne correspondent pas.');
            redirect('register');
        }

        $userModel = new User($this->db);
        
        // Email Uniqueness Check
        if ($userModel->emailExists($email)) {
            set_flash('error', 'Cette adresse email est déjà enregistrée.');
            redirect('register');
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Register user
        if ($userModel->register($nom, $prenom, $email, $telephone, $hashedPassword, 'client')) {
            set_flash('success', 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.');
            redirect('login');
        } else {
            set_flash('error', 'Une erreur s\'est produite lors de la création de votre compte. Veuillez réessayer.');
            redirect('register');
        }
    }
}