<?php
// On démarre la session
session_start();

// On charge les voyages depuis un fichier JSON
$voyages = json_decode(file_get_contents("data/voyage.json"), true);

// On récupère le filtre choisi dans l'URL (GET), sinon on prend "recents" par défaut
$filtre = $_GET['filtre'] ?? 'recents';

// On trie les voyages selon le filtre choisi
switch ($filtre) {
    case 'notes':
        // Trier par note (du meilleur au moins bon)
        usort($voyages, fn($a, $b) => $b['note'] <=> $a['note']);
        break;
    case 'aleatoire':
        // Mélanger les voyages au hasard
        shuffle($voyages);
        break;
    case 'tous':
        // Pas de tri : on garde l'ordre du fichier
        break;
    case 'recents':
    default:
        // Trier par date, les plus récents en premier
        usort($voyages, fn($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));
        break;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Destinations - CosmoTrip</title>

    <!-- Lien vers le fichier CSS -->
    <link id="theme-css" rel="stylesheet" href="style.css">
</head>

<body class="acceuil">
    <!-- Inclusion du header -->
    <?php include 'header.php'; ?>

    <main class="destinations">
        <h2>Nos Voyages CosmoTrip</h2>

        <!-- Petit texte d’intro -->
        <div class="intro">
            <p>Bienvenue dans notre univers galactique ! Découvrez nos voyages à thème dans des planètes extraordinaires :</p>
            <p>que vous soyez amateur de sport, de musique, de cuisine ou d'aventure, nous avons une destination faite pour vous !</p>
        </div>

        <!-- Formulaire pour choisir un filtre de tri -->
        <form method="GET" style="margin-bottom: 30px; text-align:center;">
            <label for="filtre"><strong>Trier par :</strong></label>
            <select name="filtre" id="filtre" class="filtre-select" onchange="this.form.submit()">
                <!-- Chaque option est sélectionnée si elle correspond au filtre actif -->
                <option value="recents" <?= $filtre === 'recents' ? 'selected' : '' ?>>Les plus récents</option>
                <option value="notes" <?= $filtre === 'notes' ? 'selected' : '' ?>>Les mieux notés</option>
                <option value="aleatoire" <?= $filtre === 'aleatoire' ? 'selected' : '' ?>>Sélection aléatoire</option>
                <option value="tous" <?= $filtre === 'tous' ? 'selected' : '' ?>>Tous les voyages</option>
            </select>
        </form>

        <!-- Liste des destinations affichées -->
        <div class="destination-liste">
            <?php 
            // Si on a choisi "tous", on affiche tout, sinon on prend les 6 premiers
            foreach (($filtre === 'tous' ? $voyages : array_slice($voyages, 0, 6)) as $voyage): 
            ?>
                <a class="destination" href="#">
                    <!-- Image du voyage -->
                    <img src="<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['titre']) ?>">

                    <!-- Titre du voyage -->
                    <h3><?= htmlspecialchars($voyage['titre']) ?></h3>

                    <!-- Prix et note du voyage (en étoiles) -->
                    <p>
                        Prix : <?= htmlspecialchars($voyage['prix']) ?> €<br>
                        Note : <?= str_repeat('★', $voyage['note']) . str_repeat('☆', 5 - $voyage['note']) ?>
                    </p>

                    <!-- Description courte du voyage -->
                    <p><?= htmlspecialchars($voyage['description']) ?></p>

                    <!-- Bouton pour aller vers la page de réservation -->
                    <form method="GET" action="voyage_detail.php">
                        <input type="hidden" name="id" value="<?= $voyage['id'] ?>">
                        <button class="acheter-btn" type="submit">Reserver</button>
                    </form>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="js/theme.js"></script>

</body>
</html>
