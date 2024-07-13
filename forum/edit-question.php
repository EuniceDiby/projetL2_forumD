<?php
require('editQuestionsAction.php');
require('getInfosOfEditedQuestionAction.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une question</title>
    <link rel="stylesheet" href="edit-question.css">
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

    <!-- Formulaire début -->
    <div class="container">
        <?php if (isset($question_date)): ?>
            <h2>Modifier une question</h2>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre de la question</label>
                    <input type="text" id="titre" name="titre" value="<?= $question_titre ?>" required>
                </div><br>
                <div class="mb-3">
                    <label for="description" class="form-label">Description de la question</label>
                    <textarea id="description" name="description" required><?= $question_description ?></textarea>
                </div><br>
                <div class="mb-3">
                    <label for="contenu" class="form-label">Contenu de la question</label>
                    <textarea id="contenu" name="contenu" required><?= $question_contenu ?></textarea>
                </div>
                <input type="hidden" name="id" value="<?= $idOfQuestion ?>"><br>
                <button type="submit" class="btn btn-primary" name="valider">Modifier la question</button>
            </form>
        <?php endif; ?>
       
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
    <!-- Formulaire fin -->
</body>
</html>
