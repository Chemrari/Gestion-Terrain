<?php

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/Terrain.php';

function run_crud_tests() {
    echo "=== RUNNING CRUD TESTS ===\n";
    
    $database = new Database();
    $db = $database->connect();
    
    $terrainModel = new Terrain($db);
    
    // Test 1: Create
    echo "Test 1: Ajout d'un terrain... ";
    $nom = "Terrain CRUD Test";
    $ville = "Casablanca";
    $prix = 250.00;
    $surface = 600.00;
    $desc = "Un terrain de test pour le CRUD.";
    $img = "crud_test.jpg";
    
    $created = $terrainModel->add($nom, $ville, $prix, $surface, $desc, $img, "disponible");
    
    // Get ID
    $stmt = $db->query("SELECT MAX(id) as id FROM terrains");
    $terrainId = (int)$stmt->fetch(PDO::FETCH_ASSOC)['id'];
    
    if ($created && $terrainId > 0) {
        echo "SUCCESS (ID: $terrainId)\n";
    } else {
        echo "FAIL\n";
    }
    
    // Test 2: Read
    echo "Test 2: Lecture des détails... ";
    $terrain = $terrainModel->getById($terrainId);
    if ($terrain && $terrain['nom'] === $nom && (float)$terrain['prix'] === $prix) {
        echo "SUCCESS\n";
    } else {
        echo "FAIL\n";
    }
    
    // Test 3: Update
    echo "Test 3: Modification du terrain... ";
    $newNom = "Terrain CRUD Test (Modifié)";
    $newPrix = 280.00;
    $updated = $terrainModel->update($terrainId, $newNom, $ville, $newPrix, $surface, $desc, $img, "reserve");
    
    $terrainUpdated = $terrainModel->getById($terrainId);
    if ($updated && $terrainUpdated['nom'] === $newNom && (float)$terrainUpdated['prix'] === $newPrix && $terrainUpdated['statut'] === 'reserve') {
        echo "SUCCESS\n";
    } else {
        echo "FAIL\n";
    }
    
    // Test 4: Delete
    echo "Test 4: Suppression... ";
    $deleted = $terrainModel->delete($terrainId);
    $terrainDeleted = $terrainModel->getById($terrainId);
    if ($deleted && !$terrainDeleted) {
        echo "SUCCESS\n";
    } else {
        echo "FAIL\n";
    }
    
    echo "==========================\n\n";
}

run_crud_tests();
