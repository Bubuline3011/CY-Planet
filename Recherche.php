<?php
// Charger tous les fichiers JSON des planètes
$planetes = glob('Voyage.json');
$destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
$date_depart = isset($_POST['date_depart']) ? $_POST['date_depart'] : '';
$date_retour = isset($_POST['date_retour']) ? $_POST['date_retour'] : '';
$resultat = null;

// Vérifier si la recherche a été effectuée
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($destination)) {
    foreach ($planetes as $file) {
        $planete = json_decode(file_get_contents($file), true);
        if (strcasecmp($planete['titre'], $destination) == 0) {
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
                <option value="Footbolis">Footbolis</option>
                <option value="AquaWorld">AquaWorld</option>
                <option value="Musicaria">Musicaria</option>
                <option value="Adventuris">Adventuris</option>
                <option value="Médiévalia">Médiévalia</option>
                <option value="Dreamara">Dreamara</option>
                <option value="Ludopolis">Ludopolis</option>
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

                <!-- Affichage de l’image -->
                <img src="<?php echo htmlspecialchars($resultat['image']); ?>" alt="<?php echo htmlspecialchars($resultat['titre']); ?>" class="image-planete">

                <p><strong>Thème :</strong> <?php echo htmlspecialchars($resultat['theme']); ?></p>
                <p><strong>Description :</strong> <?php echo htmlspecialchars($resultat['description']); ?></p>

                <h3>📍 Hébergements disponibles :</h3>
                <ul>
                    <?php 
                    $hebergements = ['Hôtels-bulles flottants', 'Hôtel Galactique', 'Château Cybernétique', 'Suite Thématique'];
                    foreach ($resultat['activites'] as $activite) :
                        if (in_array($activite['nom'], $hebergements)) : ?>
                            <li><?php echo htmlspecialchars($activite['nom']); ?> - <?php echo htmlspecialchars($activite['prix']); ?>€</li>
                        <?php endif;
                    endforeach; 
                    ?>
                </ul>

                <h3>🎯 Activités disponibles :</h3>
                <ul>
                    <?php foreach ($resultat['activites'] as $activite) : ?>
                        <li><?php echo htmlspecialchars($activite['nom']); ?> - <?php echo htmlspecialchars($activite['description']); ?> (<?php echo htmlspecialchars($activite['prix']); ?>€)</li>
                    <?php endforeach; ?>
                </ul>

                <a href="voyage_detail.php?id=<?php echo htmlspecialchars($resultat['id']); ?>" class="button">Voir plus</a>
            </div>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
            <p style="color:red;">⚠️ Aucune destination trouvée. Veuillez réessayer.</p>
        <?php endif; ?>
    </div>
    
    <footer>
        <p>&copy 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
</body>
</html>
