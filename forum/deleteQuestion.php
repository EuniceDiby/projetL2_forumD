<?php
require('cnx.php');
session_start();

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
            $deleteThisQuestion = $link->prepare('DELETE FROM questions WHERE id = ?');
            $deleteThisQuestion->bind_param('i', $idOfQuestion);
            $deleteThisQuestion->execute();

            header('Location: mes-questions.php');
            exit;
        } else {
            echo "Vous n'avez pas le droit de supprimer une question qui ne vous appartient pas";
        }
    } else {
        echo "Aucune question n'a été trouvée";
    }
} else {
    echo "Aucune question n'a été trouvée";
}
?>
