<?php
session_start();
require "fonctions.php";

// Sécurité : On rejette si non connecté
requireLogin();

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_compte'])) {
    // Suppression basée sur l'ID de session (Sécurité : on ne supprime que soi-même)
    if (supprimerUtilisateur($pdo, $_SESSION['user_id'])) {
        session_destroy(); // On détruit la session après suppression
        header("Location: register.php");
        exit;
    } else {
        $error = "Erreur lors de la suppression du compte.";
    }
}

// On récupère les infos fraîches depuis la BDD
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: logout.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php require 'header.php'; ?>

    <div class="container profile-card">
        
        <div class="profile-avatar">
            <?php echo strtoupper(substr($user['nom'], 0, 1)); ?>
        </div>

        <h1>Mon Profil</h1>

        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <div class="profile-info-group">
            <span class="profile-label">Nom</span>
            <span class="profile-value"><?php echo htmlspecialchars($user['nom']); ?></span>
        </div>

        <div class="profile-info-group">
            <span class="profile-label">Email</span>
            <span class="profile-value"><?php echo htmlspecialchars($user['email']); ?></span>
        </div>

        <div class="profile-info-group">
            <span class="profile-label">Adresse</span>
            <span class="profile-value"><?php echo htmlspecialchars($user['adresse']); ?></span>
        </div>

        <div class="profile-info-group">
            <span class="profile-label">Rôle</span>
            <span class="profile-value">
                <?php echo ($user['role_id'] == 1) ? 'Administrateur' : 'Utilisateur'; ?>
            </span>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 20px 0;">

        <div class="action-buttons" style="justify-content: center; flex-direction: column;">
            
            <a href="logout.php" style="margin-bottom: 15px;">Se déconnecter</a>

            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte définitivement ?');" style="width: 100%;">
                <button type="submit" name="supprimer_compte" class="btn-delete" style="width: 100%;">
                    Supprimer mon compte
                </button>
            </form>
            
        </div>
    </div>

    <?php require "footer.php"; ?>

</body>
</html>