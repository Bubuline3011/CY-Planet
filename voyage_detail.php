<?php
session_start();
// Vérifie si un identifiant de voyage a bien été envoyé via l'URL
if (!isset($_GET['id'])) {
    die("Erreur : aucun identifiant de voyage fourni.");
}
$voyage_id = $_GET['id'];

// On charge l'index qui relie les IDs aux fichiers de voyage
$index_path = 'data/index_voyages.json';
if (!file_exists($index_path)) {
    die("Erreur : index des voyages manquant.");
}

$index = json_decode(file_get_contents($index_path), true);

// On vérifie si l’ID correspond à un fichier dans l’index
if (!isset($index[$voyage_id])) {
    die("Erreur : voyage non trouvé.");
}

// On récupère le chemin du fichier du voyage à afficher
$voyage_file = 'data/' . $index[$voyage_id];
$voyageData = json_decode(file_get_contents($voyage_file), true);

// Vérification du bon chargement des données
if (!$voyageData) {
    die("Erreur de chargement du voyage.");
}

session_start(); // Lance la session (si ce n’est pas déjà fait dans header.php)

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
                    $vc['date_consultation'] = $date_consultation; // mise à jour date
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

    <!-- Feuille de style principale -->
    <link id="theme-css" rel="stylesheet" href="style.css">
</head>

<body class="detail">
    <!-- Inclusion du header commun -->
    <?php include 'header.php'; ?>

    <div class="page-contenu">
        <!-- Titre du voyage -->
        <h1><?php echo htmlspecialchars($voyageData['titre']); ?></h1>

        <!-- Infos générales sur le voyage -->
        <p><strong>Dates :</strong> du <?php echo $voyageData['date_depart']; ?> au <?php echo $voyageData['date_retour']; ?> (<?php echo $voyageData['duree']; ?> jours)</p>
        <p><strong>Description :</strong> <?php echo $voyageData['specificites']; ?></p>
        <p><strong>Prix de base :</strong> <?php echo $voyageData['prix_total']; ?> €</p>

        <!-- Formulaire pour ajouter ce voyage au panier avec options personnalisées -->
        <form action="ajouter_panier.php" method="POST">
            <input type="hidden" name="voyage_id" value="<?php echo $voyageData['id']; ?>">

            <!-- On parcourt chaque étape du voyage -->
            <?php foreach ($voyageData['etapes'] as $etapeIndex => $etape): ?>
                <fieldset style="margin-top: 30px;">
                    <!-- Titre de l’étape avec ses dates -->
                    <legend><strong><?php echo htmlspecialchars($etape['titre']); ?></strong> 
                        (du <?php echo $etape['date_arrivee']; ?> au <?php echo $etape['date_depart']; ?>)
                    </legend>

                    <!-- Lieu de l’étape -->
                    <p><em><?php echo $etape['position']['nom_lieu']; ?> [<?php echo $etape['position']['gps']; ?>]</em></p>

                    <!-- Boucle sur les options de l’étape (hébergement, activité, transport...) -->
                    <?php foreach ($etape['options'] as $optionIndex => $option): ?>
                        <div style="margin-bottom: 15px;">
                            <!-- Type et nom de l’option -->
                            <label for="option_<?php echo $etapeIndex . '_' . $optionIndex; ?>">
                                <?php echo ucfirst($option['type']); ?> : <?php echo $option['nom']; ?>
                            </label>

                            <!-- Sélecteur pour choisir une valeur possible -->
                            <select name="options[<?php echo $etapeIndex; ?>][<?php echo $optionIndex; ?>][choix_utilisateur]" 
                                    id="option_<?php echo $etapeIndex . '_' . $optionIndex; ?>">
                                <?php foreach ($option['valeurs_possibles'] as $valeur): ?>
                                    <option value="<?php echo htmlspecialchars($valeur); ?>" 
                                        <?php if ($valeur == $option['choix_utilisateur']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($valeur); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <?php
                                // Calcul du prix total pour cette option (en fonction du choix + nombre de personnes)
                                $choix = $option['choix_utilisateur'];
                                $prix_unitaire = isset($option['prix_par_valeur'][$choix]) ? $option['prix_par_valeur'][$choix] : 0;
                                $nb_personnes = isset($option['personnes']) ? (int)$option['personnes'] : 1;
                                $prix_total = $prix_unitaire * $nb_personnes;
                            ?>

                            <!-- Champs cachés pour envoyer le type et le nom de l’option -->
                            <input type="hidden" name="options[<?php echo $etapeIndex; ?>][<?php echo $optionIndex; ?>][type]" 
                                   value="<?php echo $option['type']; ?>">
                            <input type="hidden" name="options[<?php echo $etapeIndex; ?>][<?php echo $optionIndex; ?>][nom]" 
                                   value="<?php echo $option['nom']; ?>">

                            <!-- Saisie du nombre de personnes concernées -->
                            <label for="nb_<?php echo $etapeIndex . '_' . $optionIndex; ?>">Nombre de personnes :</label>
                            <input type="number" id="nb_<?php echo $etapeIndex . '_' . $optionIndex; ?>" 
                                   name="options[<?php echo $etapeIndex; ?>][<?php echo $optionIndex; ?>][personnes]" 
                                   value="<?php echo $nb_personnes; ?>" min="0" required>
                        </div>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>

            <!-- Bouton pour envoyer les choix et ajouter au panier -->
            <button type="submit">Ajouter au panier</button>
        </form>
    </div>
    <script src="js/theme.js"></script>
</body>
</html>
