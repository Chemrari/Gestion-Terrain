<?php

session_start();

require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../models/Terrain.php";

$db = new Database();
$conn = $db->connect();

$terrain = new Terrain($conn);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {

    $nom = $_POST['nom'];
    $emplacement = $_POST['emplacement'];
    $prix = $_POST['prix'];
    $localisation = $_POST['localisation'];

    $imageName = null;

    if (!empty($_FILES['image']['name'])) {

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . "." . $ext;

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            __DIR__ . "/../../uploads/" . $imageName
        );
    }

    $terrain->add($nom, $emplacement, $prix, $imageName, $localisation);

    header("Location: TerrainController.php");
    exit();
}

if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];
    $terrain->delete($id);

    header("Location: TerrainController.php");
    exit();
}


$terrains = $terrain->getAll();

require_once __DIR__ . "/../views/admin/terrains.php";