<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit;
}

// Vérifier que le panier existe
if (!isset($_SESSION['commande'])) {
    echo "<p>Votre panier est vide.</p>";
    exit;
}

$commande = $_SESSION['commande'];
$voyage_id = $commande['voyage_id'];
$titre = $commande['titre'];
$date_depart = $commande['date_depart'];
$date_retour = $commande['date_retour'];
$duree = $commande['duree'];
$prix_total = $commande['prix_total'];
$recap = $commande['recap'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panier</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link id="theme-css" rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="acceuil">

<?php include 'header.php'; ?>

<main class="presentation">
    <h2>Récapitulatif de votre commande</h2>
    <p>Merci pour votre commande ! Voici les détails de votre voyage sélectionné :</p>

    <!-- Infos principales du voyage -->
    <div class="intro">
        <p><strong>Voyage :</strong> <?= htmlspecialchars($titre) ?></p>
        <p><strong>Dates :</strong> du <?= htmlspecialchars($date_depart) ?> au <?= htmlspecialchars($date_retour) ?> (<?= htmlspecialchars($duree) ?> jours)</p>
    </div>

    <!-- Détails des options -->
    <section>
        <h3>Options choisies :</h3>
        <ul>
            <?php if (empty($recap)): ?>
                <li>Aucune option supplémentaire sélectionnée.</li>
            <?php else: ?>
                <?php foreach ($recap as $opt): ?>
                    <li>
                        <?= htmlspecialchars($opt['nom']) ?> :
                        <?= htmlspecialchars($opt['choix']) ?> –
                        <?= $opt['personnes'] ?> pers –
                        <?= $opt['prix_total'] ?> €
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <h3>Prix total à payer : <?= number_format($prix_total, 2) ?> €</h3>
    </section>

    <!-- Bouton pour modifier -->
    <a href="voyage_detail.php?id=<?= urlencode($voyage_id) ?>">
        <button class="bouton-paiement" type="button">Modifier mon voyage</button>
    </a>

    <!-- Paiement -->
    <?php
        include("getapikey.php");
        $transaction = uniqid('TXN');
        $vendeur = "TEST";
        $api_key = getAPIKey($vendeur);
        $retour = "http://localhost:8080/retour_paiement.php";
        $control = md5($api_key."#".$transaction."#".$prix_total."#".$vendeur."#".$retour."#");
    ?>

    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?= $transaction ?>">
        <input type="hidden" name="montant" value="<?= $prix_total ?>">
        <input type="hidden" name="vendeur" value="<?= $vendeur ?>">
        <input type="hidden" name="retour" value="<?= $retour ?>">
        <input type="hidden" name="control" value="<?= $control ?>">
        <input type="submit" class="bouton-paiement" value="Valider et payer">
    </form>
    <form method="POST" action="supprimer_panier.php" style="margin-top: 20px;">
    	<input type="submit" class="bouton-paiement" value="Vider le panier">
    </form>
</main>

<script src="js/theme.js"></script>
</body>
</html>

