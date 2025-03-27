<?php
session_start();
$voyages = json_decode(file_get_contents("data/voyage.json"), true);
?>

<!DOCTYPE html>
<html lang="fr">
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
                <a href="destination.php" class="btn">Voir les destinations</a>
            </div>
        </section>
    
        <section class="destinations">
            <h2>🌍 Nos destinations les plus populaires</h2>
            <div class="destination-liste">
            <?php foreach (array_slice($voyages, 0, 3) as $voyage): ?>
                <a class="destination" href="voyage_detail.php?id=<?= $voyage['id'] ?>">
                    <img src="<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['titre']) ?>">
                    <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                    <p>Note : <?= str_repeat('★', $voyage['note']) . str_repeat('☆', 5 - $voyage['note']) ?></p>
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
