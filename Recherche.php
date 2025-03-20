<?php
// Charger tous les fichiers JSON depuis le bon dossier "Voyage/"
$planetes = glob('Voyage/*.json');

$destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
$date_depart = isset($_POST['date_depart']) ? $_POST['date_depart'] : '';
$date_retour = isset($_POST['date_retour']) ? $_POST['date_retour'] : '';
$resultat = null;

// Vérifier si la recherche a été effectuée
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($destination)) {
    foreach ($planetes as $file) {
        $planete = json_decode(file_get_contents($file), true);

        // Vérifier que le fichier JSON est bien structuré
        if (isset($planete['titre']) && strcasecmp(trim($planete['titre']), $destination) == 0) {
            $resultat = $planete;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="recherche">
    <?php include 'header.php'; ?>
    <div class="recherche-boite">
        <h1>Trouvez votre séjour idéal :</h1>
    
        <form method="POST" action="search.php">
            <label for="destination">Destination :</label>
            <select id="destination" name="destination" required> 
                <option value="" disabled selected>Choisissez une destination</option>
                <option value="footbolis">Footbolis</option>
                <option value="aquaworld">AquaWorld</option>
                <option value="musicaria">Musicaria</option>
                <option value="adventuris">Adventuris</option>
                <option value="medievalia">Médiévalia</option>
                <option value="dreamara">Dreamara</option>
                <option value="ludopolis">Ludopolis</option>
            </select>
    
            <div>
                <label for="depart">Date de départ :</label>
                <input type="date" id="depart" name="date_depart" required>
            </div>
    
            <div>
                <label for="retour">Date de retour :</label> 
                <input type="date" id="retour" name="date_retour" required>
            </div>
    
            <button type="submit" class="button">Rechercher</button>
        </form>

        <!-- Résultat de la recherche -->
        <?php if ($resultat) : ?>
            <div class="resultat-container">
                <h2>🌍 Destination trouvée : <?php echo htmlspecialchars($resultat['titre']); ?></h2>

                <img src="<?php echo htmlspecialchars($resultat['image']); ?>" alt="<?php echo htmlspecialchars($resultat['titre']); ?>" class="image-planete">

                <p><strong>Thème :</strong> <?php echo htmlspecialchars($resultat['theme']); ?></p>
                <p><strong>Description :</strong> <?php echo htmlspecialchars($resultat['description']); ?></p>

                <h3>📍 Activités disponibles :</h3>
                <ul>
                    <?php foreach ($resultat['activites'] as $activite) : ?>
                        <li><?php echo htmlspecialchars($activite['nom']); ?> - <?php echo htmlspecialchars($activite['description']); ?> (<?php echo htmlspecialchars($activite['prix']); ?>€)</li>
                    <?php endforeach; ?>
                </ul>

                <a href="voyage_detail.php?id=<?php echo htmlspecialchars($resultat['id']); ?>" class="button">Voir plus</a>
            </div>
        <?php else : ?>
            <p style="color:red; font-weight:bold;">⚠️ Aucune destination trouvée. Vérifiez le nom et réessayez.</p>
        <?php endif; ?>
    </div>
    
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
