<?php

class LoginController extends Controller {

    public function showLoginForm() {
        if (is_logged_in()) {
            if (is_admin()) {
                redirect('admin/dashboard');
            } else {
                redirect('client/dashboard');
            }
        }
        $this->render('login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }

        // CSRF Check
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash('error', 'Échec de la validation de sécurité (CSRF). Veuillez réessayer.');
            redirect('login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            set_flash('error', 'Veuillez saisir votre email et votre mot de passe.');
            redirect('login');
        }

        $userModel = new User($this->db);
        $user = $userModel->getByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID for security (prevent session fixation)
            session_regenerate_id(true);

            $_SESSION['id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Remember me logic
            if (isset($_POST['remember'])) {
                // Set cookie for 30 days
                setcookie('remember_user', $user['email'], time() + (86400 * 30), "/");
            } else {
                if (isset($_COOKIE['remember_user'])) {
                    setcookie('remember_user', '', time() - 3600, "/");
                }
            }

            set_flash('success', 'Connexion réussie. Bienvenue ' . htmlspecialchars($user['prenom']) . ' !');

            if ($user['role'] === 'admin') {
                redirect('admin/dashboard');
            } else {
                redirect('client/dashboard');
            }
        }

        set_flash('error', 'Adresse email ou mot de passe incorrect.');
        redirect('login');
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear session variables
        $_SESSION = [];
        
        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
        
        // Restart session to store flash message
        session_start();
        set_flash('success', 'Vous avez été déconnecté avec succès.');
        redirect('login');
    }
}