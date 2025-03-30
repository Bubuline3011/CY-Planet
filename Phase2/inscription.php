<?php
// On démarre la session
session_start();

// On lit le fichier JSON contenant les utilisateurs déjà inscrits
$usersFile = 'data/utilisateur.json';
$usersData = json_decode(file_get_contents($usersFile), true);

// Si l'utilisateur a envoyé le formulaire (méthode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On récupère les données saisies dans le formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $motdepasse = $_POST['motdepasse'];
    $age = trim($_POST['age']);
    $telephone = trim($_POST['telephone']);
    
    // Vérifier si l'email est bien écrit
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse email n'est pas valide.";
    }

    // Vérifier si l'âge est bien un nombre positif
    elseif (!is_numeric($age) || (int)$age <= 0) {
        $erreur = "L'âge doit être un nombre valide.";
    }

    // Vérifier si le téléphone contient bien 10 chiffres
    elseif (!preg_match('/^[0-9]{10}$/', $telephone)) {
        $erreur = "Le numéro de téléphone doit contenir 10 chiffres.";
    }
    
    // Vérifier si l'email est déjà utilisé
    foreach ($usersData as $user) {
        if ($user['email'] === $email) {
            $erreur = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
            break;
        }
    }

    // Si aucune erreur n’a été détectée, on crée le nouvel utilisateur
    if (!isset($erreur)) {
        // On définit le tableau avec les infos du nouvel utilisateur
        $nouvel_utilisateur = [
            "email" => $_POST['email'],
            "motdepasse" => $_POST['motdepasse'],
            "role" => "normal", // par défaut
            "prenom" => $_POST['prenom'],
            "nom" => $_POST['nom'],
            "age" => (int)$_POST['age'],
            "telephone" => $_POST['telephone'],
            "date_inscription" => date("Y-m-d"),
            "derniere_connexion" => null,
            "voyages_consultes" => [],
            "voyages_achetes" => [],
            "coordonnees_bancaires" => [
                "numero_carte" => "",
                "date_expiration" => "",
                "cvv" => ""
            ]
        ];

        // On ajoute ce nouvel utilisateur à la liste
        $usersData[] = $nouvel_utilisateur;

        // On réécrit le fichier JSON avec le nouveau tableau
        file_put_contents($usersFile, json_encode($usersData, JSON_PRETTY_PRINT));

        // Connexion automatique après l’inscription
        $_SESSION['email'] = $email;
        $_SESSION['role'] = "normal";
        $_SESSION['prenom'] = $prenom;
        $_SESSION['nom'] = $nom;
        $_SESSION['connecte'] = true;

        // Redirection vers l'accueil
        header("Location: acceuil.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Affichage responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <!-- Feuille de style -->
    <link rel="stylesheet" href="style.css">

    <!-- Icônes boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="inscription">
    <!-- Header avec le menu -->
    <?php include 'header.php'; ?>

    <div class="boite">
        <!-- Formulaire d’inscription -->
        <form action="inscription.php" method="POST">
            <h1>Inscription</h1>

            <!-- Affichage du message d’erreur s’il y en a -->
            <?php if (isset($erreur)) echo "<p style='color: red;'>$erreur</p>"; ?>

            <!-- Champ Nom -->
            <div class="saisie">
                <input type="text" name="nom" placeholder="Nom" required autocomplete="family-name">
                <i class='bx bx-user'></i>
            </div>

            <!-- Champ Prénom -->
            <div class="saisie">
                <input type="text" name="prenom" placeholder="Prénom" required autocomplete="given-name">
                <i class='bx bx-user'></i>
            </div>

            <!-- Champ Email -->
            <div class="saisie">
                <input type="email" name="email" placeholder="Email" required autocomplete="email">
                <i class='bx bx-envelope'></i>
            </div>

            <!-- Champ Mot de passe -->
            <div class="saisie">
                <input type="password" name="motdepasse" placeholder="Mot de passe" required autocomplete="new-password">
                <i class='bx bx-lock'></i>
            </div>

            <!-- Champ Âge -->
            <div class="saisie">
                <input type="number" name="age" placeholder="Âge" required min="1">
                <i class='bx bx-calendar'></i>
            </div>

            <!-- Champ Téléphone -->
            <div class="saisie">
                <input type="tel" name="telephone" placeholder="Numéro de téléphone" required autocomplete="tel">
                <i class='bx bx-phone'></i>
            </div>

            <!-- Bouton pour envoyer le formulaire -->
            <button type="submit" class="button">S'inscrire</button>

            <!-- Lien vers la page de connexion -->
            <div class="lien-inscription">
                <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
            </div>
        </form>
    </div>

    <!-- Pied de page -->
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
