<?php
// On vérifie si une session a déjà été démarrée
// Si non, on démarre la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Barre du haut (header) qui contient le logo et le menu de navigation -->
<header>
    <!-- Logo avec lien vers la page d'accueil -->
    <a href="acceuil.php" class="logo">
        <h1>Cosmo Trip</h1>
        <img src="img/logo.png" alt="Planet Vacation logo">
    </a>

    <!-- Menu de navigation -->
    <nav>
        <ul>
            <li><a href="presentation.php">À propos</a></li>
            <li><a href="destination.php">Destinations</a></li>
            <li><a href="recherche.php">Recherche</a></li>

            <!-- Si l'utilisateur est connecté (email présent dans la session) -->
            <?php if (!empty($_SESSION['email'])): ?>
            	<li><a href="profil.php">Mon profil</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            <?php else: ?>
                <!-- Si l'utilisateur n'est pas connecté -->
                <li><a href="connexion.php">Connexion</a></li>
                <li><a href="inscription.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
