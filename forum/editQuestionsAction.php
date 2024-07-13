<?php
require('cnx.php');
session_start();

$message = '';

// Validation du formulaire
if (isset($_POST['valider'])) {
    // Vérifier si les champs sont remplis
    if (!empty($_POST['titre']) && !empty($_POST['description']) && !empty($_POST['contenu'])) {
        // Les données à faire passer dans la requête
        $new_question_titre = htmlspecialchars($_POST['titre']);
        $new_question_description = nl2br(htmlspecialchars($_POST['description']));
        $new_question_contenu = nl2br(htmlspecialchars($_POST['contenu']));
        $idOfQuestion = $_POST['id'];

        // Modifier les informations de la question
        $editQuestionOnWebsite = $link->prepare('UPDATE questions SET titre = ?, description = ?, contenu = ? WHERE id = ?');
        $editQuestionOnWebsite->bind_param('sssi', $new_question_titre, $new_question_description, $new_question_contenu, $idOfQuestion);
        $editQuestionOnWebsite->execute();

        if ($editQuestionOnWebsite->affected_rows > 0) {
            $message = "Question mise à jour avec succès.";
        } else {
            $message = "Aucune modification effectuée.";
        }

        header('Location: mes-questions.php');
        exit;
    } else {
        $message = "Veuillez compléter tous les champs.";
    }
}
?>
