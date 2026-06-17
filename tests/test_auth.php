<?php

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/models/User.php';

function run_auth_tests() {
    echo "=== RUNNING AUTHENTICATION TESTS ===\n";
    
    $database = new Database();
    $db = $database->connect();
    
    if (!$db) {
        echo "FAIL: Impossible de se connecter à la base de données.\n";
        exit(1);
    }
    
    $userModel = new User($db);
    
    $testEmail = "test_" . uniqid() . "@example.com";
    $testPassword = "password123";
    $hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);
    
    // Test 1: Registration
    echo "Test 1: Inscription d'un utilisateur... ";
    $registered = $userModel->register("TestNom", "TestPrenom", $testEmail, "0600000000", $hashedPassword, "client");
    if ($registered) {
        echo "SUCCESS\n";
    } else {
        echo "FAIL\n";
    }
    
    // Test 2: Duplicate check
    echo "Test 2: Détection d'email en doublon... ";
    if ($userModel->emailExists($testEmail)) {
        echo "SUCCESS\n";
    } else {
        echo "FAIL\n";
    }
    
    // Test 3: Credentials check
    echo "Test 3: Vérification de la connexion... ";
    $user = $userModel->getByEmail($testEmail);
    if ($user && password_verify($testPassword, $user['password'])) {
        echo "SUCCESS\n";
    } else {
        echo "FAIL\n";
    }
    
    // Clean up
    echo "Nettoyage des données de test... ";
    if ($user) {
        $userModel->delete($user['id']);
        echo "SUCCESS\n";
    } else {
        echo "FAIL (Impossible de supprimer le compte de test)\n";
    }
    
    echo "=====================================\n\n";
}

run_auth_tests();
