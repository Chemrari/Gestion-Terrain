<?php

class Reservation {
    private $conn;
    private $table = "reservations";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($user_id, $terrain_id, $date_debut, $date_fin, $montant_total, $statut = 'en_attente') {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, terrain_id, date_debut, date_fin, montant_total, statut) 
                  VALUES (:user_id, :terrain_id, :date_debut, :date_fin, :montant_total, :statut)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':terrain_id', $terrain_id, PDO::PARAM_INT);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        $stmt->bindParam(':montant_total', $montant_total);
        $stmt->bindParam(':statut', $statut);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function isAvailable($terrain_id, $date_debut, $date_fin, $excludeReservationId = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE terrain_id = :terrain_id 
                    AND statut IN ('acceptee', 'en_attente', 'terminee')
                    AND date_debut <= :date_fin 
                    AND date_fin >= :date_debut";
                    
        if ($excludeReservationId !== null) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':terrain_id', $terrain_id, PDO::PARAM_INT);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        
        if ($excludeReservationId !== null) {
            $stmt->bindParam(':exclude_id', $excludeReservationId, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'] === 0;
    }

    public function getById($id) {
        $query = "SELECT r.*, t.nom as terrain_nom, t.prix as terrain_prix, t.ville as terrain_ville, 
                         u.nom as user_nom, u.prenom as user_prenom, u.email as user_email
                  FROM " . $this->table . " r
                  JOIN terrains t ON r.terrain_id = t.id
                  JOIN users u ON r.user_id = u.id
                  WHERE r.id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $query = "SELECT r.*, t.nom as terrain_nom, t.ville as terrain_ville, t.image as terrain_image,
                         p.statut as paiement_statut
                  FROM " . $this->table . " r
                  JOIN terrains t ON r.terrain_id = t.id
                  LEFT JOIN paiements p ON p.reservation_id = r.id
                  WHERE r.user_id = :user_id 
                  ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithDetails() {
        $query = "SELECT r.*, t.nom as terrain_nom, t.ville as terrain_ville, 
                         u.nom as user_nom, u.prenom as user_prenom, u.email as user_email, u.telephone as user_tel,
                         p.statut as paiement_statut, p.id as paiement_id
                  FROM " . $this->table . " r
                  JOIN terrains t ON r.terrain_id = t.id
                  JOIN users u ON r.user_id = u.id
                  LEFT JOIN paiements p ON p.reservation_id = r.id
                  ORDER BY r.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $statut) {
        $query = "UPDATE " . $this->table . " SET statut = :statut WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':statut', $statut);
        return $stmt->execute();
    }

    public function countTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    public function countByStatus($statut) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE statut = :statut";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $statut);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    public function getMonthlyStats() {
        // Returns last 6 months reservation counts
        $query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as mois, COUNT(*) as total 
                  FROM " . $this->table . "
                  GROUP BY mois 
                  ORDER BY mois ASC 
                  LIMIT 6";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopularTerrains($limit = 3) {
        $query = "SELECT t.nom, COUNT(r.id) as reservations_count
                  FROM " . $this->table . " r
                  JOIN terrains t ON r.terrain_id = t.id
                  GROUP BY t.id
                  ORDER BY reservations_count DESC
                  LIMIT :limit";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
