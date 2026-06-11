<?php

session_start();

require_once "../../config/database.php";
require_once "../models/User.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);

    $user->user = [
        "email" => $_POST["email"],
        "password" => $_POST["password"]
    ];

    $data = $user->login();

    if ($data) {

        if (password_verify($user->user["password"], $data["password"])) {

            $_SESSION["id"] = $data["id"];
            $_SESSION["nom"] = $data["nom"];
            $_SESSION["role"] = $data["role"];

            if ($data["role"] == "admin") {

                header("Location: ../views/admin/terrains.php");
                exit();
            }

            header("Location: ../views/client/home.php");
            exit();
        }
    }

    echo "Email ou mot de passe incorrect";
}
?>