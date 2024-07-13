<?php
require('cnx.php');
require('securiteAction.php');

// Vérifier si l'ID de la catégorie est passé en paramètre
/*echo '<pre>';
print_r($_GET); // Ligne de débogage pour afficher le contenu de $_GET
echo '</pre>';*/

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idCategorie = intval($_GET['id']);
    echo "ID de la catégorie : " . $idCategorie; // Ligne de débogage

    // Requête pour récupérer les sujets de la catégorie
    $sql = 'SELECT id, titre, description, date_publication, nom_auteur FROM sujets WHERE id_categorie = ?';
    $stmt = $link->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $idCategorie);
        $stmt->execute();
        $sujetsResult = $stmt->get_result();

        if ($sujetsResult === false) {
            die('Erreur SQL : ' . $link->error);
        }
    } else {
        die('Erreur de préparation de la requête : ' . $link->error);
    }
} else {
    die('ID de catégorie non spécifié.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Sujets</title>
</head>
<body>
    <h1>Liste des Sujets</h1>
    <ul>
        <?php
        if ($sujetsResult->num_rows > 0) {
            while ($sujet = $sujetsResult->fetch_assoc()) {
                echo '<li>';
                echo '<a href="sujet.php?id=' . $sujet['id'] . '">' . htmlspecialchars($sujet['titre']) . '</a>';
                echo '<p>' . htmlspecialchars($sujet['description']) . '</p>';
                echo '<p>Par ' . htmlspecialchars($sujet['nom_auteur']) . ' le ' . htmlspecialchars($sujet['date_publication']) . '</p>';
                echo '</li>';
            }
        } else {
            echo '<li>Aucun sujet trouvé pour cette catégorie.</li>';
        }
        ?>
    </ul>
    <a href="categorie.php">Retour aux catégories</a>
</body>
</html>
