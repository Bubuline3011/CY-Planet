<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche</title>

    <link id="theme-css" rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="recherche">
    <?php include 'header.php'; ?>

    <div class="container-recherche">
        <div class="recherche-boite">
            <h1>Trouvez votre séjour idéal :</h1>

            <form method="GET" action="recherche.php">
                <input type="text" name="titre" placeholder="Nom du voyage" class="champ-recherche" />

                <select name="note_min">
                    <option value="">Note minimale</option>
                    <option value="1">⭐ et +</option>
                    <option value="2">⭐⭐ et +</option>
                    <option value="3">⭐⭐⭐ et +</option>
                    <option value="4">⭐⭐⭐⭐ et +</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                </select>

                <input type="number" name="prix_min" placeholder="Prix min (€)" min="0" />
                <input type="number" name="prix_max" placeholder="Prix max (€)" min="0" />
                <input type="number" name="duree_max" placeholder="Durée max (jours)" min="1" />

                <select name="theme">
                    <option value="">Thème</option>
                    <option value="aventure">Aventure</option>
                    <option value="culture">Culture</option>
                    <option value="exploration">Exploration</option>
                    <option value="festival">Festival</option>
                    <option value="musique">Musique</option>
                    <option value="nature">Nature</option>
                    <option value="sport">Sport</option>
                </select>

                <select id="transport" name="transport">
                    <option value="">Choisissez votre transport pour vous y rendre</option>
                    <option value="voiture">Voiture volante spatiale</option>
                    <option value="portail">Portail interdimensionnel</option>
                    <option value="vaisseaux">Vaisseaux Spatial</option>
                    <option value="Train">Train magnétique interstellaire</option>
                    <option value="mongolfière">Montgolfière spatiale</option>
                </select>
                <button type="submit" class="button">Rechercher</button>
            </form>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $filtres = $_GET;
            $index = json_decode(file_get_contents("data/index_voyages.json"), true);

            echo '<div class="resultats-filtrage">';
            echo '<div class="tri-zone">';
            echo '<label for="tri">Trier par :</label>';
            echo '<select id="tri">';
            echo '<option value="">-- Choisir --</option>';
            echo '<option value="prix">Prix croissant</option>';
            echo '<option value="prix_desc">Prix décroissant</option>';
            echo '<option value="duree">Durée croissante</option>';
            echo '<option value="duree_desc">Durée décroissante</option>';
            echo '<option value="note">Note croissante</option>';
            echo '<option value="note_desc">Note décroissante</option>';
            echo '</select>';
            echo '</div>';

            echo '<div class="destination-recherche">';

            foreach ($index as $id => $fichier) {
                $voyage = json_decode(file_get_contents("data/" . $fichier), true);

                // Filtres
                if (!empty($filtres['titre']) && stripos($voyage['titre'], $filtres['titre']) === false) continue;
                if (!empty($filtres['note_min']) && $voyage['note'] < (int)$filtres['note_min']) continue;
                if (!empty($filtres['prix_min']) && $voyage['prix_total'] < (int)$filtres['prix_min']) continue;
                if (!empty($filtres['prix_max']) && $voyage['prix_total'] > (int)$filtres['prix_max']) continue;
                if (!empty($filtres['duree_max']) && $voyage['duree'] > (int)$filtres['duree_max']) continue;
                if (!empty($filtres['theme']) && $voyage['theme'] !== $filtres['theme']) continue;
                if (!empty($filtres['transport']) && $voyage['transport'] !== $filtres['transport']) continue;

                // Affichage
                echo '<a href="voyage_detail.php?id=' . $voyage['id'] . '" class="destination" 
                    data-prix="' . $voyage['prix_total'] . '" 
                    data-duree="' . $voyage['duree'] . '" 
                    data-note="' . $voyage['note'] . '">';
                echo '<img src="' . htmlspecialchars($voyage['image']) . '" alt="Image de ' . htmlspecialchars($voyage['titre']) . '">';
                echo '<h3>' . htmlspecialchars($voyage['titre']) . '</h3>';
                echo '<p>' . htmlspecialchars($voyage['description']) . '</p>';
                echo '<p>' . htmlspecialchars($voyage['prix_total']) . ' €</p>';
                echo '<p>Note : ' . str_repeat('⭐', (int)$voyage['note']) . '</p>';
                echo '</a>';
            }

            echo '</div>'; // destination-recherche
            echo '</div>'; // resultats-filtrage
        }
        ?>
    </div>

    <footer>
        <p>&copy; 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>

    <script src="js/theme.js"></script>
    <script src="js/tri_recherche.js"></script>

</body>
</html>

