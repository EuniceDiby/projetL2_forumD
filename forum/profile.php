<?php 
    require('showOneUsersProfileAction.php');

    // Assurez-vous que l'utilisateur est connecté
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    $userId = $_SESSION['id'];

    // Vérifiez si la connexion à la base de données est réussie
    if ($link->connect_error) {
        die("Erreur de connexion : " . $link->connect_error);
    }

    // Récupérer les détails de l'utilisateur
    $sql = "SELECT nom, email, derniere_connexion, date_inscription,role FROM users WHERE id = ?";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_nom = $user['nom'];
            $user_email = $user['email'];
            $derniere_connexion = $user['derniere_connexion'];
            $date_inscription = $user['date_inscription'];
            $user_role = $user['role'];
            $initial = strtoupper($user_nom[0]); // Définir l'initiale
            $color = ["#1abc9c", "#3498db", "#9b59b6", "#e74c3c", "#f1c40f"]; // Tableau de couleurs
            $avatar_color = $color[array_rand($color)]; // Choix aléatoire de la couleur d'avatar
        } else {
            echo "Utilisateur non trouvé.";
            exit;
        }
    } else {
        // Affiche l'erreur de préparation de la requête
        echo "Erreur de préparation de la requête : " . $link->error;
        exit;
    }

    // Récupérer les questions de l'utilisateur
    $sql = "SELECT titre, description, nom_auteur, date_publication FROM questions WHERE id_auteur = ?";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $questionsResult = $stmt->get_result();
    } else {
        // Affiche l'erreur de préparation de la requête pour les questions
        echo "Erreur de préparation de la requête pour les questions : " . $link->error;
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="profile.css">
    <style>
        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: <?php echo $avatar_color; ?>;
            color: #fff;
            font-size: 2em;
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header class="head_menu">
        <div class="div_menu">
            <nav class="menu_1">
                <div class="logo">
                    <a href="#">Forum <span>DEM</span></a>
                </div>
            </nav>
            <nav class="menu_2">
                <div class="nav_menu">
                    <a class="list_menu" href="acceuil.html">Accueil</a>
                    <a class="list_menu" href="rechercherQuestion.php">Les questions</a>
                    <a class="list_menu" href="mes-questions.php">Mes questions</a>
                    <a class="list_menu" href="profile.php">Mon profil</a>
                    <a class="list_menu" href="deconnexion.php">Déconnexion</a>
                </div>
            </nav>
        </div>
    </header>

    <div class="profil-container">
        <div class="profil-header">
            <div class="avatar"><?php echo $initial; ?></div>
            <div class="profil-details">
                <h1 class="profil-nom"><?php echo htmlspecialchars($user_nom); ?></h1>
                <p class="profil-role">Rôle: <?php echo htmlspecialchars($user_role)?></p>
                <p class="profil-activite">Dernière activité: <?php echo date("d/ m/ Y, H:i", strtotime($derniere_connexion)); ?></p>
                <p class="profil-inscription">Inscrit: <?php echo date("d/ m/ Y", strtotime($date_inscription)); ?></p>
            </div>
        </div>

        <?php if (isset($_GET['message'])) { ?>
            <div class="message">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php } ?>

        <div class="profil-content">
            <div class="tab">
                <button class="tablinks" onclick="openOnglet(event, 'Activites')">Activités</button>
                <button class="tablinks" onclick="openOnglet(event, 'APropos')">À propos</button>
                <button class="tablinks" onclick="openOnglet(event, 'Media')">Média</button>
            </div>

            <div id="Activites" class="tabcontent">
                <h3>Activités</h3>
                <?php if (isset($questionsResult)) {
                    while ($question = $questionsResult->fetch_assoc()) { ?>
                        <div class="card">
                            <div class="card-header">
                                <?php echo htmlspecialchars($question['titre']); ?>
                            </div>
                            <div class="card-body">
                                <?php echo nl2br(htmlspecialchars($question['description'])); ?>
                            </div>
                            <div class="card-footer">
                                Par <em><?php echo htmlspecialchars($question['nom_auteur']); ?> le <?php echo htmlspecialchars($question['date_publication']); ?></em>
                            </div>
                        </div>
                        <br>
                    <?php }
                } ?>
            </div>

            <div id="APropos" class="tabcontent">
                <h3>À propos</h3>
                <?php if (isset($user_email) && isset($user_nom)) { ?>
                    <div class="card">
                        <div class="card-body">
                            <p><b>Email/Nom utilisateur : </b> @ <?php echo htmlspecialchars($user_email); ?></p>
                            <hr>
                            <p> <b>Nom :</b> <?php echo htmlspecialchars($user_nom); ?></p>
                        </div>
                    </div>
                    <br>
                <?php } ?>
            </div>

            <div id="Media" class="tabcontent">
                <h3>Média</h3>
                <p>Contenu média ici...</p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
