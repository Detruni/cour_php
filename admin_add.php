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
    // Récupération des données
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']); // Nouveau champ
    $role_id = intval($_POST['role']);

    // Validations basiques
    if ($nom === "" || $email === "" || $adresse === "" || $password === "" || $confirm_password === "") {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($password !== $confirm_password) {
        // Nouvelle vérification
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (emailExiste($pdo, $email)) {
        $error = "Cet email est déjà utilisé.";
    } else {
        // Si tout est bon, on hash et on enregistre
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        if (creerUtilisateur($pdo, $nom, $email, $adresse, $passwordHash, $role_id)) {
            header("Location: admin.php");
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

    <?php require 'header.php'; ?>

    <div class="container">
        <h2>Ajouter un utilisateur (Admin)</h2>
        
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

        <form method="POST">
            Nom : <br><input type="text" name="nom" required><br><br>
            Email : <br><input type="email" name="email" required><br><br>
            <label>Adresse :</label>
            <div class="address-group">
                <input type="text" name="adresse" id="addressInput" autocomplete="off" required>
                <ul id="suggestions"></ul>
            </div><br>
            <label>Mot de passe :</label>
            <div class="password-container" style="position: relative;">
                <input type="password" name="password" id="passwordInput" required>
                <span id="togglePassword" style="position: absolute; right: 10px; top: 15px; cursor: pointer;">👁️</span>
            </div><br>

            <label>Confirmer le mot de passe :</label>
            <input type="password" name="confirm_password" required><br><br>
            
            Rôle : <br>
            <select name="role">
                <option value="2">Utilisateur</option>
                <option value="1">Administrateur</option>
            </select><br><br>

            <button type="submit">Ajouter</button>
            <a href="admin.php">Annuler</a>
        </form>
    </div>

    <?php require "footer.php"; ?>

    <script src="assets/js/script.js" defer></script>
</body>
</html>