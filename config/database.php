<?php

class Database {

    public $conn;

    public function connect() {

        $this->conn = null;

        try {

            $this->conn = new PDO(
                "mysql:host=localhost;dbname=gestion_terrains;charset=utf8mb4",
                "root",
                ""
            );

            $this->conn->exec("SET NAMES utf8mb4");

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch(PDOException $e) {

            echo "Erreur connexion : " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>