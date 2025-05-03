<?php
// On démarre la session pour stocker des infos utilisateur
session_start();

// Vérifier qu'un identifiant de voyage a été envoyé
if (!isset($_POST['voyage_id'])) {
    echo "<p>Erreur : aucun identifiant de voyage fourni.</p>";
    exit;
}

// On récupère l'ID du voyage choisi
$voyage_id = $_POST['voyage_id'];

// On vérifie que le fichier index des voyages existe
$index_path = 'data/index_voyages.json';
if (!file_exists($index_path)) {
    echo "<p>Erreur : index des voyages manquant.</p>";
    exit;
}

// On récupère l'index des fichiers voyages
$index = json_decode(file_get_contents($index_path), true);

// Vérifier si l'ID du voyage existe dans l'index
if (!isset($index[$voyage_id])) {
    echo "<p>Erreur : voyage non trouvé.</p>";
    exit;
}

// On récupère le chemin du vrai fichier du voyage, puis on le lit
$voyage_file = 'data/' . $index[$voyage_id];
$voyage = json_decode(file_get_contents($voyage_file), true);

// On récupère les options sélectionnées par l'utilisateur (ou vide par défaut)
$options_selectionnees = $_POST['options'] ?? [];

// Prix de base du voyage
$prix_total = $voyage['prix_total'];

// Tableau pour récapitulatif des options payantes
$recap = [];

// Parcours de toutes les options sélectionnées
foreach ($options_selectionnees as $etapeIndex => $options) {
    foreach ($options as $optionIndex => $option) {
        $type = $option['type'] ?? 'inconnu';
        $nom = $option['nom'] ?? 'Option inconnue';
        $choix = $option['choix_utilisateur'] ?? 'Non';
        $nb = isset($option['personnes']) ? (int)$option['personnes'] : 1;

        // Rechercher le prix correspondant dans le fichier JSON du voyage
        $prix_unitaire = 0;
        foreach ($voyage['etapes'][$etapeIndex]['options'] as $opt) {
            if ($opt['type'] === $type && $opt['nom'] === $nom) {
                $prix_unitaire = $opt['prix_par_valeur'][$choix] ?? 0;
                break;
            }
        }

        // Calcul du sous-total
        $sous_total = $prix_unitaire * $nb;

        // Si c’est une option payante, on l’ajoute au récap
        if (strtolower(trim($choix)) !== 'non' && $sous_total > 0) {
            $recap[] = [
                'nom' => $nom,
                'choix' => $choix,
                'personnes' => $nb,
                'prix_unitaire' => $prix_unitaire,
                'prix_total' => $sous_total
            ];
            $prix_total += $sous_total;
        }
    }
}

// On stocke les infos de la commande dans la session pour l'utiliser après le paiement
$_SESSION['commande'] = [
    'voyage_id' => $voyage_id,
    'titre' => $voyage['titre'],
    'date_depart' => $voyage['date_depart'],
    'date_retour' => $voyage['date_retour'],
    'prix_total' => $prix_total,
    'etapes' => $voyage['etapes']
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Affichage responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>

    <!-- Style général -->
    <link id="theme-css" rel="stylesheet" href="style.css">

    <!-- Icônes boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="acceuil">
    <?php
        // Génération des infos pour le paiement (fictif ici)
        include("getapikey.php");
        $transaction = uniqid('TXN'); // ID unique pour cette transaction
        $montant = $prix_total;
        $vendeur = "TEST";
        $api_key = getAPIKey($vendeur); // Clé d'API récupérée via un fichier externe
        $retour = "http://localhost:8080/retour_paiement.php";
        $control = md5($api_key."#".$transaction."#".$montant."#".$vendeur."#".$retour."#");
    ?>
    
    <main class="presentation">
        <h2>Récapitulatif de votre commande</h2>
        <p>Merci pour votre commande ! Voici les détails de votre voyage sélectionné :</p>
	
        <!-- Infos principales du voyage -->
        <div class="intro">
            <p><strong>Voyage :</strong> <?= htmlspecialchars($voyage['titre']) ?></p>
            <p><strong>Dates :</strong> du <?= htmlspecialchars($voyage['date_depart']) ?> au <?= htmlspecialchars($voyage['date_retour']) ?> (<?= htmlspecialchars($voyage['duree']) ?> jours)</p>
            <p><strong>Prix de base :</strong> <?= htmlspecialchars($voyage['prix_total']) ?> €</p>
        </div>

        <!-- Détails des options -->
        <section>
            <h3>Options choisies :</h3>
            <ul>
                <?php if (empty($recap)): ?>
                    <li>Aucune option supplémentaire sélectionnée.</li>
                <?php else: ?>
                    <?php foreach ($recap as $opt): ?>
                        <li><?= htmlspecialchars($opt['nom']) ?> : <?= htmlspecialchars($opt['prix_total']) ?> €</li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <h3>Prix total à payer : <?= $prix_total ?> €</h3>
        </section>

        <!-- Bouton pour modifier le voyage -->
        <a href="voyage_detail.php?id=<?= urlencode($voyage_id) ?>">
            <button class="bouton-paiement" type="bouton-paiement">Modifier mon voyage</button>
        </a>
    
        <!-- Formulaire pour aller vers le paiement (cybank fictif) -->
        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
            <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
            <input type="hidden" name="montant" value="<?php echo $montant; ?>">
            <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
            <input type="hidden" name="retour" value="<?php echo $retour; ?>">
            <input type="hidden" name="control" value="<?php echo $control; ?>">
            <input type="submit" class="bouton-paiement" value="Valider et payer">
        </form>
    </main>
    <script src="js/theme.js"></script>
</body>
</html>
