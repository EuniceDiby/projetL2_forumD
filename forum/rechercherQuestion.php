<?php

require('cnx.php');


// Requête pour récupérer les questions
$sql = 'SELECT q.id, q.titre, q.description, q.nom_auteur, q.date_publication, COUNT(r.id) AS nb_reponses 
        FROM questions q 
        LEFT JOIN reponse r ON q.id = r.id_question 
        GROUP BY q.id 
        ORDER BY q.id DESC 
        LIMIT 50';
$getAllQuestions = $link->query($sql);

// Vérifier si une recherche a été effectuée
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $usersSearch = $link->real_escape_string($_GET['search']);
    $sql = 'SELECT q.id, q.titre, q.description, q.nom_auteur, q.date_publication, COUNT(r.id) AS nb_reponses 
            FROM questions q 
            LEFT JOIN reponse r ON q.id = r.id_question 
            WHERE q.titre LIKE "%' . $usersSearch . '%" 
            GROUP BY q.id 
            ORDER BY q.id DESC';
    $getAllQuestions = $link->query($sql);
}

// Vérifier les erreurs de requête
if ($getAllQuestions === false) {
    die('Erreur SQL : ' . $link->error);
}

// Fonction pour attribuer une couleur basée sur l'initiale de l'auteur
function getColorFromInitial($initial) {
    $colors = ["#1abc9c", "#3498db", "#9b59b6", "#e74c3c", "#f1c40f"];
    $index = ord(strtoupper($initial)) % count($colors);
    return $colors[$index];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher des Questions</title>
    <link rel="stylesheet" href="rechercherQuestion.css">
    <style>
        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1em;
            margin-right: 10px;
        }
    </style>
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
    <div class="container">
        <form method="GET">
            <div class="form-group">
                <div class="col-8">
                    <input type="search" name="search" class="form-control" placeholder="Rechercher une question">
                </div>
                <div class="col-4">
                    <button class="btn btn-success">Rechercher</button>
                </div>
            </div>
        </form>

        <table class="questions-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Réponses</th>
                    <th>Date</th>
                    <th>Auteur</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($getAllQuestions) {
                    while ($question = $getAllQuestions->fetch_assoc()) {
                        $initial = strtoupper($question['nom_auteur'][0]);
                        $color = getColorFromInitial($initial);
                        ?>
                        <tr>
                            <td><a href="article.php?id=<?php echo htmlspecialchars($question['id']); ?>"><?php echo htmlspecialchars($question['titre']); ?></a></td>
                            <td><?php echo htmlspecialchars($question['description']); ?></td>
                            <td><?php echo htmlspecialchars($question['nb_reponses']); ?></td>
                            <td><?php echo htmlspecialchars($question['date_publication']); ?></td>
                            <td>
                                <div class="avatar" style="background-color: <?php echo $color; ?>">
                                    <span><?php echo $initial; ?></span>
                                </div>
                                <a href="profile.php?id=<?php echo htmlspecialchars($question['id']); ?>"><?php echo htmlspecialchars($question['nom_auteur']); ?> </a>
                            </td>
                        </tr>
                        <?php
                    }
                    $getAllQuestions->free();
                } else {
                    echo "<tr><td colspan='5'>Aucune question trouvée ou une erreur est survenue : " . $link->error . "</td></tr>";
                }
                $link->close();
                ?>
            </tbody>
        </table>
    </div>

    <div class="publierQuestion">
        <a href="../forum/publierQuestion.php"><p>Publier une question</p></a>
    </div>

</body>
</html>
