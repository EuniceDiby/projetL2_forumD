<?php
require('cnx.php');

// Requête pour récupérer les questions
$sql = 'SELECT id, id_auteur, titre, description, nom_auteur, date_publication FROM questions ORDER BY id DESC LIMIT 5';
$getAllQuestions = $link->query($sql);

// Vérifier si une recherche a été effectuée
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $usersSearch = $link->real_escape_string($_GET['search']);
    $sql = 'SELECT id, id_auteur, titre, description, nom_auteur, date_publication FROM questions WHERE titre LIKE "%' . $usersSearch . '%" ORDER BY id DESC';
    $getAllQuestions = $link->query($sql);
}

// Vérifier les erreurs de requête
if ($getAllQuestions === false) {
    die('Erreur SQL : ' . $link->error);
}
?>
