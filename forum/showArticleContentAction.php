<?php
require('cnx.php');
$message = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {

    // Récupère l'identifiant de la question
    $idOfTheQuestion = $_GET['id'];

    // Vérifier si la question existe
    $checkIfQuestionExists = $link->prepare('SELECT * FROM questions WHERE id = ?');
    $checkIfQuestionExists->bind_param('i', $idOfTheQuestion);
    $checkIfQuestionExists->execute();
    $result = $checkIfQuestionExists->get_result();

    if ($result->num_rows > 0) {

        // Récupérer toutes les données de la question
        $questionsInfos = $result->fetch_assoc();

        // Stocker les données de la question dans des variables propres
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
?>
