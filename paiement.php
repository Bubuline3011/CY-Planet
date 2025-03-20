<?php
session_start();
if (!isset($_SESSION['connecte']) || !isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}

// Récupération des infos du voyage sélectionné
$voyage = $_SESSION['voyage_selectionne'] ?? ['titre' => 'Voyage inconnu', 'prix' => 0];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="paiement">
	<?php include 'header.php'; ?>
    <div class="paiement-container">
        <h2>Paiement du voyage</h2>
        <p><strong>Voyage :</strong> <?= htmlspecialchars($voyage['titre']); ?></p>
        <p><strong>Prix :</strong> <?= number_format($voyage['prix'], 2, '.', '') . " €"; ?></p>

        <form action="traitement_paiement.php" method="POST">
            <label>Numéro de carte bancaire :</label>
            <input type="text" name="numero_carte" pattern="\d{16}" required placeholder="16 chiffres">

            <label>Nom du propriétaire :</label>
            <input type="text" name="nom_proprietaire" required placeholder="Nom sur la carte">

            <label>Date d’expiration :</label>
            <input type="month" id="expiration" name="expiration" required placeholder="AAAA-MM">

            <label>Cryptogramme :</label>
            <input type="text" name="cvv" pattern="\d{3}" required placeholder="3 chiffres">

            <input type="hidden" name="voyage_id" value="<?= $voyage['id'] ?? ''; ?>">
            <input type="hidden" name="montant" value="<?= number_format($voyage['prix'], 2, '.', ''); ?>">

            <button type="submit" class="button">Valider et payer</button>
        </form>
    </div>
</body>
</html>

