<?php
// On démarre la session pour garder des infos entre les pages
session_start();

// On lit le fichier JSON qui contient les infos sur les voyages
$voyages = json_decode(file_get_contents("data/voyage.json"), true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Pour que la page s'affiche bien sur tous les écrans (téléphones, ordis, etc) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Accueil</title>
    
    <!-- On relie la feuille de style CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- On ajoute une bibliothèque d'icônes (Boxicons) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="acceuil">
    <!-- On ajoute le header (barre du haut) depuis un autre fichier -->
    <?php include 'header.php'; ?> <!-- Inclusion du header dynamique -->
    
    <main class="contenue">
        <!-- Partie principale de l'accueil -->
        <section class="principal">
            <div class="principal-contenue">
                <h1>Découvrez l'univers avec Cosmo Trip</h1>
                <p>Voyagez vers des mondes extraordinaires grâce à nos vaisseaux et portails intergalactiques.</p>
                
                <!-- Bouton qui emmène vers la page avec toutes les destinations -->
                <a href="destination.php" class="btn">Voir les destinations</a>
            </div>
        </section>
    
        <!-- Section avec les destinations les plus populaires -->
        <section class="destinations">
            <h2>🌍 Nos destinations les plus populaires</h2>
            <div class="destination-liste">
                <?php 
                // On affiche seulement les 3 premiers voyages du fichier JSON
                foreach (array_slice($voyages, 0, 3) as $voyage): 
                ?>
                    <!-- Chaque destination est un lien vers sa page de détails -->
                    <a class="destination" href="voyage_detail.php?id=<?= $voyage['id'] ?>">
                        <!-- On affiche l'image du voyage -->
                        <img src="<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['titre']) ?>">
                        
                        <!-- Titre du voyage -->
                        <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                        
                        <!-- Affichage de la note avec des étoiles -->
                        <p>Note : <?= str_repeat('★', $voyage['note']) . str_repeat('☆', 5 - $voyage['note']) ?></p>
                        
                        <!-- Prix du voyage -->
                        <p><?= htmlspecialchars($voyage['prix']) ?> €</p>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    
    <!-- Pied de page -->
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
