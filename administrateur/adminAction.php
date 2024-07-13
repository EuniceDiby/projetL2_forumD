<?php
session_start();
require('cnx.php'); // Connexion à la base de données

// Vérifier le rôle de l'utilisateur (désactivé pour les fins de démonstration)
/*if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}*/

if (isset($_POST['block_user'])) {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        $block = $_POST['block'] ? 1 : 0;

        $query = $link->prepare('UPDATE users SET bloquer = ? WHERE id = ?');
        $query->bind_param('ii', $block, $user_id);

        if ($query->execute()) {
            $notificationMessage = $block ? "Votre compte a été bloqué par un administrateur." : "Votre compte a été débloqué par un administrateur.";
            $insertNotification = $link->prepare('INSERT INTO notifications (id_utilisateur, message) VALUES (?, ?)');
            $insertNotification->bind_param('is', $user_id, $notificationMessage);

            if ($insertNotification->execute()) {
                echo "L'utilisateur a été " . ($block ? 'bloqué' : 'débloqué') . " avec succès.";
            } else {
                echo "Erreur lors de l'envoi de la notification.";
            }
        } else {
            echo "Erreur lors de la mise à jour de l'utilisateur.";
        }
    } else {
        echo "ID utilisateur non défini dans le formulaire POST.";
    }
}

if (isset($_POST['delete_message'])) {
    $message_id = $_POST['message_id'];

    // Récupération des informations sur la réponse pour envoyer la notification
    $query = $link->prepare('SELECT id_auteur FROM reponse WHERE id = ?');
    if ($query === false) {
        die('Erreur de préparation de la requête : ' . $link->error);
    }
    $query->bind_param('i', $message_id);
    if ($query->execute()) {
        $result = $query->get_result();
        $message = $result->fetch_assoc();
        $user_id = $message['id_auteur'];

        $query = $link->prepare('DELETE FROM reponse WHERE id = ?');
        if ($query === false) {
            die('Erreur de préparation de la requête : ' . $link->error);
        }
        $query->bind_param('i', $message_id);
        if ($query->execute()) {
            // Envoi de la notification
            $notificationMessage = "Votre réponse a été supprimée par un modérateur.";
            $insertNotification = $link->prepare('INSERT INTO notifications (user_id, message) VALUES (?, ?)');
            if ($insertNotification === false) {
                die('Erreur de préparation de la requête : ' . $link->error);
            }
            $insertNotification->bind_param('is', $user_id, $notificationMessage);
            if ($insertNotification->execute()) {
                echo "Le message a été supprimé avec succès.";
            } else {
                echo "Erreur lors de l'envoi de la notification.";
            }
        } else {
            echo "Erreur lors de la suppression du message.";
        }
    } else {
        echo "Erreur lors de la récupération des informations sur la réponse.";
    }
}

if (isset($_POST['delete_question'])) {
    if (isset($_POST['question_id'])) {
        $question_id = $_POST['question_id'];

        // Récupération des informations sur la question pour envoyer la notification
        $query = $link->prepare('SELECT id_auteur FROM questions WHERE id = ?');
        if ($query === false) {
            die('Erreur de préparation de la requête : ' . $link->error);
        }
        $query->bind_param('i', $question_id);
        if ($query->execute()) {
            $result = $query->get_result();
            if ($result->num_rows === 0) {
                echo "Aucune question trouvée avec cet identifiant.";
                exit; // Sortir du script si aucune question n'est trouvée
            }
            $question = $result->fetch_assoc();
            $user_id = $question['id_auteur'];

            $query = $link->prepare('DELETE FROM questions WHERE id = ?');
            if ($query === false) {
                die('Erreur de préparation de la requête : ' . $link->error);
            }
            $query->bind_param('i', $question_id);
            if ($query->execute()) {
                // Envoi de la notification
                $notificationMessage = "Votre question a été supprimée par un modérateur.";
                $insertNotification = $link->prepare('INSERT INTO notifications (user_id, message) VALUES (?, ?)');
                if ($insertNotification === false) {
                    die('Erreur de préparation de la requête : ' . $link->error);
                }
                $insertNotification->bind_param('is', $user_id, $notificationMessage);
                if ($insertNotification->execute()) {
                    echo "La question a été supprimée avec succès.";
                } else {
                    echo "Erreur lors de l'envoi de la notification.";
                }
            } else {
                echo "Erreur lors de la suppression de la question.";
            }
        } else {
            echo "Erreur lors de la récupération des informations sur la question.";
        }
    } else {
        echo "ID question non défini dans le formulaire POST.";
    }
}

?>
