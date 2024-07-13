<?php
require('publierQuestionAction.php');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier une question</title>
    <link rel="stylesheet" href="publierQuestion.css">
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
                    <a class="list_menu" href="acceuil.html">Accueil</a>
                    <a class="list_menu" href="rechercherQuestion.php">Les questions</a>
                    <a class="list_menu" href="mes-questions.php">Mes questions</a>
                    <a class="list_menu" href="profile.php">Mon profil</a>
                    <a class="list_menu" href="deconnexion.php">Déconnexion</a>
                </div>
            </nav>
        </div>
    </header>
    <!-- Fin entete -->
<br><br>

    <form action="" method="post">
        <h2>Publier une question</h2>
        
        <label for="titre">Titre de la question :</label>
        <input type="text" id="titre" name="titre" required>

        <label for="description">Description de la question :</label>
        <textarea id="description" name="description" required></textarea>
        
        <label for="contenu">Contenu de la question :</label>
        <textarea id="contenu" name="contenu" required></textarea>
        
        <button type="submit">Publier</button>
        
        <?php
            if (isset($message)) {
                echo '<p>' . htmlspecialchars($message) . '</p>';
            }
        ?>
    </form>
</body>
</html>
