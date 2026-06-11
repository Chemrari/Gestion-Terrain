<?php

class User {

    private $conn;

    public $user = [];

    public function __construct($db) {

        $this->conn = $db;
    }

    public function register() {

        $stmt = $this->conn->prepare(
            "INSERT INTO users (nom, prenom, email, telephone, password)
             VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $this->user['nom'],
            $this->user['prenom'],
            $this->user['email'],
            $this->user['telephone'],
            $this->user['password']
        ]);
    }

    public function emailExists() {

        $stmt = $this->conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $stmt->execute([$this->user['email']]);

        return $stmt->rowCount() > 0;
    }
    
    
    public function login() {

    $stmt = $this->conn->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->execute([
        $this->user['email']
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
?>