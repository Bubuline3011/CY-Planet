<?php
session_start();
?>

<header>
    <a href="acceuil.php" class="logo">
        <h1>Cosmo Trip</h1>
        <img src="img/logo.png" alt="Planet Vacation logo">
    </a>
    <nav>
        <ul>
            <li><a href="presentation.php">À propos</a></li>
            <li><a href="destinations.php">Destinations</a></li>
            <li><a href="recherche.php">Recherche</a></li>
            <li><a href="profil.php">Mon profil</a></li>

            <?php if (isset($_SESSION['email'])): ?>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="inscription.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

