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

            <form id="form-filtrage">
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

            </form>
        </div>

        <div class="resultats-filtrage">
            <div class="tri-zone">
                <label for="tri">Trier par :</label>
                <select id="tri">
                    <option value="">-- Choisir --</option>
                    <option value="prix">Prix croissant</option>
                    <option value="prix_desc">Prix décroissant</option>
                    <option value="duree">Durée croissante</option>
                    <option value="duree_desc">Durée décroissante</option>
                    <option value="note">Note croissante</option>
                    <option value="note_desc">Note décroissante</option>
                </select>
            </div>

            <div class="destination-recherche"></div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>

    <?php
        // Chargement des données PHP côté client (phase 3 autorisée)
        $index = json_decode(file_get_contents("data/index_voyages.json"), true);
        $voyages = [];
foreach ($index as $fichier) {
    $voyages[] = json_decode(file_get_contents("data/" . $fichier), true);
}

    ?>
    <script>
        const voyages = <?php echo json_encode($voyages); ?>;
    </script>

    <script src="js/theme.js"></script>
    <script src="js/tri_recherche.js"></script>

</body>
</html>

