<?php


require('cnx.php');
$message ="";
if (isset($_POST['valider'])) {
    if (!empty($_POST['answer'])) {
        $user_answer = nl2br(htmlspecialchars($_POST['answer']));

        $insertAnswer = $link->prepare('INSERT INTO reponse (id_auteur, nom_auteur, id_question, contenu) VALUES (?, ?, ?, ?)');
        if ($insertAnswer === false) {
            die('Erreur de préparation de la requête : ' . $link->error);
        }

        $id_auteur = $_SESSION['id'];
        $nom_auteur = $_SESSION['nom'];
        $id_question = $_GET['id']; // Assurez-vous que l'ID de la question est passé dans l'URL

        $insertAnswer->bind_param('isis', $id_auteur, $nom_auteur, $id_question, $user_answer);
        $insertAnswer->execute();

        if ($insertAnswer->error) {
            die('Erreur lors de l\'insertion de la réponse : ' . $insertAnswer->error);
        } else {
            $message = "Réponse ajoutée avec succès.";
        }
    } else {
        $message = "Veuillez entrer une réponse.";
    }
}
?>
