<?php
session_start();
require('showArticleContentAction.php');
require('postAnswerAction.php');
require('showAllAnswersOfQuestionAction.php');


$message = '';

// Fonction pour attribuer une couleur basée sur l'ID utilisateur
function getColorFromId($id) {
    $colors = ["#1abc9c", "#3498db", "#9b59b6", "#e74c3c", "#f1c40f"];
    return $colors[$id % count($colors)];
}

// Récupération de la question
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idOfTheQuestion = $_GET['id'];

    $checkIfQuestionExists = $link->prepare('SELECT * FROM questions WHERE id = ?');
    $checkIfQuestionExists->bind_param('i', $idOfTheQuestion);
    $checkIfQuestionExists->execute();
    $result = $checkIfQuestionExists->get_result();

    if ($result->num_rows > 0) {
        $questionsInfos = $result->fetch_assoc();
        $question_titre = $questionsInfos['titre'];
        $question_contenu = $questionsInfos['contenu'];
        $question_date_publication = $questionsInfos['date_publication'];
        $question_id_auteur = $questionsInfos['id_auteur'];
        $question_nom_auteur = $questionsInfos['nom_auteur'];
    } else {
        $message = "Aucune question n'a été trouvée";
    }
} else {
    $message = "Aucune question n'a été trouvée";
}

// Gestion de l'ajout de réponse
if (isset($_POST['valider'])) {
    if (!empty($_POST['answer'])) {
        $user_answer = nl2br(htmlspecialchars(trim($_POST['answer'])));
        $id_auteur = $_SESSION['id'];
        $nom_auteur = $_SESSION['nom'];
        $id_question = $_GET['id'];

        // Vérification de l'existence d'une réponse similaire
        $checkExistingAnswer = $link->prepare('SELECT * FROM reponse WHERE id_auteur = ? AND id_question = ? AND contenu = ?');
        $checkExistingAnswer->bind_param('iis', $id_auteur, $id_question, $user_answer);
        $checkExistingAnswer->execute();
        $existingAnswerResult = $checkExistingAnswer->get_result();

        if ($existingAnswerResult->num_rows > 0) {
            $message = "Cette réponse a déjà été ajoutée.";
        } else {
            $insertAnswer = $link->prepare('INSERT INTO reponse (id_auteur, nom_auteur, id_question, contenu) VALUES (?, ?, ?, ?)');
            if ($insertAnswer === false) {
                die('Erreur de préparation de la requête : ' . $link->error);
            }

            $insertAnswer->bind_param('isis', $id_auteur, $nom_auteur, $id_question, $user_answer);
            $insertAnswer->execute();

            if ($insertAnswer->error) {
                die('Erreur lors de l\'insertion de la réponse : ' . $insertAnswer->error);
            } else {
                $message = "Réponse ajoutée avec succès.";
            }
        }
    } else {
        $message = "Veuillez entrer une réponse.";
    }
}

// Récupération des réponses
$checkAllAnswersOfQuestion = $link->prepare('SELECT * FROM reponse WHERE id_question = ?');
$checkAllAnswersOfQuestion->bind_param('i', $idOfTheQuestion);
$checkAllAnswersOfQuestion->execute();
$result = $checkAllAnswersOfQuestion->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article</title>
    <link rel="stylesheet" href="article.css">
    <style>
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5em;
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
                    <a class="list_menu" href="deconnexion.php">Déconnexion</a>
                </div>
            </nav>
        </div>
    </header>
    <!-- Fin entete -->
    <br><br>

    <div class="container">
        <?php if (isset($question_date_publication)) { ?>
            <section class="show-contenu">
                <h3><?php echo htmlspecialchars($question_titre); ?></h3>
                <hr>
                <p><?php echo nl2br(htmlspecialchars($question_contenu)); ?></p>
                <hr>
                <small><?php echo htmlspecialchars($question_nom_auteur) . ' - ' . htmlspecialchars($question_date_publication); ?></small>
            </section>

            <section class="show-answers">
                

                <?php if ($result->num_rows > 0) {
                    while ($answer = $result->fetch_assoc()) { 
                        $initialColor = getColorFromId($answer['id_auteur']);
                        ?>
                        <div class="card">
                            <div class="card-header">
                                <div class="avatar" style="background-color: <?php echo $initialColor; ?>">
                                    <span class="initial"><?php echo strtoupper($answer['nom_auteur'][0]); ?></span>
                                </div>
                                <span class="author"><?php echo htmlspecialchars($answer['nom_auteur']); ?></span>
                            </div>
                            <div class="card-body">
                                <?php echo nl2br(htmlspecialchars($answer['contenu'])); ?>
                            </div>
                            <?php if ($_SESSION['id'] == $answer['id_auteur']) { ?>
                            <div class="card-footer">
                                <form action="editAnswer.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id_answer" value="<?php echo $answer['id']; ?>">
                                    <button type="submit" class="btn btn-warning">Modifier</button>
                                </form>
                                <form action="deleteAnswer.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id_answer" value="<?php echo $answer['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </form>
                            </div>
                            <?php } ?>
                        </div>
                    <?php }
                } ?>
<br><br>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail" class="form-label">Répondre à la question</label>
                        <textarea name="answer" class="form-control"></textarea>
                        <br><br>
                        <button class="btn-primary" type="submit" name="valider">Poster la reponse</button>
                    </div>
                </form>
            </section>
            <?php if ($message) {
                echo "<p>$message</p>";
            } ?>
        <?php } else {
            echo "<p>$message</p>";
        } ?>
    </div>
</body>
</html>
