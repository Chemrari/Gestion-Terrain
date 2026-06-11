<?php

require_once "../../config/database.php";
require_once "../models/User.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);

    $user->user = [
        "nom" => $_POST["nom"],
        "prenom" => $_POST["prenom"],
        "email" => $_POST["email"],
        "telephone" => $_POST["telephone"],
        "password" => password_hash($_POST["password"], PASSWORD_DEFAULT)
    ];

    if ($user->emailExists()) {

        echo "Email déjà utilisé";
        exit();
    }

    if ($user->register()) {

        header("Location: ../views/login.php");
        exit();
    }

    echo "Erreur inscription";
}
?>