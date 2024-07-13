<?php
require('cnx.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Récupérer l'identifiant de la question
    $idOfTheQuestion = $_GET['id'];

    // Préparer la requête pour obtenir toutes les réponses de cette question
    $getAllAnswersOfThisQuestion = $link->prepare('SELECT id_auteur, nom_auteur, id_question, contenu FROM reponse WHERE id_question = ?');
    
    if ($getAllAnswersOfThisQuestion === false) {
        die('Erreur de préparation de la requête : ' . htmlspecialchars($link->error));
    }
    
    $getAllAnswersOfThisQuestion->bind_param('i', $idOfTheQuestion);
    
    if (!$getAllAnswersOfThisQuestion->execute()) {
        die('Erreur lors de l\'exécution de la requête : ' . htmlspecialchars($getAllAnswersOfThisQuestion->error));
    }
    
    $result = $getAllAnswersOfThisQuestion->get_result();
} else {
    die('ID de la question non spécifié.');
}
?>
