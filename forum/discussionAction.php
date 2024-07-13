<?php
require("cnx.php");

// Vérifier si les variables POST existent avant de les utiliser
if (isset($_POST['id_question'], $_POST['contenu'])) {
    // Ajout d'une réponse
    $id_question = $_POST['id_question']; // ID de la question à laquelle la réponse est ajoutée
    $contenu = $_POST['contenu']; // Contenu de la réponse

    $sql_insert_reponse = "INSERT INTO reponse (id_question, contenu) VALUES ('$id_question', '$contenu')";
    if ($link->query($sql_insert_reponse) === TRUE) {
        // Mettre à jour le nombre de réponses dans la table question
        $sql_update_question = "UPDATE questions SET nombre_reponses = nombre_reponses + 1 WHERE id = '$id_question'";
        if (!$link->query($sql_update_question)) {
            echo "Erreur lors de la mise à jour du nombre de réponses : " . $link->error;
        }
    } else {
        echo "Erreur lors de l'ajout de la réponse : " . $link->error;
    }
}

// Vérifier si la variable POST pour la suppression existe avant de l'utiliser
if (isset($_POST['id_reponse'])) {
    $id_reponse = $_POST['id_reponse']; // ID de la réponse à supprimer

    // Récupérer l'ID de la question associée à cette réponse
    $sql_get_question_id = "SELECT id_question FROM reponse WHERE id = '$id_reponse'";
    $result = $link->query($sql_get_question_id);
    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_question = $row['id_question'];

            // Supprimer la réponse
            $sql_delete_reponse = "DELETE FROM reponse WHERE id = '$id_reponse'";
            if ($link->query($sql_delete_reponse) === TRUE) {
                // Mettre à jour le nombre de réponses dans la table question
                $sql_update_question = "UPDATE questions SET nombre_reponses = nombre_reponses - 1 WHERE id = '$id_question'";
                if (!$link->query($sql_update_question)) {
                    echo "Erreur lors de la mise à jour du nombre de réponses : " . $link->error;
                }
            } else {
                echo "Erreur lors de la suppression de la réponse : " . $link->error;
            }
        } else {
            echo "Erreur : La réponse n'existe pas.";
        }
    } else {
        echo "Erreur lors de la récupération de l'ID de la question : " . $link->error;
    }
}
?>
