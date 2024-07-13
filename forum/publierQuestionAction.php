<?php
session_start();
require('cnx.php');

if (!isset($_SESSION['id']) || !isset($_SESSION['nom'])) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['titre']) && !empty($_POST['description']) && !empty($_POST['contenu'])) {
        
        $question_titre = htmlspecialchars($_POST['titre']);
        $question_description = nl2br(htmlspecialchars($_POST['description']));
        $question_contenu = nl2br(htmlspecialchars($_POST['contenu']));
        $question_date = date('d-m-Y');
        $question_id_auteur = $_SESSION['id'];
        $question_nom_auteur = $_SESSION['nom'];

        $insertQuestionOnWebSite = $link->prepare('INSERT INTO questions (titre, description, contenu, date_publication, id_auteur, nom_auteur) VALUES (?, ?, ?, ?, ?, ?)');
        $insertQuestionOnWebSite->bind_param("ssssss", $question_titre, $question_description, $question_contenu, $question_date, $question_id_auteur, $question_nom_auteur);

        if ($insertQuestionOnWebSite->execute()) {
            $message = "Votre question a bien été publiée.";
            header('rechercherQuestion.php');
        } else {
            $message = "Erreur lors de la publication de votre question.";
        }

        $insertQuestionOnWebSite->close();
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>
