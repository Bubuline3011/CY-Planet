<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="inscription">
	<?php include 'header.php'; ?>
    <div class="boite">
        <form action="acceuil.html" method="POST">
            <h1>Inscription</h1>

            <div class="saisie">
                <input type="text" name="nom" placeholder="Nom" required autocomplete="family-name">
                <i class='bx bx-user'></i>
            </div>

            <div class="saisie">
                <input type="text" name="prenom" placeholder="Prénom" required autocomplete="given-name">
                <i class='bx bx-user'></i>
            </div>

            <div class="saisie">
                <input type="email" name="email" placeholder="Email" required autocomplete="email">
                <i class='bx bx-envelope'></i>
            </div>

            <div class="saisie">
                <input type="password" name="password" placeholder="Mot de passe" required autocomplete="new-password">
                <i class='bx bx-lock'></i>
            </div>

            <div class="saisie">
                <input type="number" name="age" placeholder="Âge" required min="1">
                <i class='bx bx-calendar'></i>
            </div>

            <div class="saisie">
                <input type="tel" name="telephone" placeholder="Numéro de téléphone" required autocomplete="tel">
                <i class='bx bx-phone'></i>
            </div>

            <button type="submit" class="button">S'inscrire</button>

            <div class="lien-inscription">
                <p>Vous avez déjà un compte ? <a href="connexion.html">Se connecter</a></p>
            </div>
        </form>
    </div>

    <!-- Pied de page -->
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>

</html>
