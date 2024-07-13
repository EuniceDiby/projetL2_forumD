<?php
require('cnx.php');

$message = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idOfQuestion = $_GET['id'];

    $checkIfQuestionExists = $link->prepare('SELECT id, titre, description, contenu, date_publication, id_auteur FROM questions WHERE id = ?');
    $checkIfQuestionExists->bind_param('i', $idOfQuestion);
    $checkIfQuestionExists->execute();
    $checkIfQuestionExists->store_result();

    if ($checkIfQuestionExists->num_rows > 0) {
        $checkIfQuestionExists->bind_result($id, $titre, $description, $contenu, $date_publication, $id_auteur);
        $checkIfQuestionExists->fetch();

        if ($id_auteur == $_SESSION['id']) {
            $question_titre = $titre;
            $question_description = str_replace('<br />', '', $description);
            $question_contenu = str_replace('<br />', '', $contenu);
            $question_date = $date_publication;
        } else {
            $message = "Vous n'êtes pas l'auteur de cette question.";
        }
    } else {
        $message = "Aucune question trouvée.";
    }
} else {
    $message = "Aucune question trouvée.";
}
?>
