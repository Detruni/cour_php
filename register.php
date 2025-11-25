<?php
session_start();
require "fonctions.php";

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($nom === "" || $email === "" || $password === "") {
        die("Tous les champs sont obligatoires.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email invalide.");
    }

    if (emailExiste($pdo, $email)) {
        die("Cet email existe déjà.");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if (creerUtilisateur($pdo, $nom, $email, $passwordHash)) {
        echo "Inscription réussie. <a href='login.php'>Se connecter</a>";
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<h2>Inscription</h2>

<form method="POST">
    Nom :<br>
    <input type="text" name="nom" required><br><br>
    
    Email :<br>
    <input type="email" name="email" required><br><br>
    
    Mot de passe :<br>
    <input type="password" name="password" required><br><br>
    
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>