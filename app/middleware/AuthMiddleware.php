<?php

class AuthMiddleware {
    
    public static function handleClient() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['id'])) {
            set_flash('error', 'Vous devez être connecté pour accéder à cette page.');
            redirect('login');
        }
    }
    
    public static function handleAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['id'])) {
            set_flash('error', 'Vous devez vous connecter en tant qu\'administrateur.');
            redirect('login');
        }
        
        if ($_SESSION['role'] !== 'admin') {
            set_flash('error', 'Accès interdit. Cette zone est réservée aux administrateurs.');
            redirect('client/dashboard');
        }
    }
}
