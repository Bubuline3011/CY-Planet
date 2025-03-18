<?php
session_start();

// Charger le fichier JSON contenant les utilisateurs
$usersFile = 'utilisateur.json';
$usersData = json_decode(file_get_contents($usersFile), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    foreach ($usersData as $user) {
        if ($user['email'] === $email && $user['motdepasse'] === $password) {
            // Stocker les informations utilisateur en session
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['nom'] = $user['nom'];
		$_SESSION['connecte'] = true;
            // Mettre à jour la dernière connexion dans le JSON
            $user['derniere_connexion'] = date("Y-m-d");
            file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

            // Rediriger vers la page d'accueil
            
            header("Location: acceuil.php");
            exit();
        }
    }

    // Si aucun utilisateur ne correspond
    $erreur = "Email ou mot de passe incorrect.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="connexion">
	<header>
        <a href="acceuil.html" class="logo">
            <h1> Cosmo Trip </h1>
            <img src="img/logo.png" alt="Planet Vacation logo">
        </a>
        <nav>
            <ul>
                <li><a href="presentation.html">A propos</a></li>
                <li><a href="destinations.html">Destinations</a></li>
                <li><a href="recherche.html">Recherche</a></li>
                <li><a href="profil.html">Mon profil</a></li>
                <li><a href="connexion.html">Connexion</a></li>
                <li><a href="inscription.html">Inscription</a></li>
            </ul>
        </nav>
    </header>
    <div class="boite">
        <form action="connexion.php" method="POST">
            <h1>Connexion</h1>
            <?php if (isset($erreur)) echo "<p class='erreur'>$erreur</p>"; ?>
            <div class="saisie">
                <input type="email" name="email" placeholder="Email" required autocomplete="email">
                <i class='bx bxs-envelope'></i>
            </div>

            <div class="saisie">
                <input type="password" name="password" placeholder="Mot de passe" required>
                <i class='bx bx-lock'></i>
            </div>

            <div class="oublie">
                <a href="#">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="button">Se connecter</button>

            <div class="lien-inscription">
                <p>Vous n'avez pas de compte ? <a href="inscription.html">S'inscrire</a></p>
            </div>
        </form>
    </div>
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>

</html>

