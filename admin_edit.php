<?php
session_start();
require "fonctions.php";
requireLogin();

if ($_SESSION['user_role'] != 1) {
    header("Location: profil.php");
    exit;
}

$pdo = getDB();

// Vérifier si on a un ID dans l'URL (ex: admin_edit.php?id=5)
if (!isset($_GET['id'])) {
    die("ID utilisateur manquant.");
}

$id = intval($_GET['id']);
$user = getUserById($pdo, $id);

if (!$user) {
    die("Utilisateur introuvable.");
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $role_id = intval($_POST['role']);

    if ($nom === "" || $email === "" || $adresse === "") {
        $error = "Tous les champs sont obligatoires.";
    } else {
        if (updateUtilisateur($pdo, $id, $nom, $email, $adresse, $role_id)) {
            header("Location: admin.php"); // Retour à la liste après succès
            exit;
        } else {
            $error = "Erreur lors de la modification.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Modifier l'utilisateur : <?php echo htmlspecialchars($user['nom']); ?></h2>
        
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

        <form method="POST">
            Nom : <br>
            <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required><br><br>
            
            Email : <br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>
            
            Adresse : <br>
            <input type="text" name="adresse" value="<?php echo htmlspecialchars($user['adresse']); ?>" required><br><br>
            
            Rôle : <br>
            <select name="role">
                <option value="2" <?php if ($user['role_id'] == 2) echo 'selected'; ?>>Utilisateur</option>
                <option value="1" <?php if ($user['role_id'] == 1) echo 'selected'; ?>>Administrateur</option>
            </select><br><br>

            <button type="submit">Enregistrer les modifications</button>
            <a href="admin.php">Annuler</a>
        </form>
    </div>
</body>
</html>