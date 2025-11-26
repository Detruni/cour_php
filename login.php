<?php
session_start();
require "fonctions.php";

$pdo = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // Récupération de l'utilisateur par son email
        $user = getUserByEmail($pdo, $email);

        // On vérifie si l'utilisateur existe et si le mot de passe est bon
        if ($user && password_verify($password, $user['password'])) {
            
            // 1. On remplit la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_role'] = $user['role_id']; // On stocke le rôle (1 ou 2)

            // 2. Redirection selon le rôle
            if ($user['role_id'] == 1) {
                header("Location: admin.php");
            } else {
                header("Location: profil.php");
            }
            exit;

        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h2>Connexion</h2>

<?php if (isset($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

<p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>

</body>
</html>