<?php

class Paiement {
    private $conn;
    private $table = "paiements";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($reservation_id, $montant, $mode_paiement, $date_paiement, $statut = 'paye') {
        $query = "INSERT INTO " . $this->table . " 
                  (reservation_id, montant, mode_paiement, date_paiement, statut) 
                  VALUES (:reservation_id, :montant, :mode_paiement, :date_paiement, :statut)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
        $stmt->bindParam(':montant', $montant);
        $stmt->bindParam(':mode_paiement', $mode_paiement);
        $stmt->bindParam(':date_paiement', $date_paiement);
        $stmt->bindParam(':statut', $statut);
        
        return $stmt->execute();
    }

    public function getByReservationId($reservation_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE reservation_id = :reservation_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllWithDetails() {
        $query = "SELECT p.*, r.date_debut, r.date_fin, t.nom as terrain_nom, u.nom as user_nom, u.prenom as user_prenom
                  FROM " . $this->table . " p
                  JOIN reservations r ON p.reservation_id = r.id
                  JOIN terrains t ON r.terrain_id = t.id
                  JOIN users u ON r.user_id = u.id
                  ORDER BY p.date_paiement DESC, p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sumTotalRevenue() {
        $query = "SELECT SUM(montant) as total FROM " . $this->table . " WHERE statut = 'paye'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (double)($row['total'] ?? 0.0);
    }

    public function getMonthlyRevenue() {
        // Last 6 months revenues
        $query = "SELECT DATE_FORMAT(date_paiement, '%Y-%m') as mois, SUM(montant) as total 
                  FROM " . $this->table . "
                  WHERE statut = 'paye'
                  GROUP BY mois 
                  ORDER BY mois ASC 
                  LIMIT 6";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
