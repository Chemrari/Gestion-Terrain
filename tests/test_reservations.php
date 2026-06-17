<?php

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/Terrain.php';
require_once ROOT_PATH . '/app/models/Reservation.php';

function run_reservation_tests() {
    echo "=== RUNNING RESERVATION OVERLAP TESTS ===\n";
    
    $database = new Database();
    $db = $database->connect();
    
    $userModel = new User($db);
    $terrainModel = new Terrain($db);
    $reservationModel = new Reservation($db);
    
    // Create temp user
    $email = "test_user_res_" . uniqid() . "@example.com";
    $userModel->register("User", "Test", $email, "060", "hash", "client");
    $user = $userModel->getByEmail($email);
    $userId = $user['id'];
    
    // Create temp terrain
    $terrainModel->add("Terrain Test Res", "Rabat", 300, 1000, "Desc", "img.jpg", "disponible");
    // Fetch last inserted terrain
    $stmt = $db->query("SELECT MAX(id) as id FROM terrains");
    $terrainId = (int)$stmt->fetch(PDO::FETCH_ASSOC)['id'];
    
    // Add reference booking: June 15 to June 18
    echo "Création d'une réservation de référence (15/06 au 18/06)... ";
    $resId = $reservationModel->add($userId, $terrainId, '2026-06-15', '2026-06-18', 1200.00, 'acceptee');
    if ($resId) {
        echo "SUCCESS (ID: $resId)\n";
    } else {
        echo "FAIL\n";
    }
    
    // Test 1: Full overlap (16/06 to 17/06)
    echo "Test 1: Recouvrement complet (16/06 au 17/06) - Doit être bloqué... ";
    $available = $reservationModel->isAvailable($terrainId, '2026-06-16', '2026-06-17');
    if (!$available) {
        echo "SUCCESS (Bloqué correctement)\n";
    } else {
        echo "FAIL (Réservation acceptée par erreur)\n";
    }
    
    // Test 2: Overlap start (14/06 to 16/06)
    echo "Test 2: Recouvrement début (14/06 au 16/06) - Doit être bloqué... ";
    $available = $reservationModel->isAvailable($terrainId, '2026-06-14', '2026-06-16');
    if (!$available) {
        echo "SUCCESS (Bloqué correctement)\n";
    } else {
        echo "FAIL (Réservation acceptée par erreur)\n";
    }
    
    // Test 3: No overlap (19/06 to 20/06)
    echo "Test 3: Sans recouvrement (19/06 au 20/06) - Doit être disponible... ";
    $available = $reservationModel->isAvailable($terrainId, '2026-06-19', '2026-06-20');
    if ($available) {
        echo "SUCCESS (Disponible)\n";
    } else {
        echo "FAIL (Bloqué à tort)\n";
    }
    
    // Clean up
    echo "Nettoyage des données... ";
    $userModel->delete($userId);
    $terrainModel->delete($terrainId);
    echo "SUCCESS\n";
    
    echo "==========================================\n\n";
}

run_reservation_tests();
