<?php
session_start();
require "fonctions.php";
requireLogin();
?>

<!DOCTYPE html>
<html>
<body>

<h2>Tableau de bord</h2>

Bonjour <?php echo htmlspecialchars($_SESSION['user_nom']); ?>

<br><br>
<a href="logout.php">Se déconnecter</a>

</body>
</html>