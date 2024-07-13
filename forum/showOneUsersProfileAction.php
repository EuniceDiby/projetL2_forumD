<?php
session_start();
require('cnx.php');


// Get the current date and time
$date = date('d-m-Y H:i:s');

if(isset($_SESSION['id'])) {
    $idOfUser = $_SESSION['id'];

    // Update the last connection date
    $updateLastConnection = $link->prepare('UPDATE users SET derniere_connexion = ? WHERE id = ?');
    $updateLastConnection->bind_param('si', $date, $idOfUser);
    $updateLastConnection->execute();

    // Update the registration date
    $updateRegistrationDate = $link->prepare('UPDATE users SET date_inscription = ? WHERE id = ?');
    $updateRegistrationDate->bind_param('si', $date, $idOfUser);
    $updateRegistrationDate->execute();

    $checkIfUserExists = $link->prepare('SELECT nom, email, derniere_connexion, date_inscription FROM users WHERE id = ?');
    $checkIfUserExists->bind_param('i', $idOfUser);
    $checkIfUserExists->execute();
    $result = $checkIfUserExists->get_result();

    if($result->num_rows > 0) {
        $usersInfos = $result->fetch_assoc();

        $user_nom = $usersInfos['nom'];
        $user_email = $usersInfos['email'];
        $last_connection = $usersInfos['derniere_connexion'];
        $registration_date = $usersInfos['date_inscription'];

        $getHisQuestions = $link->prepare('SELECT * FROM questions WHERE id_auteur = ?');
        $getHisQuestions->bind_param('i', $idOfUser);
        $getHisQuestions->execute();
        $questionsResult = $getHisQuestions->get_result();
    } else {
        $message = "Aucun utilisateur trouvé";
    }
} else {
    $message = "Aucun utilisateur trouvé";
}
?>



