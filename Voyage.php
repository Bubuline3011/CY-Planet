<?php
// Charger tous les fichiers JSON des planètes
$planetes = glob('Voyage/*.json');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Voyages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="acceuil">

    <?php include 'header.php'; ?>

    <div class="destinations">
        <h2>🌍 Explorez toutes nos destinations</h2>

        <!-- 📍 Liste des destinations -->
        <div class="destination-liste">
            <?php foreach ($planetes as $file) : ?>
                <?php $planete = json_decode(file_get_contents($file), true); ?>
                <div class="destination">
                    <img src="<?php echo htmlspecialchars($planete['image']); ?>" alt="<?php echo htmlspecialchars($planete['titre']); ?>">
                    <h3><?php echo htmlspecialchars($planete['titre']); ?></h3>
                    <p><?php echo htmlspecialchars($planete['description']); ?></p>
                    <div class="btn-container">
                        <a href="voyage_detail.php?id=<?php echo htmlspecialchars($planete['id']); ?>" class="button">Voir plus</a>
                        <a href="reservation.php?id=<?php echo htmlspecialchars($planete['id']); ?>" class="button reserve-btn">Réserver</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p> &copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>

</body>
</html>
