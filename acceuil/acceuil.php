<?php
session_start();
require('../forum/cnx.php');


    



// Requête pour récupérer les questions
$sql = 'SELECT q.id, q.titre, q.description, q.nom_auteur, q.date_publication, COUNT(r.id) AS nb_reponses 
        FROM questions q 
        LEFT JOIN reponse r ON q.id = r.id_question 
        GROUP BY q.id 
        ORDER BY q.id DESC 
        LIMIT 7';
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

<!--Catagories-->

<?php


// Vérifier si la connexion à la base de données est établie
if ($link->connect_error) {
    die("Erreur de connexion : " . $link->connect_error);
}

// Requête pour récupérer les catégories
$sql = 'SELECT c.id, c.titre, c.description, COUNT(s.id) AS nb_sujets 
        FROM categories c 
        LEFT JOIN sujets s ON c.id = s.id_categorie 
        GROUP BY c.id';
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
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <title>Rechercher des Questions</title>
    <link rel="stylesheet" href="acceuil.css">
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
                    <a class="list_menu" href="acceuil.php">Accueil</a>
                    <a class="list_menu" href="#">A propos</a>
                    <a class="list_menu" href="../forum/connexion.php"><img src="../forum/img/310869.png" alt=""></a>
                </div>
            </nav>
        </div>
    </header>
   
    <!-- Fin entete -->

    
    <br><br>
    <div class="titre_acc"><h4>Questions du forum</h4></div>
    <div class="container">
    
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
                            <td><a href="../forum/connexion.php"><?php echo htmlspecialchars($question['titre']); ?></a></td>
                            <td><?php echo htmlspecialchars($question['description']); ?></td>
                            <td><?php echo htmlspecialchars($question['nb_reponses']); ?></td>
                            <td><?php echo htmlspecialchars($question['date_publication']); ?></td>
                            <td>
                                <div class="avatar" style="background-color: <?php echo $color; ?>">
                                    <span><?php echo $initial; ?></span>
                                </div>
                                <?php echo htmlspecialchars($question['nom_auteur']); ?>
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

    <div class="titre_acc"><h4>Categories de discussion du forum</h4></div>

    <table class="categories-table">
            <thead>
                <tr>
                    <th>Nom de la Catégorie</th>
                    <th>Nombre de Sujets</th> <!-- Ajout de cette colonne -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($categoriesResult->num_rows > 0) {
                    while ($categorie = $categoriesResult->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><a href="../forum/sujet.php?id=' . $categorie['id'] . '">' . htmlspecialchars($categorie['titre']) . '</a></td>';
                        echo '<td>' . htmlspecialchars($categorie['nb_sujets']) . '</td>'; // Affichage du nombre de sujets
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">Aucune catégorie trouvée.</td></tr>';
                }
                ?>
            </tbody>
        </table>

<br><br>
        <footer>
            <br>
            <p>Nous contacter</p>
            <div class="social-media">
                <a href="#" class="social-icon">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-icon">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-icon">
                     <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-icon">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
            <br>
        </footer>
</body>
</html>
