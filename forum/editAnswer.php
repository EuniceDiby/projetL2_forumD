<?php
session_start();
require('cnx.php'); // Connexion à la base de données


if (isset($_POST['id_answer']) && !empty($_POST['id_answer']) && isset($_POST['new_content']) && !empty($_POST['new_content'])) {
    $id_answer = $_POST['id_answer'];
    $new_content = nl2br(htmlspecialchars($_POST['new_content']));

    $query = $link->prepare("UPDATE reponse SET contenu = ? WHERE id = ? AND id_auteur = ?");
    $query->bind_param('sii', $new_content, $id_answer, $_SESSION['id']);
    if ($query->execute()) {
        header('Location: article.php?id=' . $_GET['id']);
    } else {
        echo "Erreur lors de la modification.";
    }
} elseif (isset($_POST['id_answer'])) {
    $id_answer = $_POST['id_answer'];
    $query = $link->prepare("SELECT contenu FROM reponse WHERE id = ? AND id_auteur = ?");
    $query->bind_param('ii', $id_answer, $_SESSION['id']);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $answer = $result->fetch_assoc();
        ?>
        <form action="" method="post">
            <textarea name="new_content"><?php echo htmlspecialchars($answer['contenu']); ?></textarea>
            <input type="hidden" name="id_answer" value="<?php echo $id_answer; ?>">
            <button type="submit">Modifier</button>
        </form>
        <?php
    } else {
        echo "Réponse introuvable ou non autorisée.";
    }
} else {
    echo "Aucune réponse spécifiée.";
}
?>
