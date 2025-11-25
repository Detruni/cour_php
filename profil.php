<?php
session_start();
require "fonctions.php";

requireLogin();

$pdo = getDB();

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
</head>
<body>

<h1>Bonjour, <?php echo htmlspecialchars($user['nom']); ?> !</h1>

<h3>Mes informations</h3>
<p>
    <strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?><br>
    <strong>Adresse :</strong> <?php echo htmlspecialchars($user['adresse']); ?><br>
    <strong>Role :</strong> <?php echo ($user['role_id'] == 1) ? 'Administrateur' : 'Utilisateur'; ?>
</p>

<hr>
<a href="logout.php">Se déconnecter</a>
    
</body>
</html>