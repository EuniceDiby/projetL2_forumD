<?php
// Connexion à la base de données
require('database.php');

// Vérifier si les informations de connexion sont envoyées
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Préparer la requête pour vérifier les informations de connexion
    $stmt = $db->prepare('SELECT id, password FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Vérifier si le mot de passe est correct
    if (password_verify($password, $hashed_password)) {
        // Mettre à jour la date et l'heure de la dernière connexion
        $update_stmt = $db->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
        $update_stmt->bind_param('i', $user_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Démarrer la session et rediriger l'utilisateur
        session_start();
        $_SESSION['user_id'] = $user_id;
        header('Location: profile.php');
        exit();
    } else {
        echo 'Email ou mot de passe incorrect.';
    }
}
?>
