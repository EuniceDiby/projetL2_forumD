<?php
require('cnx.php');
session_start();

// Vérifier si la connexion à la base de données est établie
if ($link->connect_error) {
    die("Erreur de connexion : " . $link->connect_error);
}

// Requête pour récupérer les catégories
$sql = 'SELECT id, titre, description FROM categories';
$categoriesResult = $link->query($sql);

// Vérifiez si la requête a réussi
if ($categoriesResult === false) {
    die('Erreur SQL : ' . $link->error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Catégories</title>
    <link rel="stylesheet" href="categorie.css">
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
                    <a class="list_menu" href="deconnexion.php">Déconnexion</a>
                </div>
            </nav>
        </div>
    </header>
    <!-- Fin entete -->
     <br><br>
    <div class="container">
        <h1>Liste des Catégories</h1>
        <table class="categories-table">
            <thead>
                <tr>
                    <th>Nom de la Catégorie</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($categoriesResult->num_rows > 0) {
                    while ($categorie = $categoriesResult->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><a href="sujet.php?id=' . $categorie['id'] . '">' . htmlspecialchars($categorie['titre']) . '</a></td>';
                        echo '<td>' . htmlspecialchars($categorie['description']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="2">Aucune catégorie trouvée.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <a class="back-button" href="categorie.php">Retour à l\'accueil</a>
    </div>
</body>
</html>
