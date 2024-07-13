<?php

require('adminAction.php'); // Connexion à la base de données

/*if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}*/

// Récupération des utilisateurs
$query = $link->prepare('SELECT * FROM users');
$query->execute();
$users = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <!-- Debut entete -->
    <header class="head_menu">
        <div class="div_menu">
            <nav class="menu_1">
                <div class="logo">
                    <a href="#">Forum <span>DEM</span></a>
                </div>
            </nav>
            <nav class="menu_2">
                <div class="nav_menu">
                    <a class="list_menu" href="accueil.html">Accueil</a>
                    <a class="list_menu" href="rechercherQuestion.php">Les questions</a>
                    <a class="list_menu" href="publierQuestion.php">Publier une question</a>
                    <a class="list_menu" href="mes-questions.php">Mes questions</a>
                    <a class="list_menu" href="profile.php">Mon profil</a>
                    <a class="list_menu" href="deconnexion.php">Déconnexion</a>
                </div>
            </nav>
        </div>
    </header>
    <!-- Fin entete -->
    <h2>Gestion des utilisateurs</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Bloqué</th>
            <th>Action</th>
        </tr>
        <?php while ($user = $users->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['nom']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['bloquer'] ? 'Oui' : 'Non'; ?></td>
            <td>
                <form action="adminAction.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="hidden" name="block" value="<?php echo $user['bloquer'] ? '0' : '1'; ?>">
                    <button type="submit" name="block_user"><?php echo $user['bloquer'] ? 'Débloquer' : 'Bloquer'; ?></button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>


    <h2>Modération des discussions</h2>
<h3>Questions</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Auteur</th>
        <th>Action</th>
    </tr>
    <?php
    $questionsQuery = $link->prepare('SELECT * FROM questions');
    $questionsQuery->execute();
    $questions = $questionsQuery->get_result();

    while ($question = $questions->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $question['id']; ?></td>
        <td><?php echo $question['titre']; ?></td>
        <td><?php echo $question['nom_auteur']; ?></td>
        <td>
            <form action="adminAction.php" method="post">
                <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                <button type="submit" name="delete_question">Supprimer</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>

<h3>Réponses</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Contenu</th>
        <th>Auteur</th>
        <th>Action</th>
    </tr>
    <?php
    $answersQuery = $link->prepare('SELECT * FROM reponse');
    $answersQuery->execute();
    $answers = $answersQuery->get_result();

    while ($answer = $answers->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $answer['id']; ?></td>
        <td><?php echo $answer['contenu']; ?></td>
        <td><?php echo $answer['nom_auteur']; ?></td>
        <td>
            <form action="adminAction.php" method="post">
                <input type="hidden" name="message_id" value="<?php echo $answer['id']; ?>">
                <button type="submit" name="delete_message">Supprimer</button>
            </form>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
