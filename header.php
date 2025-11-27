<?php
// On démarre la session ici pour ne pas avoir à le faire sur chaque page
// La condition vérifie si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "Gestion Utilisateurs"; ?></title>
    
    <link rel="stylesheet" href="assets/css/style.css">
    
    <script src="assets/js/script.js" defer></script>
</head>
<body>

<header>
    <nav class="navbar">
        <a href="index.php" class="logo">SUP DE VINCI</a>

        <ul class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['user_role'] == 1): ?>
                    <li><a href="admin.php">Administration</a></li>
                <?php endif; ?>

                <li><a href="profil.php">Mon Profil</a></li>
                <li><a href="logout.php" style="color: var(--danger);">Déconnexion</a></li>

            <?php else: ?>
                <li><a href="register.php">Inscription</a></li>
                <li><a href="login.php">Connexion</a></li>
            <?php endif; ?>
        </ul>

        <div class="burger-menu">
            <span class="line1"></span>
            <span class="line2"></span>
            <span class="line3"></span>
        </div>
    </nav>
</header>

<div style="margin-top: 80px;"></div>