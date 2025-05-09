<?php
// On démarre la session pour garder les infos de l'utilisateur
session_start();

// Si l'utilisateur est déjà connecté, on le redirige automatiquement
if (isset($_SESSION['connecte']) && $_SESSION['connecte'] === true) {
    // Si c'est un admin, on l'envoie vers la page admin
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php");
    } else {
        // Sinon, on l'envoie vers la page d'accueil classique
        header("Location: acceuil.php");
    }
    exit();
}

// On charge les données des utilisateurs depuis le fichier JSON
$usersFile = 'data/utilisateur.json';
$usersData = json_decode(file_get_contents($usersFile), true);

// Si le formulaire a été envoyé (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On récupère l'email et le mot de passe du formulaire
    $email = $_POST['email'] ?? '';
    $password = $_POST['motdepasse'] ?? '';

    // On vérifie si l'utilisateur existe dans le fichier JSON
    foreach ($usersData as &$user) {
        if ($user['email'] === $email && $user['motdepasse'] === $password) {
            // Si c'est bon, on stocke ses infos dans la session
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['connecte'] = true;

            // On met à jour la date de dernière connexion
            $user['derniere_connexion'] = date("Y-m-d");

            // On réécrit le fichier JSON avec la nouvelle date
            file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

            // On redirige l'utilisateur selon son rôle
            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: acceuil.php");
            }
            exit();
        }
    }

    // Si on ne trouve aucun utilisateur avec ces infos
    $erreur = "Email ou mot de passe incorrect.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <!-- Pour un bon affichage sur tous les écrans -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <!-- Fichier CSS principal -->
    <link id="theme-css" rel="stylesheet" href="style.css">

    <!-- Icônes Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="connexion">
    <!-- On inclut le header commun à toutes les pages -->
    <?php include 'header.php'; ?>

    <div class="boite">
        <!-- Formulaire de connexion -->
        <form action="connexion.php" method="POST">
            <h1>Connexion</h1>

            <!-- Message d’erreur s’il y a une erreur de connexion -->
            <?php if (isset($erreur)) echo "<p class='erreur'>$erreur</p>"; ?>

            <!-- Saisie de l'email -->
            <div class="saisie">
                <input type="email" name="email" placeholder="Email" required autocomplete="email">
                <i class='bx bxs-envelope'></i>
            </div>

            <!-- Saisie du mot de passe -->
           <div class="saisie motdepasse">
 		 <input type="password" name="motdepasse" placeholder="Mot de passe" required autocomplete="new-password">
  		<button type="button" class="toggle-password">👁️</button>
  		<i class='bx bx-lock'></i>
	</div>
            <!-- Lien mot de passe oublié (non fonctionnel ici) -->
            <div class="oublie">
                <a href="#">Mot de passe oublié ?</a>
            </div>

            <!-- Bouton pour se connecter -->
            <button type="submit" class="button">Se connecter</button>

            <!-- Lien vers la page d'inscription -->
            <div class="lien-inscription">
                <p>Vous n'avez pas de compte ? <a href="inscription.php">S'inscrire</a></p>
            </div>
        </form>
    </div>

    <!-- Pied de page -->
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
    <script src="js/theme.js"></script>
    <script src="js/formulaire.js"></script>

</body>

</html>
