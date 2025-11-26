<?php
session_start();
require "fonctions.php";

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Nettoyage des entrées avec trim() pour enlever les espaces inutiles
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Vérification que tous les champs sont remplis
    if ($nom === "" || $email === "" || $adresse === "" || $password === "" || $confirm_password === "") {
        die("Tous les champs sont obligatoires.");
    }

    // Validation Email via Regex (Format standard : texte@domaine.extension)
    if (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/', $email)) {
        die("Format d'email invalide.");
    }

    // Validation Mot de passe (Regex : Min 8 chars, 1 lettre, 1 chiffre)
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password)) {
        die("Le mot de passe doit contenir au moins 8 caractères, dont une lettre et un chiffre.");
    }
 
    // Vérification que les deux mots de passe sont identiques
    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    // Vérification en BDD si l'email est déjà pris
    if (emailExiste($pdo, $email)) {
        die("Cet email existe déjà.");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertion en base de données
    if (creerUtilisateur($pdo, $nom, $email, $adresse, $passwordHash)) {
        echo "Inscription réussie. <a href='login.php'>Se connecter</a>";
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"> 
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<h2>Inscription</h2>

<form method="POST">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>
    
    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Adresse :</label><br>
    <input type="text" name="adresse" required><br><br>
    
    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <label>Confirmer le mot de passe :</label><br>
    <input type="password" name="confirm_password" required><br><br>
    
    <button type="submit">S'inscrire</button><br>

    <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
</form>
</body>
</html>