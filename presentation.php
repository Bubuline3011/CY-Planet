<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <?php include 'header.php'; ?>
    <!-- Section Présentation -->
    <section class="presentation">
        <div class="encadrer">
            <h2>Bienvenue sur Cosmo Trip 🚀</h2>
            <p>
                Planet Vacation est votre agence de voyage intergalactique qui vous permet d'explorer
                les confins de l'univers en toute sécurité et à des prix défiant toutes concurrences ! Grâce à nos technologies avancées de
                téléportation et de vaisseaux spatiaux, vous pouvez désormais visiter des mondes
                extraordinaires et vivre des expériences inoubliables.
            </p>
            <p>
                Découvrez des planètes uniques, goûtez aux spécialités culinaires d'autres galaxies, 
                créez des souvenirs inoubliables et plongez dans des aventures cosmiques exceptionnelles !
            </p>
        </div>
    </section>
     <!-- Section Recherche Rapide -->
     <section class="barre-recherche">
        <h3>🔍 Recherchez votre prochaine destination :</h3>
        <form action="#" method="GET">
            <input type="text" name="query" placeholder="Recherchez une planète..." required>
            <button type="submit">Rechercher</button>
        </form>
        <div class="logo-encadrer">
            <a href="acceuil.html">
                <img src="img/logo.png">
            </a>
        </div>
    </section>

    <!-- Pied de page -->
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
