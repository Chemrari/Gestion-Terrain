<?php

class Terrain
{
    private $conn;
    private $table = "terrains";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function add($nom, $emplacement, $prix, $image, $localisation)
    {
        $query = "INSERT INTO $this->table
                  (nom, emplacement, prix, image, localisation)
                  VALUES
                  (:nom, :emplacement, :prix, :image, :localisation)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':emplacement', $emplacement);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':localisation', $localisation);

        return $stmt->execute();
    }

    public function getAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function delete($id)
    {
        $query = "DELETE FROM $this->table WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }


    public function update($id, $nom, $emplacement, $prix, $image, $localisation)
    {
        $query = "UPDATE $this->table
                  SET nom = :nom,
                      emplacement = :emplacement,
                      prix = :prix,
                      image = :image,
                      localisation = :localisation
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':emplacement', $emplacement);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':localisation', $localisation);

        return $stmt->execute();
    }
}