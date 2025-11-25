<?php
session_start();
require "fonctions.php";

requireLogin();

if ($_SESSION['user_role'] != 1) {
    header("Location: profil.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
</head>
<body>
    <h1 style="color:red">INTERFACE ADMINISTRATEUR</h1>
    <p>Bienvenue dans la zone sécurisée.</p>

    <a href="logout.php">Se déconnecter</a>
</body>
</html>