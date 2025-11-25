<?php

// ---------------------------------------
// Connexion PDO à la base de données
// ---------------------------------------
function getDB() {
    $host = "localhost";
    $dbname = "cour_php";
    $username = "root";
    $password = "";

    try {
        return new PDO(
            "mysql:host=$host;dbname=$dbname;port=3306;charset=utf8",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    } catch (PDOException $e) {
        die("Erreur de connexion BDD : " . $e->getMessage());
    }
}



// ---------------------------------------
// Vérifie si un email existe déjà
// ---------------------------------------
function emailExiste($pdo, $email) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->rowCount() > 0;
}



// ---------------------------------------
// Inscrire un utilisateur
// ---------------------------------------
function creerUtilisateur($pdo, $nom, $email, $adresse, $passwordHash, $role_id = 2) {
    $sql = "INSERT INTO users (nom, email, adresse, password, role_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nom, $email, $adresse, $passwordHash, $role_id]);
}



// ---------------------------------------
// Récupérer un utilisateur par email
// ---------------------------------------
function getUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}



// ---------------------------------------
// Vérifier si un utilisateur est connecté
// ---------------------------------------
function isLogged() {
    return isset($_SESSION['user_id']);
}



// ---------------------------------------
// Bloquer une page si non connecté
// ---------------------------------------
function requireLogin() {
    if (!isLogged()) {
        header("Location: login.php");
        exit;
    }
}


// ---------------------------------------
// Supprimer un utilisateur par son ID
// ---------------------------------------
function supprimerUtilisateur($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}


// ---------------------------------------
// Récupérer TOUS les utilisateurs avec leur nom de rôle
// ---------------------------------------
function recupererTousLesUtilisateurs($pdo) {
    $sql = "SELECT users.*, roles.role_name
            FROM users
            JOIN roles ON users.role_id = roles.id
            ORDER BY users.id ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}


// ---------------------------------------
// Récupérer un utilisateur par ID
// ---------------------------------------
function getUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


// ---------------------------------------
// Mettre à jour un utilisateur (Admin)
// ---------------------------------------
function updateUtilisateur($pdo, $id, $nom, $email, $adresse, $role_id) {
    $sql = "UPDATE users SET nom = ?, email = ?, adresse = ?, role_id = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$nom, $email, $adresse, $role_id, $id]);
}


?>