<?php
session_start(); // Assurez-vous que la session est démarrée


if (isset($_FILES['photo']) && !empty($_FILES['photo']['name'])) {
    $tailleMax = 2097152; // 2 Mo
    $extensionValide = array('jpg', 'jpeg', 'gif', 'png');

    if ($_FILES['photo']['size'] <= $tailleMax) {
        $extensionUpload = strtolower(substr(strrchr($_FILES['photo']['name'], '.'), 1));

        if (in_array($extensionUpload, $extensionValide)) {
            $chemin = "image/" . $_SESSION['id'] . "." . $extensionUpload;
            $resultat = move_uploaded_file($_FILES['photo']['tmp_name'], $chemin);

            if ($resultat) {
                // Préparez la requête SQL
                $sql = "UPDATE users SET image = ? WHERE id = ?";
                $stmt = $link->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("si", $chemin, $_SESSION['id']);
                    if ($stmt->execute()) {
                        header('Location: profile.php?message=' . urlencode("Photo de profil mise à jour avec succès."));
                        exit;
                    } else {
                        $message = "Erreur lors de la mise à jour de la base de données : " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "Erreur de préparation de la requête : " . $link->error;
                }
            } else {
                $message = "Erreur durant l'importation de votre photo de profil.";
            }
        } else {
            $message = "Votre photo doit être au format 'jpg', 'jpeg', 'gif', 'png'.";
        }
    } else {
        $message = "Votre photo de profil ne doit pas dépasser 2 Mo.";
    }
} else {
    $message = "Aucun fichier n’a été sélectionné.";
}

if (isset($message)) {
    header("Location: profile.php?message=" . urlencode($message));
    exit;
}
?>
