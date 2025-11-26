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

<h1>Bonjour, <?php echo htmlspecialchars($user['nom']); ?> !</h1>

<?php if (isset($error)) { echo "<p style='color:red'>$error</p>"; } ?>

<h3>Mes informations</h3>
<p>
    <strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?><br>
    <strong>Adresse :</strong> <?php echo htmlspecialchars($user['adresse']); ?><br>
    <strong>Role :</strong> <?php echo ($user['role_id'] == 1) ? 'Administrateur' : 'Utilisateur'; ?>
</p>

<hr>

<form method="post" onsubmit="return confirm('Etes-vous sur de vouloir supprimer votre compte définitivement ?');">
    <button type="submit" name="supprimer_compte" style="background-color: red; color: white;">
        Supprimer mon compte
    </button>
</form>

<br>
<a href="logout.php">Se déconnecter</a>
    
</body>
</html>