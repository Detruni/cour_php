<?php
session_start();
require "fonctions.php";
requireLogin();

// Sécurité Admin
if ($_SESSION['user_role'] != 1) {
    header("Location: profil.php");
    exit;
}

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $password = trim($_POST['password']);
    $role_id = intval($_POST['role']); // On récupère le rôle choisi

    // Validations basiques
    if ($nom === "" || $email === "" || $adresse === "" || $password === "") {
        $error = "Tous les champs sont obligatoires.";
    } elseif (emailExiste($pdo, $email)) {
        $error = "Cet email est déjà utilisé.";
    } else {
        // Hashage
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Création avec le rôle spécifique
        if (creerUtilisateur($pdo, $nom, $email, $adresse, $passwordHash, $role_id)) {
            header("Location: admin.php"); // Retour à la liste
            exit;
        } else {
            $error = "Erreur lors de l'ajout.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Ajouter un utilisateur (Admin)</h2>
        
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

        <form method="POST">
            Nom : <br><input type="text" name="nom" required><br><br>
            Email : <br><input type="email" name="email" required><br><br>
            Adresse : <br><input type="text" name="adresse" required><br><br>
            Mot de passe : <br><input type="password" name="password" required><br><br>
            
            Rôle : <br>
            <select name="role">
                <option value="2">Utilisateur</option>
                <option value="1">Administrateur</option>
            </select><br><br>

            <button type="submit">Ajouter</button>
            <a href="admin.php">Annuler</a>
        </form>
    </div>
</body>
</html>