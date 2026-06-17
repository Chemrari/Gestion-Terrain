<?php

class Terrain {
    private $conn;
    private $table = "terrains";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($nom, $ville, $prix, $surface, $description, $image, $statut = 'disponible') {
        $query = "INSERT INTO " . $this->table . " 
                  (nom, ville, prix, surface, description, image, statut) 
                  VALUES (:nom, :ville, :prix, :surface, :description, :image, :statut)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':surface', $surface);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':statut', $statut);
        
        return $stmt->execute();
    }

    public function update($id, $nom, $ville, $prix, $surface, $description, $image, $statut) {
        $query = "UPDATE " . $this->table . " 
                  SET nom = :nom, 
                      ville = :ville, 
                      prix = :prix, 
                      surface = :surface, 
                      description = :description, 
                      image = :image, 
                      statut = :statut 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':surface', $surface);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':statut', $statut);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($search = '', $ville = '', $statut = '', $max_prix = '') {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (nom LIKE :search OR description LIKE :search)";
            $searchVal = "%" . $search . "%";
            $params[':search'] = $searchVal;
        }
        
        if (!empty($ville)) {
            $query .= " AND ville = :ville";
            $params[':ville'] = $ville;
        }
        
        if (!empty($statut)) {
            $query .= " AND statut = :statut";
            $params[':statut'] = $statut;
        }
        
        if (!empty($max_prix)) {
            $query .= " AND prix <= :max_prix";
            $params[':max_prix'] = (float)$max_prix;
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => &$val) {
            if ($key === ':max_prix') {
                $stmt->bindParam($key, $val, PDO::PARAM_STR); // PDO can handle float/double as string bind parameters safely
            } else {
                $stmt->bindParam($key, $val);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    public function getDistinctVilles() {
        $query = "SELECT DISTINCT ville FROM " . $this->table . " ORDER BY ville ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updateStatus($id, $statut) {
        $query = "UPDATE " . $this->table . " SET statut = :statut WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':statut', $statut);
        return $stmt->execute();
    }
}