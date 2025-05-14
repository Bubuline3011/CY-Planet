
<?php
session_start();
$q = isset($_GET['q']) ? $_GET['q'] : '';
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
        <h1>Trouvez votre séjour idéal :</h1>
        <div class="filtres">
            <input type="search" id="recherche-input" placeholder="Nom du voyage..." value="<?= htmlspecialchars($q) ?>">
            <select id="filtre-theme">
                <option value="">Tous les thèmes</option>
                <option value="aventure">Aventure</option>
                <option value="culture">Culture</option>
                <option value="exploration">Exploration</option>
                <option value="festival">Festival</option>
                <option value="musique">Musique</option>
                <option value="nature">Nature</option>
                <option value="sport">Sport</option>
            </select>
            <select id="filtre-transport">
                <option value="">Tous les transports</option>
                <option value="voiture">Voiture volante spatiale</option>
                <option value="portail">Portail interdimensionnel</option>
                <option value="vaisseaux">Vaisseaux Spatial</option>
                <option value="Train">Train magnétique interstellaire</option>
                <option value="mongolfière">Montgolfière spatiale</option>
            </select>
            <input type="number" id="filtre-prix-min" placeholder="Prix min (€)" min="0" />
            <input type="number" id="filtre-prix-max" placeholder="Prix max (€)" min="0" />
            <input type="number" id="filtre-duree-max" placeholder="Durée max (jours)" min="1" />
            <select id="filtre-note">
                <option value="">Toutes les notes</option>
                <option value="1">⭐ et +</option>
                <option value="2">⭐⭐ et +</option>
                <option value="3">⭐⭐⭐ et +</option>
                <option value="4">⭐⭐⭐⭐ et +</option>
                <option value="5">⭐⭐⭐⭐⭐</option>
            </select>
            <button id="reset-filtres">Reset</button>
        </div>
        <div class="actions-tri">
            <button data-tri="prix">Trier par Prix</button>
            <button data-tri="duree">Trier par Durée</button>
            <button data-tri="note">Trier par Note</button>
        </div>
        <div class="destination-recherche" id="resultats">
            <!-- Les résultats JS ici -->
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Cosmo Trip. Tous droits réservés.</p>
    </footer>
    <script src="js/theme.js"></script>
    <script src="js/recherche_dynamique.js"></script>
</body>
</html>
