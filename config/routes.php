<?php

return [
    'GET' => [
        '/' => ['HomeController', 'index'],
        'home' => ['HomeController', 'index'],
        'about' => ['HomeController', 'about'],
        'contact' => ['HomeController', 'contact'],
        'login' => ['LoginController', 'showLoginForm'],
        'register' => ['RegisterController', 'showRegisterForm'],
        'logout' => ['LoginController', 'logout'],
        
        // Client Routes
        'client/dashboard' => ['ClientController', 'dashboard', 'auth'],
        'client/profile' => ['ClientController', 'profile', 'auth'],
        'client/terrains' => ['ClientController', 'terrains', 'auth'],
        'client/terrain/details' => ['ClientController', 'terrainDetails', 'auth'],
        'client/reservations' => ['ClientController', 'reservations', 'auth'],
        
        // Admin Routes
        'admin/dashboard' => ['AdminController', 'dashboard', 'admin'],
        'admin/terrains' => ['AdminController', 'terrains', 'admin'],
        'admin/terrain/delete' => ['AdminController', 'deleteTerrain', 'admin'],
        'admin/users' => ['AdminController', 'users', 'admin'],
        'admin/users/delete' => ['AdminController', 'deleteUser', 'admin'],
        'admin/reservations' => ['AdminController', 'reservations', 'admin'],
        'admin/payments' => ['AdminController', 'payments', 'admin'],
        'admin/statistics' => ['AdminController', 'statistics', 'admin'],
    ],
    'POST' => [
        'login' => ['LoginController', 'login'],
        'register' => ['RegisterController', 'register'],
        
        // Client Actions
        'client/profile/update' => ['ClientController', 'updateProfile', 'auth'],
        'client/reserve' => ['ClientController', 'createReservation', 'auth'],
        'client/reservation/cancel' => ['ClientController', 'cancelReservation', 'auth'],
        
        // Admin Actions
        'admin/terrain/add' => ['AdminController', 'addTerrain', 'admin'],
        'admin/terrain/edit' => ['AdminController', 'editTerrain', 'admin'],
        'admin/users/role' => ['AdminController', 'updateUserRole', 'admin'],
        'admin/reservation/status' => ['AdminController', 'updateReservationStatus', 'admin'],
        'admin/payment/add' => ['AdminController', 'addPayment', 'admin'],
    ]
];
