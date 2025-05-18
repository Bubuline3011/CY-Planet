<?php
session_start();
if (!isset($_GET['id'])) {
    die("Erreur : aucun identifiant de voyage fourni.");
}
$voyage_id = $_GET['id'];

$index_path = 'data/index_voyages.json';
if (!file_exists($index_path)) {
    die("Erreur : index des voyages manquant.");
}

$index = json_decode(file_get_contents($index_path), true);

if (!isset($index[$voyage_id])) {
    die("Erreur : voyage non trouvé.");
}

$voyage_file = 'data/' . $index[$voyage_id];
$voyageData = json_decode(file_get_contents($voyage_file), true);

if (!$voyageData) {
    die("Erreur de chargement du voyage.");
}

if (isset($_SESSION['email'])) {
    $utilisateur_email = $_SESSION['email'];
    $utilisateur_path = 'data/utilisateur.json';

    $utilisateurs = json_decode(file_get_contents($utilisateur_path), true);
    $voyage_nom = isset($voyageData['titre']) ? $voyageData['titre'] : 'Voyage inconnu';
    $date_consultation = date("Y-m-d H:i:s");

    foreach ($utilisateurs as &$utilisateur) {
        if ($utilisateur['email'] === $utilisateur_email) {
            $deja_consulte = false;
            foreach ($utilisateur['voyages_consultes'] as &$vc) {
                if ($vc['id'] == $voyage_id) {
                    $vc['date_consultation'] = $date_consultation;
                    $deja_consulte = true;
                    break;
                }
            }

            if (!$deja_consulte) {
                $utilisateur['voyages_consultes'][] = [
                    'id' => $voyage_id,
                    'nom' => $voyage_nom,
                    'date_consultation' => $date_consultation
                ];
            }

            break;
        }
    }

    file_put_contents($utilisateur_path, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($voyageData['titre']); ?></title>
    <link id="theme-css" rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body class="detail">
    <?php include 'header.php'; ?>

    <div class="page-contenu">
        <h1><?php echo htmlspecialchars($voyageData['titre']); ?></h1>

        <p><strong>Dates :</strong> du <?php echo $voyageData['date_depart']; ?> au <?php echo $voyageData['date_retour']; ?> (<?php echo $voyageData['duree']; ?> jours)</p>
        <p><strong>Description :</strong> <?php echo $voyageData['specificites']; ?></p>
        <p><strong>Prix de base :</strong> <?php echo $voyageData['prix_total']; ?> €</p>

        <form action="ajouter_panier.php" method="POST">
            <input type="hidden" name="voyage_id" value="<?php echo $voyageData['id']; ?>">
            <input type="hidden" id="voyage_id" value="<?= $voyage_id ?>">

            <?php foreach ($voyageData['etapes'] as $etapeIndex => $etape): ?>
                <fieldset style="margin-top: 30px;">
                    <legend><strong><?php echo htmlspecialchars($etape['titre']); ?></strong> 
                        (du <?php echo $etape['date_arrivee']; ?> au <?php echo $etape['date_depart']; ?>)
                    </legend>

                    <p><em><?php echo $etape['position']['nom_lieu']; ?> [<?php echo $etape['position']['gps']; ?>]</em></p>

                    <!-- Conteneur dynamique -->
                    <div class="etape-container" data-etape-id="<?= $etape['id'] ?>">
                        <div id="options_etape_<?= $etape['id'] ?>"></div> 
                    </div>
                </fieldset>
            <?php endforeach; ?>

            <!-- Prix estimé dynamique -->
            <p id="prix-estime" style="font-weight: bold; font-size: 1.2em; margin-top: 20px;">
                Prix des options : <span id="valeur-prix-estime">0.00</span> €
            </p>

            <button type="submit">Ajouter au panier</button>
        </form>
    </div>

    <script src="js/theme.js"></script>
    <script src="js/voyage_detail.js"></script>
</body>
</html>

