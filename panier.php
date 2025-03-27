<?php
session_start();

if (!isset($_POST['voyage_id'])) {
    echo "<p>Erreur : aucun identifiant de voyage fourni.</p>";
    exit;
}

$voyage_id = $_POST['voyage_id'];

$index_path = 'data/index_voyages.json';
if (!file_exists($index_path)) {
    echo "<p>Erreur : index des voyages manquant.</p>";
    exit;
}

$index = json_decode(file_get_contents($index_path), true);
if (!isset($index[$voyage_id])) {
    echo "<p>Erreur : voyage non trouvé.</p>";
    exit;
}

$voyage_file = 'data/' . $index[$voyage_id];
$voyage = json_decode(file_get_contents($voyage_file), true);

$options_selectionnees = $_POST['options'] ?? [];
$prix_total = $voyage['prix_total'];
$recap = [];

foreach ($options_selectionnees as $etape => $options) {
    foreach ($options as $valeur) {
        list($nom, $prix) = explode('|', $valeur);
        $recap[] = [
            'nom' => $nom,
            'prix' => (float)$prix
        ];
        $prix_total += (float)$prix;
    }
}

// Stocker les données dans la session pour les récupérer après paiement
$_SESSION['commande'] = [
    'voyage_id' => $voyage_id,
    'titre' => $voyage['titre'],
    'prix_total' => $prix_total
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="acceuil">
    <?php
        include("getapikey.php");
        $transaction = uniqid('TXN');
        $montant = $prix_total;
        $vendeur = "TEST";
        $api_key = getAPIKey($vendeur);
        $retour = "http://localhost:8080/retour_paiement.php";
        $control = md5($api_key."#".$transaction."#".$montant."#".$vendeur."#".$retour."#");
    ?>
    
    <main class="presentation">
        <h2>Récapitulatif de votre commande</h2>
        <p>Merci pour votre commande ! Voici les détails de votre voyage sélectionné :</p>

        <div class="intro">
            <p><strong>Voyage :</strong> <?= htmlspecialchars($voyage['titre']) ?></p>
            <p><strong>Dates :</strong> du <?= htmlspecialchars($voyage['date_depart']) ?> au <?= htmlspecialchars($voyage['date_retour']) ?> (<?= htmlspecialchars($voyage['duree']) ?> jours)</p>
            <p><strong>Prix de base :</strong> <?= htmlspecialchars($voyage['prix_total']) ?> €</p>
        </div>

        <section>
            <h3>Options choisies :</h3>
            <ul>
                <?php if (empty($recap)): ?>
                    <li>Aucune option supplémentaire sélectionnée.</li>
                <?php else: ?>
                    <?php foreach ($recap as $opt): ?>
                        <li><?= htmlspecialchars($opt['nom']) ?> : <?= $opt['prix'] ?> €</li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <h3>Prix total à payer : <?= $prix_total ?> €</h3>
        </section>

    
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
        <input type="hidden" name="montant" value="<?php echo $montant; ?>">
        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
        <input type="hidden" name="retour" value="<?php echo $retour; ?>">
        <input type="hidden" name="control" value="<?php echo $control; ?>">
        <input type="submit" class="bouton-paiement" value="Valider et payer">
    </form>
    </main>
</body>
</html>

