<?php

define('ROOT_PATH', dirname(__DIR__));
session_start();
require_once ROOT_PATH . '/app/helpers/functions.php';

function run_security_tests() {
    echo "=== RUNNING SECURITY TESTS ===\n";
    
    // Test 1: XSS escaping
    echo "Test 1: Échappement HTML (XSS Prevention)... ";
    $maliciousInput = "<script>alert('xss')</script>";
    $escaped = e($maliciousInput);
    
    $expected = htmlspecialchars($maliciousInput, ENT_QUOTES, 'UTF-8');
    
    if ($escaped === $expected && strpos($escaped, '<script>') === false) {
        echo "SUCCESS (Entités HTML encodées)\n";
    } else {
        echo "FAIL (Code script non échappé)\n";
    }
    
    // Test 2: CSRF Token Generation
    echo "Test 2: Génération du jeton CSRF... ";
    $token1 = csrf_token();
    $token2 = csrf_token();
    
    if (!empty($token1) && $token1 === $token2) {
        echo "SUCCESS (Jeton constant par session)\n";
    } else {
        echo "FAIL\n";
    }
    
    // Test 3: CSRF Token Verification
    echo "Test 3: Vérification du jeton CSRF... ";
    $valid = verify_csrf_token($token1);
    $invalid = verify_csrf_token("invalid_token_value_here");
    
    if ($valid && !$invalid) {
        echo "SUCCESS (Validation correcte)\n";
    } else {
        echo "FAIL\n";
    }
    
    echo "==============================\n\n";
}

run_security_tests();
