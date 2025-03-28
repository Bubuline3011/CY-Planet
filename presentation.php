<?php
session_start();
// Lire le fichier JSON
$voyages = json_decode(file_get_contents("data/voyage.json"), true);

// Récupérer le mot-clé de recherche
$motCle = isset($_GET['motcle']) ? strtolower(trim($_GET['motcle'])) : '';

// Filtrer les voyages
$voyagesFiltres = [];
if ($motCle !== '') {
    foreach ($voyages as $voyage) {
        $dansTitre = stripos($voyage['titre'], $motCle) !== false;
        $dansDescription = stripos($voyage['description'], $motCle) !== false;
        $dansMotsCles = array_filter($voyage['mots_cles'], fn($mot) => stripos($mot, $motCle) !== false);

        if ($dansTitre || $dansDescription || !empty($dansMotsCles)) {
            $voyagesFiltres[] = $voyage;
        }
    }
} else {
    $voyagesFiltres = $voyages; // Tous les voyages si aucun mot-clé
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Présentation</title>
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
                les confins de l'univers en toute sécurité et à des prix défiant toutes concurrences !
                Grâce à nos technologies avancées de téléportation et de vaisseaux spatiaux, vous pouvez
                désormais visiter des mondes extraordinaires et vivre des expériences inoubliables.
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
        <form method="GET" action="presentation.php">
            <input type="text" name="motcle" placeholder="Rechercher un voyage..." required>
            <button type="submit">Rechercher</button>
        </form>

        <?php if ($motCle !== ''): ?>
            <h3>Résultats pour "<?= htmlspecialchars($motCle) ?>" :</h3>

            <?php if (empty($voyagesFiltres)): ?>
                <p>Aucun voyage ne correspond à votre recherche.</p>
            <?php else: ?>
                <div class="destination-liste">
                    <?php foreach ($voyagesFiltres as $voyage): ?>
                        <a class="destination" href="voyage_detail.php?id=<?= $voyage['id'] ?>">
                            <img src="<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['titre']) ?>">
                            <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                            <p><strong>Prix :</strong> <?= htmlspecialchars($voyage['prix']) ?> €</p>
                            <p><strong>Note :</strong> <?= htmlspecialchars($voyage['note']) ?>/5 ⭐</p>
                            <p><?= htmlspecialchars($voyage['description']) ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="logo-encadrer">
            <a href="acceuil.php">
                <img src="img/logo.png" alt="Logo Cosmo Trip">
            </a>
        </div>
    </section>

    <!-- Pied de page -->
    <footer>
        <p>&copy; 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>

