<?php
session_start();
require('cnx.php'); // Assurez-vous que ce fichier contient la connexion à la base de données

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Questions</title>
    <link rel="stylesheet" href="mes-questions.css">
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
                    <a class="list_menu" href="mes-questions.php">Mes questions</a>
                    <a class="list_menu" href="profile.php">Mon profil</a>
                    <a class="list_menu" href="deconnexion.php">Déconnexion</a>
                </div>
            </nav>
        </div>
    </header>
    <!-- Fin entete -->

    <?php
    $userId = $_SESSION['id']; // Assurez-vous que l'utilisateur est connecté et que l'ID est défini

    $sql = "SELECT id, titre, description FROM questions WHERE id_auteur = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($question = $result->fetch_assoc()) {
            ?>
            <div class="card">
                <h5 class="card-header">
                    <a href="article.php?id=<?php echo $question['id'];?>">
                        <?php echo htmlspecialchars($question['titre']);?>
                    </a>
                </h5>
                <div class="card-body">                        
                    <p class="card-text">
                        <?php echo nl2br(htmlspecialchars($question['description'])); ?>
                    </p>
                    <a href="article.php?id=<?php echo $question['id'];?>" class="btn btn-primary">Accéder à l'article</a>
                    <a href="edit-question.php?id=<?php echo $question['id']; ?>" class="btn btn-primary">Modifier l'article</a>
                    <a href="deleteQuestion.php?id=<?php echo $question['id']; ?>" class="btn btn-primary">Supprimer l'article</a>
                </div>
            </div>
            <?php
        }
        $result->free(); // Libérer le résultat pour éviter l'erreur "Commands out of sync"
    } else {
        echo "Aucune question trouvée.";
    }
    $stmt->close();
    $link->close(); // Fermez la connexion après avoir fini
    ?>
</body>
</html>
