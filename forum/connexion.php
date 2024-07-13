<?php
require('cnx.php');
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // Gestion de la connexion
        if (isset($_POST['email']) && isset($_POST['mot_de_passe'])) {
            $email = $_POST['email'];
            $mot_de_passe = $_POST['mot_de_passe'];

            // Préparer la requête avec MySQLi
            $stmt = $link->prepare("SELECT id, nom, mot_de_passe, bloquer FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Vérifier si un utilisateur a été trouvé
            if ($stmt->num_rows > 0) {
                // Lier les résultats aux variables
                $stmt->bind_result($id, $nom, $hash, $bloquer);
                $stmt->fetch();

                // Vérifier si l'utilisateur est bloqué
                if ($bloquer) {
                    $message = "Votre compte a été bloqué. Veuillez contacter l'administrateur.";
                } else {
                    // Vérifier le mot de passe
                    if (password_verify($mot_de_passe, $hash)) {
                        $_SESSION['id'] = $id;
                        $_SESSION['nom'] = $nom;
                        header('Location: acceuil.html');
                        exit;
                    } else {
                        $message = "Mauvais mot de passe.";
                    }
                }
            } else {
                $message = "Email non trouvé.";
            }

            $stmt->close();
        } else {
            $message = "Veuillez remplir tous les champs.";
        }
    } elseif (isset($_POST['register'])) {
        // Gestion de l'inscription
        if (isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['mot_de_passe'])) {
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

            $stmt = $link->prepare("INSERT INTO users (nom, email, mot_de_passe) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nom, $email, $mot_de_passe);

            if ($stmt->execute()) {
                $message = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            } else {
                $message = "Erreur lors de l'inscription. Veuillez réessayer.";
            }

            $stmt->close();
        } else {
            $message = "Veuillez remplir tous les champs.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="connexion.css" />
    <title>Connexion & inscription</title>
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="connexion.php" class="sign-in-form" method="post">
                    <h2 class="title">Connexion</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="email" name="email" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="mot_de_passe" placeholder="Password" required />
                    </div>
                    <input type="submit" name="login" value="Connexion" class="btn solid" />
                    <p class="social-text">Ou connectez-vous avec une plateforme sociale</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    <?php if (!empty($message) && isset($_POST['login'])): ?>
                        <p><?php echo $message; ?></p>
                    <?php endif; ?>
                </form>

                <form action="connexion.php" class="sign-up-form" method="post">
                    <h2 class="title">Inscription</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="nom" placeholder="Nom" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required />
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="mot_de_passe" placeholder="Password" required />
                    </div>

                    <input type="hidden" name="role" value="administrateur" />

                    <input type="submit" name="register" class="btn" value="Inscription" />
                    <p class="social-text">Ou inscrivez-vous avec une plateforme sociale</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    <?php if (!empty($message) && isset($_POST['register'])): ?>
                        <p class = "message"><?php echo $message; ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Nouveau ici ?</h3>
                    <p>
                        Appuyez sur inscription pour vous inscrire!
                    </p>
                    <button class="btn transparent" id="sign-up-btn">
                        Inscription
                    </button>
                </div>
                
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>L'un de nous ?</h3>
                    <p>
                        Cliquez ici pour vous connecter.
                    </p>
                    <button class="btn transparent" id="sign-in-btn">
                        Connexion
                    </button>
                </div>
              
            </div>
        </div>
    </div>
    <script src="app.js"></script>
</body>
</html>
