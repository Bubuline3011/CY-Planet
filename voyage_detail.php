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

            <?php foreach ($voyageData['etapes'] as $etapeIndex => $etape): ?>
                <fieldset style="margin-top: 30px;">
                    <legend><strong><?php echo htmlspecialchars($etape['titre']); ?></strong> 
                        (du <?php echo $etape['date_arrivee']; ?> au <?php echo $etape['date_depart']; ?>)
                    </legend>

                    <p><em><?php echo $etape['position']['nom_lieu']; ?> [<?php echo $etape['position']['gps']; ?>]</em></p>

                    <?php foreach ($etape['options'] as $optionIndex => $option): ?>
                        <div style="margin-bottom: 15px;">
                            <label for="option_<?php echo $etapeIndex . '_' . $optionIndex; ?>">
                                <?php echo ucfirst($option['type']); ?> : <?php echo $option['nom']; ?>
                            </label>

                            <select name="options[<?php echo $etapeIndex; ?>][<?php echo $optionIndex; ?>][choix_utilisateur]" 
                                    id="option_<?php echo $etapeIndex . '_' . $optionIndex; ?>">
                                <?php foreach ($option['valeurs_possibles'] as $valeur): ?>
                                    <option 
                                        value="<?php echo htmlspecialchars($valeur); ?>" 
                                        data-prix="<?php echo isset($option['prix_par_valeur'][$valeur]) ? $option['prix_par_valeur'][$valeur] : 0; ?>"
                                        <?php if (isset($option['choix_utilisateur']) && $valeur == $option['choix_utilisateur']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($valeur); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <input type="hidden" name="options[<?php echo $etapeIndex; ?>][<?php echo $optionIndex; ?>][type]" 
                                   value="<?php echo $option['type']; ?>">
                            <input type="hidden" name="options[<?php echo $etapeIndex; ?>][<?php echo $optionIndex; ?>][nom]" 
                                   value="<?php echo $option['nom']; ?>">

                            <label for="nb_<?php echo $etapeIndex . '_' . $optionIndex; ?>">Nombre de personnes :</label>
                            <input type="number" id="nb_<?php echo $etapeIndex . '_' . $optionIndex; ?>" 
                                   name="options[<?php echo $etapeIndex; ?>][<?php echo $optionIndex; ?>][personnes]" 
                                   value="<?php echo isset($option['personnes']) ? $option['personnes'] : 1; ?>" 
                                   min="0" required>
                        </div>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>

            <!-- Prix estimé dynamique -->
            <p id="prix-estime" style="font-weight: bold; font-size: 1.2em; margin-top: 20px;">
                Prix estimé : <span id="valeur-prix-estime">0</span> €
            </p>

            <button type="submit">Ajouter au panier</button>
        </form>
    </div>

    <script src="js/theme.js"></script>
    <script src="js/voyage_detail.js"></script>
</body>
</html>

