<?php
session_start();
require('cnx.php'); // Connexion à la base de données

if (isset($_POST['id_answer']) && !empty($_POST['id_answer'])) {
    $id_answer = $_POST['id_answer'];

    $query = $link->prepare("DELETE FROM reponse WHERE id = ? AND id_auteur = ?");
    $query->bind_param('ii', $id_answer, $_SESSION['id']);
    if ($query->execute()) {
        header('Location: article.php?id=' . $_GET['id']);
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "Aucune réponse spécifiée.";
}
?>
