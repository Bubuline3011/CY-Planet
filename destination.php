<?php
session_start();
// Charger les voyages depuis un fichier JSON
$voyages = json_decode(file_get_contents("data/voyage.json"), true);

// Tri selon le filtre sélectionné
$filtre = $_GET['filtre'] ?? 'recents';
switch ($filtre) {
    case 'notes':
        usort($voyages, fn($a, $b) => $b['note'] <=> $a['note']);
        break;
    case 'aleatoire':
        shuffle($voyages);
        break;
    case 'tous':
        // Pas de tri, on montre tous les voyages dans l'ordre du fichier
        break;
    case 'recents':
    default:
        usort($voyages, fn($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));
        break;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Destinations - CosmoTrip</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="acceuil">
	<?php include 'header.php'; ?>
    <main class="destinations">
        <h2>Nos Voyages CosmoTrip</h2>

        <div class="intro">
            <p>Bienvenue dans notre univers galactique ! Découvrez nos voyages à thème dans des planètes extraordinaires :</p>
            <p>que vous soyez amateur de sport, de musique, de cuisine ou d'aventure, nous avons une destination faite pour vous !</p>
        </div>

        <form method="GET" style="margin-bottom: 30px; text-align:center;">
            <label for="filtre"><strong>Trier par :</strong></label>
            <select name="filtre" id="filtre" class="filtre-select" onchange="this.form.submit()">
                <option value="recents" <?= $filtre === 'recents' ? 'selected' : '' ?>>Les plus récents</option>
                <option value="notes" <?= $filtre === 'notes' ? 'selected' : '' ?>>Les mieux notés</option>
                <option value="aleatoire" <?= $filtre === 'aleatoire' ? 'selected' : '' ?>>Sélection aléatoire</option>
                <option value="tous" <?= $filtre === 'tous' ? 'selected' : '' ?>>Tous les voyages</option>
            </select>
        </form>

        <div class="destination-liste">
            <?php foreach (($filtre === 'tous' ? $voyages : array_slice($voyages, 0, 6)) as $voyage): ?>

                <a class="destination" href="#">
                    <img src="<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['titre']) ?>">
                    <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                    <p>
                        Prix : <?= htmlspecialchars($voyage['prix']) ?> €<br>
                        Note : <?= str_repeat('★', $voyage['note']) . str_repeat('☆', 5 - $voyage['note']) ?>
                    </p>
                    <p><?= htmlspecialchars($voyage['description']) ?></p>
                    <form method="GET" action="voyage_detail.php">
                        <input type="hidden" name="id" value="<?= $voyage['id'] ?>">
                        <button class="acheter-btn" type="submit">Reserver</button>
                    </form>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>


