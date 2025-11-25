<?php
session_start();
require "fonctions.php";

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        die("Veuillez remplir tous les champs.");
    }

    $user = getUserByEmail($pdo, $email);

    if (!$user) {
        die("Email ou mot de passe incorrect.");
    }

    if (!password_verify($password, $user['password'])) {
        die("Email ou mot de passe incorrect.");
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nom'] = $user['nom'];

    header("Location: tableau.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>Connexion</h2>

<form method="POST">
    Email :<br>
    <input type="email" name="email" required><br><br>

    Mot de passe :<br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

</body>
</html>