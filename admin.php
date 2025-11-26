<?php
session_start();
require "fonctions.php";

requireLogin();

// Sécurité : Vérification stricte du rôle Admin (id = 1)
if ($_SESSION['user_role'] != 1) {
    header("Location: profil.php");
    exit;
}

$pdo = getDB();
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_id'])) {
    $idASupprimer = intval($_POST['supprimer_id']);

    if ($idASupprimer === $_SESSION['user_id']) {
        $error = "Vous ne pouvez pas supprimer votre propre compte ici.";
    } else {
        if (supprimerUtilisateur($pdo, $idASupprimer)) {
            $success = "Utilisateur supprimé avec succès.";
        } else {
            $error = "Erreur lors de la suppression.";
        }
    }
}

// Récupération de tous les utilisateurs pour le tableau
$users = recupererTousLesUtilisateurs($pdo);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Interface Administrateur</h1>
    <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_nom']); ?>.</p>

    <a href="profil.php">Mon Profil</a> | <a href="logout.php">Se déconnecter</a>

    <hr>

    <?php if ($error): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert success"><?php echo $success; ?></div>
    <?php endif; ?>

    <h3>Liste des utilisateurs</h3>

    <a href="admin_add.php" class="btn-green">Ajouter un utilisateur</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['nom']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    
                    <td>
                        <?php if ($user['role_name'] === 'admin'): ?>
                            <strong>Admin</strong>
                        <?php else: ?>
                            Utilisateur
                        <?php endif; ?>
                    </td>

                    <td>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Confirmer la suppression de cet utilisateur ?');">
                            <input type="hidden" name="supprimer_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn-red">Supprimer</button>
                        </form>
                        
                        <a href="admin_edit.php?id=<?php echo $user['id']; ?>">Modifier</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>