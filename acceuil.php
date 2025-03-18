<?php
session_start();
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="acceuil">
    <?php include 'header.php'; ?> <!-- Inclusion du header dynamique -->
    
    <main class="contenue">
        <section class="principal">
            <div class="principal-contenue">
                <h1>Découvrez l'univers avec Cosmo Trip</h1>
                <p>Voyagez vers des mondes extraordinaires grâce à nos vaisseaux et portails intergalactiques.</p>
                <a href="destinations.html" class="btn">Voir les destinations</a>
            </div>
        </section>
    
        <section class="destinations">
            <h2>🌍 Nos destinations les plus populaires</h2>
            <div class="destination-liste">
                <!-- Destination Footbolis -->
                <a href="Footbol.html" class="destination">
                    <img src="img/Footbol.jpg" alt="Footbolis">
                    <h3>Footbolis</h3>
                    <p>Le paradis des amateurs de football intergalactique !</p>
                </a>
                
                <!-- Destination Dreamara -->
                <a href="Dreamara.html" class="destination">
                    <img src="img/Dreamara.jpg" alt="Dreamara">
                    <h3>Dreamara</h3>
                    <p>Un retour dans un pays imaginaire</p>
                </a>
                
                <!-- Destination AquaWorld -->
                <a href="aquaworld.html" class="destination">
                    <img src="img/AquaWorld.jpg" alt="AquaWorld">
                    <h3>AquaWorld</h3>
                    <p>Un voyage sous-marin dans un océan infini.</p>
                </a>
            </div>
        </section>
    </main>
    
    <!-- Pied de page -->
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
