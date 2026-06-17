<?php

class Controller {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    protected function render($view, $data = []) {
        extract($data);
        
        $viewFile = ROOT_PATH . '/app/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            http_response_code(500);
            echo "<h1>Erreur Système</h1><p>La vue '$view' n'existe pas.</p>";
            exit();
        }
    }
}
