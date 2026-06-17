<?php
define('ROOT_PATH', __DIR__);
require_once 'config/database.php';
$db   = new Database();
$conn = $db->connect();

// Test admin
$stmt = $conn->prepare('SELECT email, password, role FROM users WHERE email = ?');
$stmt->execute(['admin@terrains.com']);
$u = $stmt->fetch(PDO::FETCH_ASSOC);
echo 'Admin found: ' . ($u ? 'YES' : 'NO') . PHP_EOL;
if ($u) {
    echo 'Admin password_verify: ' . (password_verify('admin123', $u['password']) ? 'OK ✓' : 'FAIL ✗') . PHP_EOL;
}

// Test client
$stmt->execute(['amine.rachidi@example.com']);
$u2 = $stmt->fetch(PDO::FETCH_ASSOC);
echo 'Client found: ' . ($u2 ? 'YES' : 'NO') . PHP_EOL;
if ($u2) {
    echo 'Client password_verify: ' . (password_verify('client123', $u2['password']) ? 'OK ✓' : 'FAIL ✗') . PHP_EOL;
}
echo PHP_EOL . 'DB test complete.' . PHP_EOL;
