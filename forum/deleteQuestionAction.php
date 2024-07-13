<?php
require('cnx.php'); // Ensure this file contains the database connection

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idOfTheQuestion = $_GET['id'];

    $sql = "SELECT id_auteur, nom_auteur, id_question, contenu FROM reponse WHERE id_question = ?";
    $stmt = $link->prepare($sql);

    if ($stmt === false) {
        die('Erreur de préparation de la requête : ' . htmlspecialchars($link->error));
    }

    $stmt->bind_param('i', $idOfTheQuestion);

    if (!$stmt->execute()) {
        die('Erreur lors de l\'exécution de la requête : ' . htmlspecialchars($stmt->error));
    }

    $result = $stmt->get_result();

    // Now handle the results as needed
    // For example, iterate over $result to display answers
    while ($row = $result->fetch_assoc()) {
        // Display each answer
        echo "ID Auteur: " . htmlspecialchars($row['id_auteur']) . "<br>";
        echo "Nom Auteur: " . htmlspecialchars($row['nom_auteur']) . "<br>";
        echo "ID Question: " . htmlspecialchars($row['id_question']) . "<br>";
        echo "Contenu: " . nl2br(htmlspecialchars($row['contenu'])) . "<br>";
        echo "<hr>";
    }

    $stmt->close();
    $link->close();
} else {
    die('ID de la question non spécifié.');
}
?>
